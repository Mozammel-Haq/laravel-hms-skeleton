<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Services\AppointmentService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * AppointmentsApiController
 *
 * Handles API requests related to appointments.
 * Allows patients to view their appointments, book new ones, and check doctor availability.
 */
class AppointmentsApiController extends Controller
{
    public function __construct(private readonly AppointmentService $appointmentService) {}

    /**
     * Display a listing of the patient's appointments.
     * Supports filtering by status (upcoming, past, etc.) and search.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $selectedClinicId = $request->header('X-Clinic-ID');
        $userEmail = $request->user()->email;

        // 1. Find Patient ID
        // We use withoutGlobalScopes() to disable BaseTenantModel's automatic filtering
        $patient = Patient::withoutGlobalScopes()
            ->where('email', $userEmail)
            ->where(function ($query) use ($selectedClinicId) {
                $query->where('clinic_id', $selectedClinicId)
                    ->orWhereHas('clinics', function ($q) use ($selectedClinicId) {
                        $q->where('clinics.id', $selectedClinicId);
                    });
            })
            ->first();
        if (! $patient) {
            return response()->json(['appointments' => []]);
        }

        // 2. Fetch Appointments
        $query = Appointment::withoutGlobalScopes()
            ->with(['doctor', 'doctor.user:id,name,email', 'visit.invoices', 'requests' => function ($q) {
                $q->where('status', 'pending');
            }])
            ->where('appointments.clinic_id', $selectedClinicId)
            ->where('appointments.patient_id', $patient->id);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('reason_for_visit', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhereHas('doctor.user', function ($sub) use ($search) {
                        $sub->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $today = Carbon::today()->toDateString();
            $nowTime = now()->format('H:i:s');
            $activeStatuses = ['confirmed', 'pending', 'arrived', 'scheduled', 'checked in', 'in_progress'];
            $finalStatuses = ['completed', 'cancelled', 'noshow', 'checked out'];

            if ($request->status === 'upcoming') {
                $query->whereIn('status', $activeStatuses)
                    ->where(function ($q) use ($today) {
                        $q->whereDate('appointment_date', '>', $today)
                            ->orWhereDate('appointment_date', $today);
                    });
            } elseif ($request->status === 'past') {
                $query->withTrashed();
                $query->where(function ($q) use ($today, $finalStatuses) {
                    $q->whereIn('status', $finalStatuses)
                        ->orWhere(function ($sub) use ($today) {
                            $sub->whereDate('appointment_date', '<', $today);
                        });
                });
            } else {
                $query->where('status', $request->status);
            }
        }

        $appointments = $query->orderBy('appointment_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get();

        return response()->json([
            'appointments' => $appointments,
        ]);
    }

    /**
     * Display the specified appointment details.
     * Includes doctor, visit, prescription, and vitals information.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        $appointment = Appointment::withoutGlobalScopes()
            ->with([
                'doctor.user:id,name',
                'doctor.department',
                'visit.consultation.prescriptions.items.medicine',
                'visit.vitals',
            ])
            ->find($id);

        if (! $appointment) {
            return response()->json(['message' => 'Appointment not found'], 404);
        }

        // Check ownership
        if ($appointment->patient_id != $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'appointment' => $appointment,
        ]);
    }

    /**
     * Get available time slots for a doctor on a specific date.
     * Calculates slots based on doctor's schedule and existing bookings.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function slots(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|integer',
            'date' => 'required|date',
            'clinic_id' => 'nullable|integer',
        ]);

        $doctorId = (int) $request->doctor_id;
        $date = (string) $request->date;
        $clinicId = $request->clinic_id
            ? (int) $request->clinic_id
            : ($request->header('X-Clinic-ID') ? (int) $request->header('X-Clinic-ID') : null);

        $doctor = Doctor::findOrFail($doctorId);
        $slots = $this->appointmentService->getAvailableSlots($doctor, $date, $clinicId);

        $availableSlots = array_values(array_filter(array_map(function ($slot) {
            $start = $slot['start_time'] ?? null;
            $end = $slot['end_time'] ?? null;
            $isBooked = (bool) ($slot['is_booked'] ?? false);

            if (! $start || ! $end || $isBooked) {
                return null;
            }

            return [
                'label' => Carbon::createFromFormat('H:i', $start)->format('h:i A'),
                'start' => $start.':00',
                'end' => $end.':00',
            ];
        }, $slots)));

        return response()->json([
            'slots' => $availableSlots,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return response()->json($request->all());
        $request->validate([
            'doctor_id' => 'required|integer',
            'patient_id' => 'required|integer',
            'department_id' => 'required|integer',
            'appointment_date' => 'required|date',
            'start_time' => 'required|date_format:H:i:s',
            'end_time' => 'required|date_format:H:i:s',
            'clinic_id' => 'nullable|integer',
            'status' => 'nullable|string|in:pending,arrived,confirmed,cancelled',
            'booking_source' => 'nullable|string|in:in_person,online',
            'appointment_type' => 'nullable|string',
            'reason_for_visit' => 'nullable|string',
        ]);

        $patientId = $request->user()->id;
        $appointmentDate = $request->appointment_date;
        $clinicId = $request->clinic_id
            ? (int) $request->clinic_id
            : ($request->header('X-Clinic-ID') ? (int) $request->header('X-Clinic-ID') : null);

            $bookingDate = Carbon::parse((string) $appointmentDate)->toDateString();
        $activeStatuses = ['confirmed', 'arrived', 'in_progress'];

        $doctor = Doctor::findOrFail((int) $request->doctor_id);
        $startHm = substr((string) $request->start_time, 0, 5);
        $computedEndTime = null;

        $slots = $this->appointmentService->getAvailableSlots($doctor, (string) $request->appointment_date, $clinicId);
        foreach ($slots as $slot) {
            if (($slot['start_time'] ?? null) === $startHm) {
                $end = $slot['end_time'] ?? null;
                if ($end && preg_match('/^\d{2}:\d{2}$/', $end) === 1) {
                    $computedEndTime = $end.':00';
                }
                break;
            }
        }

        $today = Carbon::today()->toDateString();

        $conflictBase = Appointment::where('patient_id', $patientId)
            ->whereIn('status', $activeStatuses)
            ->whereDate('appointment_date', $bookingDate);

        $scopeClinicId = $clinicId ?: ($request->user()->clinic_id ?? null);
        if ($scopeClinicId) {
            $conflictBase->where('clinic_id', $scopeClinicId);
        }

        if ($bookingDate === $today) {
            $stillActive = (clone $conflictBase)
                ->whereRaw('TIMESTAMP(appointment_date, COALESCE(end_time, start_time)) > NOW()')
                ->exists();
            if ($stillActive) {
                return response()->json(['message' => 'You already have an active appointment on this date.'], 422);
            }
        } else {
            if ($conflictBase->exists()) {
                return response()->json(['message' => 'You already have an active appointment on this date.'], 422);
            }
        }

        $appointment = new Appointment;
        $appointment->doctor_id = $request->doctor_id;
        $appointment->department_id = $request->department_id;
        $appointment->patient_id = $request->user()->id;
        $appointment->appointment_date = $request->appointment_date;
        $appointment->start_time = $request->start_time;
        $appointment->end_time = $computedEndTime ?? $request->end_time;
        $appointment->clinic_id = $clinicId;
        $appointment->status = $request->status ?? 'pending';
        $appointment->booking_source = $request->booking_source ?? 'in_person';

        // Fix: Frontend sends 'new'/'follow_up' as appointment_type, but DB expects 'online'/'in_person'
        // We'll default to 'in_person' and append the visit type to the reason
        $visitType = $request->appointment_type; // 'new' or 'follow_up'
        $reason = $request->reason_for_visit;

        if ($visitType && ! in_array($visitType, ['online', 'in_person'])) {
            $appointment->appointment_type = 'in_person';
            $formattedType = ucfirst(str_replace('_', ' ', $visitType));
            $appointment->reason_for_visit = "[$formattedType] ".$reason;
        } else {
            $appointment->appointment_type = $visitType ?? 'in_person';
            $appointment->reason_for_visit = $reason;
        }

        $appointment->save();

        return response()->json([
            'message' => 'Appointment booked successfully',
            'appointment' => $appointment,
            'patient_id' => $request->patient_id,
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
