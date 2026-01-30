<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Services\AppointmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Support\TenantContext;

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
     * @param  \App\Services\AppointmentService  $appointmentService
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
            $query->onlyTrashed()->latest();
        } else {
            if (request()->filled('status')) {
                $query->where('status', request('status'));
            }
            $query->latest();
        }

        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('appointment_date', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%')
                    ->orWhere('appointment_type', 'like', '%' . $search . '%')
                    ->orWhereHas('patient', function ($sub) use ($search) {
                        $sub->where('name', 'like', '%' . $search . '%')
                            ->orWhere('patient_code', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('doctor.user', function ($sub) use ($search) {
                        $sub->where('name', 'like', '%' . $search . '%');
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

        $appointments = $query
            ->paginate(5)
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
     * @param  \App\Http\Requests\StoreAppointmentRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreAppointmentRequest $request)
    {
        Gate::authorize('create', Appointment::class);

        $doctor = Doctor::findOrFail($request->doctor_id);

        // Create the appointment
        // Note: Slot availability should ideally be verified here before creation.
        $appointment = Appointment::create($request->validated() + [
            'clinic_id' => auth()->user()->clinic_id,
            'status' => 'pending',
            'end_time' => \Carbon\Carbon::parse($request->start_time)->addMinutes(15)->format('H:i'),
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
     * @param  \App\Models\Appointment  $appointment
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
     * @param  \App\Models\Appointment  $appointment
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Appointment  $appointment
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
                        $fail('The ' . $attribute . ' must be a date after or equal to today.');
                    }
                },
            ],
            'start_time' => 'required',
            'status' => 'required|in:pending,confirmed,cancelled,completed,arrived,noshow',
        ]);

        // If doctor changed, we might want to re-validate availability or just trust the admin/staff
        // We'll update the end_time if start_time changed, assuming 15 min default or keeping duration
        // For now, let's just update end_time based on start_time + 15 mins to be safe
        $validated['end_time'] = \Carbon\Carbon::parse($request->start_time)->addMinutes(15)->format('H:i');

        $oldStatus = $appointment->status;
        $appointment->update($validated);

        // Notify Patient if status changed
        if ($oldStatus !== $appointment->status && $appointment->patient) {
            $appointment->patient->notify(new \App\Notifications\AppointmentStatusNotification($appointment));
        }

        return redirect()->route('appointments.index')->with('success', 'Appointment updated successfully.');
    }

    /**
     * Remove the specified appointment from storage (Soft Delete).
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Appointment $appointment)
    {
        Gate::authorize('delete', $appointment);
        $appointment->delete();
        return redirect()->route('appointments.index')->with('success', 'Appointment cancelled.');
    }

    /**
     * Update the status of an appointment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Appointment  $appointment
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

        $appointment->update(['status' => $request->status]);

        if ($appointment->patient) {
            $appointment->patient->notify(new \App\Notifications\AppointmentStatusNotification($appointment));
        }

        return back()->with('success', 'Appointment status updated to ' . ucfirst($request->status));
    }

    /**
     * Get available slots for a doctor on a specific date.
     *
     * API endpoint for frontend/AJAX.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Doctor  $doctor
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSlots(Request $request, Doctor $doctor)
    {
        $request->validate(['date' => 'required|date|after_or_equal:today']);

        $slots = $this->appointmentService->getAvailableSlots($doctor, $request->date);

        return response()->json($slots);
    }
}
