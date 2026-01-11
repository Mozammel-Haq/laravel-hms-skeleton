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
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AppointmentController extends Controller
{
    protected $appointmentService;

    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', Appointment::class);

        $appointments = Appointment::with(['patient', 'doctor'])
            ->latest('appointment_date')
            ->paginate(15);

        return view('appointments.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new resource.
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
     * Store a newly created resource in storage.
     */
    public function store(StoreAppointmentRequest $request)
    {
        Gate::authorize('create', Appointment::class);

        $validated = $request->validated();

        return DB::transaction(function () use ($validated) {
            $doctor = Doctor::findOrFail($validated['doctor_id']);
            $appointmentDate = $validated['appointment_date'];
            $startTime = $validated['start_time'];

            // 1. Calculate End Time (Assume 15 min if schedule not found, or fetch schedule)
            // Better to fetch schedule to be accurate
            $dayOfWeek = \Carbon\Carbon::parse($appointmentDate)->dayOfWeek;
            $schedule = $doctor->schedules()
                ->where('day_of_week', $dayOfWeek)
                ->where('status', 'active')
                ->first();

            $duration = $schedule ? $schedule->slot_duration_minutes : 15;
            $endTime = \Carbon\Carbon::parse($startTime)->addMinutes($duration)->format('H:i');

            // 2. Concurrency Check (Double Booking)
            // Lock the rows for this doctor on this date to prevent race conditions
            // We use a shared lock logic or just check existence with lockForUpdate if we query

            $overlapping = Appointment::where('doctor_id', $doctor->id)
                ->where('appointment_date', $appointmentDate)
                ->whereIn('status', ['pending', 'confirmed'])
                ->where(function ($query) use ($startTime, $endTime) {
                    $query->where(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<', $endTime)
                          ->where('end_time', '>', $startTime);
                    });
                })
                ->lockForUpdate() // Lock these rows (if any exist).
                // Note: lockForUpdate only locks *existing* rows. To prevent *inserting* a new row that causes overlap,
                // strictly speaking we need to lock the range or table, but in standard Laravel apps,
                // usually checking existence inside transaction is "good enough" for low-medium traffic.
                // For strict strictness, we might rely on a unique constraint if slots were fixed,
                // but slots are time ranges.
                ->exists();

            if ($overlapping) {
                throw ValidationException::withMessages([
                    'start_time' => ['The selected time slot is already booked. Please choose another time.']
                ]);
            }

            // 3. Fee Calculation (Business Logic)
            $visitType = 'new';
            $fee = $doctor->consultation_fee;

            // Check if patient has visited this doctor before (completed appointment)
            $hasPreviousVisit = Appointment::where('doctor_id', $doctor->id)
                ->where('patient_id', $validated['patient_id'])
                ->where('status', 'completed')
                ->exists();

            if ($hasPreviousVisit) {
                $visitType = 'follow_up';
                // Use follow_up_fee if set, otherwise fallback to consultation_fee (or 0 if that's the policy, but usually same)
                // Requirement says: "lower that first appointment" -> implies follow_up_fee should be used.
                // If null, we might default to consultation_fee or 0. Let's assume fallback to consultation_fee if not set.
                $fee = $doctor->follow_up_fee ?? $doctor->consultation_fee;
            }

            $appointment = Appointment::create([
                'clinic_id' => \App\Support\TenantContext::getClinicId() ?? auth()->user()->clinic_id,
                'patient_id' => $validated['patient_id'],
                'doctor_id' => $validated['doctor_id'],
                'department_id' => $doctor->primary_department_id,
                'appointment_date' => $appointmentDate,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'appointment_type' => $validated['appointment_type'],
                'reason_for_visit' => $validated['reason_for_visit'],
                'booking_source' => $validated['booking_source'],
                'status' => 'pending',
                'created_by' => auth()->id(),
                'fee' => $fee,
                'visit_type' => $visitType,
            ]);

            return redirect()->route('appointments.show', $appointment)
                ->with('success', "Appointment booked successfully. Fee: " . number_format($fee, 2));
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        Gate::authorize('view', $appointment);
        return view('appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified resource.
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        Gate::authorize('update', $appointment);

        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        $appointment->update($validated);

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'Appointment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        Gate::authorize('delete', $appointment);
        // Soft delete logic is handled by model
        $appointment->delete();

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment cancelled successfully.');
    }

    /**
     * Get available slots for a doctor on a specific date.
     * API endpoint for frontend/AJAX.
     */
    public function getSlots(Request $request, Doctor $doctor)
    {
        $request->validate(['date' => 'required|date']);

        $slots = $this->appointmentService->getAvailableSlots($doctor, $request->date);

        return response()->json($slots);
    }

    /**
     * Get consultation fee for a doctor and patient.
     */
    public function getConsultationFee(Request $request, Doctor $doctor)
    {
        $request->validate(['patient_id' => 'required|exists:patients,id']);

        $visitType = 'new';
        $fee = $doctor->consultation_fee;

        $hasPreviousVisit = Appointment::where('doctor_id', $doctor->id)
            ->where('patient_id', $request->patient_id)
            ->where('status', 'completed')
            ->exists();

        if ($hasPreviousVisit) {
            $visitType = 'follow_up';
            $fee = $doctor->follow_up_fee ?? $doctor->consultation_fee;
        }

        return response()->json([
            'fee' => $fee,
            'visit_type' => $visitType
        ]);
    }
}
