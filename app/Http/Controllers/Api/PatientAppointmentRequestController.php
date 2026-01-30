<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AppointmentRequest;
use App\Models\User;
use App\Notifications\AppointmentRequestSubmittedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

/**
 * PatientAppointmentRequestController
 *
 * Handles API requests for patient appointment modifications.
 * Allows patients to request cancellation or rescheduling of appointments.
 */
class PatientAppointmentRequestController extends Controller
{
    /**
     * Display a listing of appointment requests.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $requests = AppointmentRequest::whereHas('appointment', function ($q) use ($user) {
            $q->where('patient_id', $user->id);
        })
            ->with(['appointment', 'appointment.doctor', 'appointment.clinic'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'requests' => $requests
        ]);
    }

    /**
     * Store a new appointment request.
     * Validates eligibility (must be pending appointment) and prevents duplicates.
     * Notifies clinic admins of the new request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'type' => 'required|in:cancel,reschedule',
            'reason' => 'required|string|max:1000',
            'desired_date' => 'required_if:type,reschedule|nullable|date|after:today',
            'desired_time' => 'required_if:type,reschedule|nullable|date_format:H:i',
        ]);

        $user = $request->user();
        $appointment = Appointment::findOrFail($request->appointment_id);

        if ($appointment->patient_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($appointment->status !== 'pending') {
            return response()->json(['message' => 'Only pending appointments can be rescheduled or cancelled'], 422);
        }

        // Check for existing pending requests
        $existingRequest = AppointmentRequest::where('appointment_id', $appointment->id)
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            return response()->json(['message' => 'A pending request already exists for this appointment.'], 409);
        }

        $appointmentRequest = AppointmentRequest::create([
            'appointment_id' => $appointment->id,
            'clinic_id' => $appointment->clinic_id,
            'type' => $request->type,
            'reason' => $request->reason,
            'desired_date' => $request->desired_date,
            'desired_time' => $request->desired_time,
            'status' => 'pending',
        ]);

        // Notify Clinic Admins
        // Assuming Clinic Admin role exists or we notify all admins of the clinic
        $admins = User::whereHas('roles', function ($q) {
            $q->where('name', 'Clinic Admin')
                ->orWhere('name', 'Super Admin');
        })
            ->where(function ($q) use ($appointment) {
                // If multi-tenant, filter by clinic or global admins
                $q->where('clinic_id', $appointment->clinic_id)
                    ->orWhereNull('clinic_id');
            })
            ->get();

        Notification::send($admins, new AppointmentRequestSubmittedNotification($appointmentRequest));

        return response()->json([
            'message' => 'Request submitted successfully',
            'request' => $appointmentRequest
        ], 201);
    }
}
