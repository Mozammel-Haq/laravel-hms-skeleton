<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\PatientVital;
use App\Models\Prescription;
use App\Support\TenantContext;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/**
 * PatientDashboardController
 *
 * Handles API requests for the patient dashboard.
 * Aggregates statistics like upcoming appointments, vitals, and prescriptions.
 */
class PatientDashboardController extends Controller
{
    /**
     * Get dashboard statistics for the patient.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function stats(Request $request)
    {
        $user = $request->user();
        $requestedClinicId = $request->header('X-Clinic-ID');

        // Resolve the correct patient record for the context
        $targetPatient = $user;

        // If a specific clinic is requested, try to find the patient record for that clinic
        // that shares the same email or phone as the authenticated user.
        if ($requestedClinicId && $requestedClinicId != $user->clinic_id) {
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
            }
        }

        // Use the requested clinic ID for scoping, fallback to patient's home clinic
        $scopeClinicId = $requestedClinicId ?: $targetPatient->clinic_id;

        // Set the TenantContext to ensure global scopes work correctly for related models
        TenantContext::setClinicId($scopeClinicId);

        // 1. Upcoming Appointments
        $upcomingAppointments = Appointment::where('patient_id', $targetPatient->id)
            ->where('clinic_id', $scopeClinicId) // Explicitly filter by clinic
            ->where('appointment_date', '>=', now()->toDateString())
            ->whereIn('status', ['confirmed', 'pending', 'arrived']) // Corrected statuses
            ->with(['doctor.user', 'doctor.primaryDepartment', 'requests' => function($q) {
                $q->where('status', 'pending');
            }])
            ->orderBy('appointment_date')
            ->orderBy('start_time') // Corrected from appointment_time to start_time
            ->limit(5)
            ->get()
            ->map(function ($apt) {
                $doctorName = $apt->doctor && $apt->doctor->user ? $apt->doctor->user->name : ($apt->doctor->name ?? 'Unknown Doctor');
                $specialty = $apt->doctor && $apt->doctor->primaryDepartment ? $apt->doctor->primaryDepartment->name : 'General';
                
                $pendingRequest = $apt->requests->first();
                $requestStatus = $pendingRequest ? $pendingRequest->type : null;

                return [
                    'id' => $apt->id,
                    'doctor' => $doctorName,
                    'specialty' => $specialty,
                    'date' => $apt->appointment_date->format('Y-m-d'),
                    'time' => $apt->start_time, // Corrected column
                    'status' => ucfirst($apt->status), // Capitalize for display
                    'pending_request_type' => $requestStatus,
                ];
            });

        // 2. Recent Vitals
        $lastVital = PatientVital::where('patient_id', $targetPatient->id)
            ->orderBy('recorded_at', 'desc')
            ->first();

        $recentVitals = [
            'bp' => $lastVital ? ($lastVital->blood_pressure ?? 'N/A') : 'N/A',
            'heartRate' => ($lastVital && $lastVital->heart_rate) ? $lastVital->heart_rate . ' bpm' : 'N/A',
            'temperature' => ($lastVital && $lastVital->temperature) ? $lastVital->temperature . '°F' : 'N/A',
            'weight' => ($lastVital && $lastVital->weight) ? $lastVital->weight . ' kg' : 'N/A',
            'lastUpdated' => ($lastVital && $lastVital->recorded_at) ? $lastVital->recorded_at->diffForHumans() : 'Never'
        ];

        // 3. Active Prescriptions
        $activePrescriptionsCount = Prescription::whereHas('consultation', function ($q) use ($targetPatient) {
            $q->where('patient_id', $targetPatient->id);
        })
            ->count();

        // 4. Next Visit
        $nextVisit = $upcomingAppointments->first();
        $nextVisitDate = $nextVisit ? Carbon::parse($nextVisit['date'])->format('M d') : 'N/A';

        return response()->json([
            'upcomingAppointments' => $upcomingAppointments,
            'recentVitals' => $recentVitals,
            'prescriptionsCount' => $activePrescriptionsCount,
            'nextVisit' => $nextVisitDate,
        ]);
    }
}
