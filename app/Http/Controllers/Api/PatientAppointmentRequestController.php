<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AppointmentRequest;
use App\Models\Patient;
use App\Models\User;
use App\Notifications\AppointmentRequestSubmittedNotification;
use App\Support\TenantContext;
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        [$targetPatient, $scopeClinicId] = $this->resolvePatientAndClinic($request);

        $requests = AppointmentRequest::withoutGlobalScopes()
            ->where('clinic_id', $scopeClinicId)
            ->whereHas('appointment', function ($q) use ($targetPatient, $scopeClinicId) {
                $q->withoutGlobalScopes()
                    ->where('clinic_id', $scopeClinicId)
                    ->where('patient_id', $targetPatient->id);
            })
            ->with(['appointment', 'appointment.doctor', 'appointment.clinic'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'requests' => $requests,
        ]);
    }

    /**
     * Store a new appointment request.
     * Validates eligibility (must be pending appointment) and prevents duplicates.
     * Notifies clinic admins of the new request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|integer',
            'type' => 'required|in:cancel,reschedule',
            'reason' => 'required|string|max:1000',
            'desired_date' => 'required_if:type,reschedule|nullable|date|after_or_equal:today',
            'desired_time' => 'required_if:type,reschedule|nullable|date_format:H:i',
        ]);

        [$targetPatient, $scopeClinicId] = $this->resolvePatientAndClinic($request);

        $appointment = Appointment::withoutGlobalScopes()
            ->whereKey($request->appointment_id)
            ->where('clinic_id', $scopeClinicId)
            ->where('patient_id', $targetPatient->id)
            ->first();

        if (! $appointment) {
            return response()->json(['message' => 'Appointment not found.'], 404);
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
            'request' => $appointmentRequest,
        ], 201);
    }

    private function resolvePatientAndClinic(Request $request): array
    {
        $user = $request->user();
        $requestedClinicId = $request->header('X-Clinic-ID');

        $targetPatient = $user;
        $scopeClinicId = $requestedClinicId ?: $user->clinic_id;

        if ($requestedClinicId && (int) $requestedClinicId !== (int) $user->clinic_id) {
            $foundPatient = Patient::withoutTenant()
                ->where(function ($query) use ($requestedClinicId) {
                    $query->where('clinic_id', $requestedClinicId)
                        ->orWhereHas('clinics', function ($q) use ($requestedClinicId) {
                            $q->where('clinics.id', $requestedClinicId);
                        });
                })
                ->where(function ($q) use ($user) {
                    if ($user->email) {
                        $q->where('email', $user->email);
                    }
                    if ($user->phone) {
                        $q->orWhere('phone', $user->phone);
                    }
                })
                ->first();

            if ($foundPatient) {
                $targetPatient = $foundPatient;
                $scopeClinicId = $requestedClinicId;
            }
        }

        TenantContext::setClinicId($scopeClinicId);

        return [$targetPatient, $scopeClinicId];
    }
}
