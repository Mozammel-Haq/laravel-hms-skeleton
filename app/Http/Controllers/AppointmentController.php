<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Models\Appointment;
use App\Models\Consultation;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Visit;
use App\Notifications\AppointmentBookedNotification;
use App\Notifications\DoctorAppointmentCancelledNotification;
use App\Services\AppointmentService;
use App\Services\BillingService;
use App\Support\TenantContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

/**
 * Manages patient appointments with doctors.
 *
 * Responsibilities:
 * - Appointment Scheduling (Create, Edit, Cancel)
 * - Status Management (Pending, Confirmed, Completed, etc.)
 * - Slot Availability Checking
 * - Notifications (Patient, Doctor)
 * - Viewing Appointment Details (History, Invoices, Lab Orders)
 */
class AppointmentController extends Controller
{
    protected $appointmentService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    /**
     * Display a listing of appointments.
     *
     * Supports filtering by:
     * - Status: 'pending', 'confirmed', 'completed', 'cancelled', 'trashed'
     * - Date: 'today', 'upcoming', 'history'
     * - Search: Patient name/code, Doctor name, Appointment date/type
     * - Date Range: Custom from/to dates
     *
     * Role restrictions:
     * - Doctors can only view their own appointments.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        Gate::authorize('viewAny', Appointment::class);

        $query = Appointment::with(['patient', 'doctor.user']);

        // Restrict doctors to view only their own appointments
        $user = auth()->user();
        if ($user && $user->hasRole('Doctor') && $user->doctor) {
            $query->where('doctor_id', $user->doctor->id);
        }

        if (request('status') === 'trashed') {
            $query->onlyTrashed();
        } elseif (request()->filled('status')) {
            $query->where('status', request('status'));
        }

        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('appointment_date', 'like', '%'.$search.'%')
                    ->orWhere('status', 'like', '%'.$search.'%')
                    ->orWhere('appointment_type', 'like', '%'.$search.'%')
                    ->orWhereHas('patient', function ($sub) use ($search) {
                        $sub->where('name', 'like', '%'.$search.'%')
                            ->orWhere('patient_code', 'like', '%'.$search.'%');
                    })
                    ->orWhereHas('doctor.user', function ($sub) use ($search) {
                        $sub->where('name', 'like', '%'.$search.'%');
                    });
            });
        }

        if (request('filter') === 'today') {
            $query->whereDate('appointment_date', today());
        } elseif (request('filter') === 'upcoming') {
            $query->whereDate('appointment_date', '>=', today());
        } elseif (request('filter') === 'history') {
            $query->whereDate('appointment_date', '<', today());
        }

        if (request()->filled('from')) {
            $query->whereDate('appointment_date', '>=', request('from'));
        }

        if (request()->filled('to')) {
            $query->whereDate('appointment_date', '<=', request('to'));
        }

        $filter = request('filter');
        if (! $filter || $filter === 'today' || $filter === 'upcoming') {
            $query->orderByDesc('appointment_date')
                ->orderByDesc('start_time')
                ->orderByDesc('id');
        } else {
            $query->orderByDesc('appointment_date')
                ->orderByDesc('start_time')
                ->orderByDesc('id');
        }

        $appointments = $query
            ->paginate(20)
            ->withQueryString();

        return view('appointments.index', compact('appointments'));
    }

    /**
     * Restore a soft-deleted appointment.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore($id)
    {
        $appointment = Appointment::withTrashed()->findOrFail($id);
        Gate::authorize('delete', $appointment);

        $appointment->restore();
        // Optionally revert status if we changed it, or keep as is.
        // If we want to revert to pending or keep it as it was (which might be confusing if it was 'cancelled' then deleted).
        // Let's assume we just restore it.

        return redirect()->route('appointments.index')->with('success', 'Appointment restored successfully.');
    }

    /**
     * Show the form for creating a new appointment.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        Gate::authorize('create', Appointment::class);

        $doctors = Doctor::where('status', 'active')
            ->whereHas('clinics', function ($q) {
                $q->where('clinics.id', TenantContext::getClinicId());
            })
            ->get();
        $patients = Patient::all(); // to do---apply search later

        return view('appointments.create', compact('doctors', 'patients'));
    }

    /**
     * Store a newly created appointment in storage.
     *
     * Features:
     * - Validates request data
     * - Sets default status to 'pending'
     * - Calculates end time (default 15 mins)
     * - Sends notifications to Doctor and Patient
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreAppointmentRequest $request)
    {
        Gate::authorize('create', Appointment::class);

        $doctor = Doctor::findOrFail($request->doctor_id);
        $startTime = \Carbon\Carbon::createFromFormat('H:i', $request->start_time)->format('H:i:s');
        $endTime = $this->resolveEndTimeFromSchedule(
            $doctor,
            (string) $request->appointment_date,
            (string) $request->start_time,
            TenantContext::hasClinic() ? TenantContext::getClinicId() : null
        );

        // Check if patient already has an appointment on this date
        $exists = Appointment::where('patient_id', $request->patient_id)
            ->whereDate('appointment_date', $request->appointment_date)
            ->whereIn('status', ['pending', 'confirmed', 'arrived', 'in_progress'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['appointment_date' => 'Patient already has an active appointment on this date.'])->withInput();
        }

        // Check if doctor is already booked at this time
        $doctorBooked = Appointment::where('doctor_id', $request->doctor_id)
            ->whereDate('appointment_date', $request->appointment_date)
            ->where('start_time', $startTime)
            ->whereIn('status', ['pending', 'confirmed', 'arrived', 'in_progress'])
            ->exists();

        if ($doctorBooked) {
            return back()->withErrors(['start_time' => 'Doctor is already booked at this time.'])->withInput();
        }

        // Create the appointment
        // Note: Slot availability should ideally be verified here before creation.
        $appointment = Appointment::create([
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->doctor_id,
            'department_id' => $doctor->primary_department_id,
            'appointment_date' => $request->appointment_date,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'status' => 'pending',
            'appointment_type' => $request->appointment_type ?? 'in_person',
            'booking_source' => 'reception',
            'visit_type' => ($request->type === 'follow_up') ? 'follow_up' : 'new',
            'reason_for_visit' => $request->reason ?? null,
            'created_by' => auth()->id(),
        ]);

        // Notify Doctor
        if ($doctor->user) {
            $doctor->user->notify(new AppointmentBookedNotification($appointment));
        }

        // Notify Patient
        $patient = Patient::find($request->patient_id);
        if ($patient) {
            $patient->notify(new AppointmentBookedNotification($appointment));
        }

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'Appointment booked successfully.');
    }

    /**
     * Display the specified appointment details.
     *
     * Loads related data:
     * - Patient Medical History
     * - Visit/Consultation Prescriptions
     * - Invoices (Consultation and others)
     * - Lab Test Orders
     *
     * @return \Illuminate\View\View
     */
    public function show(Appointment $appointment)
    {
        Gate::authorize('view', $appointment);

        $appointment->load([
            'patient.medicalHistory',
            'visit.consultation.prescriptions.items.medicine',
            'visit.invoices',
        ]);

        $consultationInvoice = \App\Models\Invoice::where('appointment_id', $appointment->id)
            ->where('invoice_type', 'consultation')
            ->latest()
            ->first();

        $labOrders = \App\Models\LabTestOrder::with(['test', 'results'])
            ->where('patient_id', $appointment->patient_id)
            ->latest()
            ->get();

        $invoices = \App\Models\Invoice::where('appointment_id', $appointment->id)
            ->orWhere('visit_id', $appointment->visit?->id)
            ->latest()
            ->get();

        return view('appointments.show', compact('appointment', 'consultationInvoice', 'labOrders', 'invoices'));
    }

    /**
     * Show the form for editing the specified appointment.
     *
     * @return \Illuminate\View\View
     */
    public function edit(Appointment $appointment)
    {
        Gate::authorize('update', $appointment);
        $doctors = Doctor::where('status', 'active')
            ->whereHas('clinics', function ($q) {
                $q->where('clinics.id', TenantContext::getClinicId());
            })
            ->get();

        return view('appointments.edit', compact('appointment', 'doctors'));
    }

    /**
     * Update the specified appointment in storage.
     *
     * Features:
     * - Validates new date/time (must be future if changed)
     * - Updates end time automatically
     * - Sends status change notification to Patient
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Appointment $appointment)
    {
        Gate::authorize('update', $appointment);

        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id,status,active',
            'appointment_date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($appointment) {
                    // Only enforce future dates if the date is being changed
                    if ($value !== $appointment->appointment_date && \Carbon\Carbon::parse($value)->lt(now()->startOfDay())) {
                        $fail('The '.$attribute.' must be a date after or equal to today.');
                    }
                },
            ],
            'start_time' => 'required',
            'status' => 'required|in:pending,confirmed,cancelled,completed,arrived,noshow',
            'appointment_type' => 'required|in:online,in_person',
            'meeting_link' => 'nullable|url',
            'reason_for_visit' => 'nullable|string|max:1000',
        ]);

        $doctor = Doctor::findOrFail($validated['doctor_id']);
        $validated['end_time'] = $this->resolveEndTimeFromSchedule(
            $doctor,
            (string) $validated['appointment_date'],
            (string) $validated['start_time'],
            TenantContext::hasClinic() ? TenantContext::getClinicId() : null
        );

        $oldStatus = $appointment->status;
        $appointment->update($validated);

        if (! in_array($oldStatus, ['confirmed', 'arrived'], true) && in_array($appointment->status, ['confirmed', 'arrived'], true)) {
            $this->ensureOnlineVisitAndConsultationInvoice($appointment);
        }

        // Notify Patient if status changed
        if ($oldStatus !== $appointment->status && $appointment->patient) {
            $appointment->patient->notify(new \App\Notifications\AppointmentStatusNotification($appointment));
        }

        // Notify Doctor if cancelled
        if ($appointment->status === 'cancelled' && $oldStatus !== 'cancelled' && $appointment->doctor && $appointment->doctor->user) {
            $appointment->doctor->user->notify(new DoctorAppointmentCancelledNotification($appointment, auth()->user()->name));
        }

        return redirect()->route('appointments.index')->with('success', 'Appointment updated successfully.');
    }

    private function resolveEndTimeFromSchedule(Doctor $doctor, string $date, string $startTime, ?int $clinicId): string
    {
        $start = trim($startTime);
        if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $start) === 1) {
            $start = substr($start, 0, 5);
        }

        $slots = $this->appointmentService->getAvailableSlots($doctor, $date, $clinicId);
        foreach ($slots as $slot) {
            if (($slot['start_time'] ?? null) === $start) {
                $end = $slot['end_time'] ?? null;
                if ($end && preg_match('/^\d{2}:\d{2}$/', $end) === 1) {
                    return $end.':00';
                }
            }
        }

        $fallbackStart = \Carbon\Carbon::createFromFormat('H:i', $start);

        return $fallbackStart->addMinutes(15)->format('H:i:s');
    }

    /**
     * Remove the specified appointment from storage (Soft Delete).
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Appointment $appointment)
    {
        Gate::authorize('delete', $appointment);

        $appointment->delete();

        return redirect()->route('appointments.index')->with('success', 'Appointment moved to trash.');
    }

    /**
     * Update the status of an appointment.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, Appointment $appointment)
    {
        Gate::authorize('update', $appointment);

        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed,arrived,noshow',
        ]);

        if ($appointment->status === 'confirmed' && $request->status === 'cancelled') {
            return back()->with('error', 'Cannot cancel a confirmed appointment.');
        }

        $oldStatus = $appointment->status;
        $appointment->update(['status' => $request->status]);

        if (! in_array($oldStatus, ['confirmed', 'arrived'], true) && in_array($appointment->status, ['confirmed', 'arrived'], true)) {
            $this->ensureOnlineVisitAndConsultationInvoice($appointment);
        }

        if ($appointment->patient) {
            $appointment->patient->notify(new \App\Notifications\AppointmentStatusNotification($appointment));
        }

        // Notify Doctor if cancelled
        if ($request->status === 'cancelled' && $appointment->doctor && $appointment->doctor->user) {
            $appointment->doctor->user->notify(new DoctorAppointmentCancelledNotification($appointment, auth()->user()->name));
        }

        return back()->with('success', 'Appointment status updated to '.ucfirst($request->status));
    }

    private function ensureOnlineVisitAndConsultationInvoice(Appointment $appointment): void
    {
        $appointment->loadMissing(['doctor', 'patient']);

        if ($appointment->appointment_type !== 'online') {
            return;
        }

        $existingInvoice = \App\Models\Invoice::where('appointment_id', $appointment->id)
            ->where('invoice_type', 'consultation')
            ->where('state', 'finalized')
            ->exists();

        if ($existingInvoice) {
            return;
        }

        DB::transaction(function () use ($appointment) {
            $visit = Visit::where('appointment_id', $appointment->id)->latest()->first();
            if (! $visit) {
                $visit = Visit::create([
                    'appointment_id' => $appointment->id,
                    'check_in_time' => null,
                    'visit_status' => 'waiting',
                ]);
            }

            $consultation = $visit->consultation;
            if (! $consultation) {
                $consultation = Consultation::create([
                    'visit_id' => $visit->id,
                    'doctor_id' => $appointment->doctor_id,
                    'patient_id' => $appointment->patient_id,
                ]);
                $visit->consultation_id = $consultation->id;
                $visit->save();
            }

            $feeInfo = app(AppointmentService::class)->calculateFee($appointment->doctor, $appointment->patient_id);
            $items = [[
                'item_type' => 'consultation',
                'reference_id' => $consultation->id,
                'description' => ($feeInfo['type'] ?? 'new') === 'follow_up' ? 'Consultation Fee (Follow-up)' : 'Consultation Fee (Initial)',
                'quantity' => 1,
                'unit_price' => $feeInfo['fee'] ?? 0,
            ]];

            app(BillingService::class)->createInvoice(
                $appointment->patient,
                $items,
                $appointment->id,
                discount: 0,
                tax: 0,
                visitId: $visit->id,
                invoiceType: 'consultation',
                createdBy: auth()->id(),
                finalize: true,
                clinicId: $appointment->clinic_id
            );

            $appointment->update([
                'fee' => $appointment->fee ?? ($feeInfo['fee'] ?? null),
                'visit_type' => $feeInfo['type'] ?? $appointment->visit_type,
            ]);
        });
    }

    /**
     * Get available slots for a doctor on a specific date.
     *
     * API endpoint for frontend/AJAX.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSlots(Request $request, Doctor $doctor)
    {
        $request->validate(['date' => 'required|date|after_or_equal:today']);

        $slots = $this->appointmentService->getAvailableSlots($doctor, $request->date);

        return response()->json($slots);
    }
}
