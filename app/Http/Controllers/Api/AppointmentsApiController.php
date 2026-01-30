<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
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
    /**
     * Display a listing of the patient's appointments.
     * Supports filtering by status (upcoming, past, etc.) and search.
     *
     * @param  \Illuminate\Http\Request  $request
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
        if (!$patient) {
            return response()->json(['appointments' => []]);
        }

        // 2. Fetch Appointments
        $query = Appointment::withoutGlobalScopes()
            ->with(['doctor', 'doctor.user:id,name,email', 'requests' => function ($q) {
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
            if ($request->status === 'upcoming') {
                $query->whereIn('status', ['confirmed', 'pending', 'arrived', 'scheduled', 'checked in']);
            } elseif ($request->status === 'past') {
                $query->whereIn('status', ['completed', 'cancelled', 'noshow', 'checked out']);
            } else {
                $query->where('status', $request->status);
            }
        }

        $appointments = $query->orderBy('appointment_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get();

        return response()->json([
            'appointments' => $appointments
        ]);
    }

    /**
     * Display the specified appointment details.
     * Includes doctor, visit, prescription, and vitals information.
     *
     * @param  \Illuminate\Http\Request  $request
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
                'visit.vitals'
            ])
            ->find($id);

        if (!$appointment) {
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function slots(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|integer',
            'date' => 'required|date',
            'clinic_id' => 'nullable|integer'
        ]);

        $doctorId = $request->doctor_id;
        $date = $request->date;
        $clinicId = $request->clinic_id;

        // 1. Day of week (1 = Monday, 7 = Sunday)
        $dayOfWeek = Carbon::parse($date)->dayOfWeekIso;

        // 2. Doctor schedule
        $schedule = DB::table('doctor_schedules')
            ->where('doctor_id', $doctorId)
            ->where('day_of_week', $dayOfWeek)
            ->where('status', 'active')
            ->when($clinicId, fn($q) => $q->where('doctor_schedules.clinic_id', $clinicId))
            ->first();

        if (!$schedule) {
            return response()->json([
                'slots' => [],
                'message' => 'Doctor is not available on this day.'
            ]);
        }

        // 3. Generate all possible slots
        $scheduleStart = Carbon::parse($schedule->start_time);
        $scheduleEnd   = Carbon::parse($schedule->end_time);
        $duration      = (int) $schedule->slot_duration_minutes;

        $allSlots = [];

        $cursor = $scheduleStart->copy();

        while ($cursor->copy()->addMinutes($duration)->lte($scheduleEnd)) {
            $allSlots[] = [
                'start' => $cursor->format('H:i:s'),
                'end'   => $cursor->copy()->addMinutes($duration)->format('H:i:s'),
            ];
            $cursor->addMinutes($duration);
        }

        // 4. Fetch booked appointments (time ranges)
        $bookedAppointments = Appointment::where('doctor_id', $doctorId)
            ->where('appointment_date', $date)
            ->where('status', '!=', 'cancelled')
            ->get(['start_time', 'end_time']);

        // 5. Filter available slots using overlap logic
        $availableSlots = [];

        foreach ($allSlots as $slot) {
            $slotStart = Carbon::parse($slot['start']);
            $slotEnd   = Carbon::parse($slot['end']);

            $isOverlapping = false;

            foreach ($bookedAppointments as $booking) {
                if (
                    $slotStart < Carbon::parse($booking->end_time) &&
                    $slotEnd > Carbon::parse($booking->start_time)
                ) {
                    $isOverlapping = true;
                    break;
                }
            }

            if (!$isOverlapping) {
                $availableSlots[] = [
                    'label' => Carbon::parse($slot['start'])->format('h:i A'),
                    'start' => $slot['start'],
                    'end'   => $slot['end'],
                ];
            }
        }

        return response()->json([
            'slots' => $availableSlots
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
        $appointment = new Appointment();
        $appointment->doctor_id = $request->doctor_id;
        $appointment->department_id = $request->department_id;
        $appointment->patient_id = $request->user()->id;
        $appointment->appointment_date = $request->appointment_date;
        $appointment->start_time = $request->start_time;
        $appointment->end_time = $request->end_time;
        $appointment->clinic_id = $request->clinic_id;
        $appointment->status = $request->status ?? 'pending';
        $appointment->booking_source = $request->booking_source ?? 'in_person';

        // Fix: Frontend sends 'new'/'follow_up' as appointment_type, but DB expects 'online'/'in_person'
        // We'll default to 'in_person' and append the visit type to the reason
        $visitType = $request->appointment_type; // 'new' or 'follow_up'
        $reason = $request->reason_for_visit;

        if ($visitType && !in_array($visitType, ['online', 'in_person'])) {
            $appointment->appointment_type = 'in_person';
            $formattedType = ucfirst(str_replace('_', ' ', $visitType));
            $appointment->reason_for_visit = "[$formattedType] " . $reason;
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
