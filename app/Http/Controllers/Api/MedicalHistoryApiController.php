<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\PatientAllergy;
use App\Models\PatientMedicalHistory;
use App\Models\PatientSurgery;
use App\Models\PatientImmunization;
use App\Support\TenantContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

/**
 * MedicalHistoryApiController
 *
 * Handles API requests for patient medical history.
 * Aggregates conditions, allergies, surgeries, and immunizations.
 */
class MedicalHistoryApiController extends Controller
{
    /**
     * Display the medical history for the authenticated patient.
     * Supports search and filtering by clinic context.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $requestedClinicId = $request->header('X-Clinic-ID');
        $targetPatient = $user;

        if ($requestedClinicId && $requestedClinicId != $user->clinic_id) {
            $foundPatient = Patient::withoutTenant()
                ->where('clinic_id', $requestedClinicId)
                ->where(function ($q) use ($user) {
                    if ($user->email) $q->where('email', $user->email);
                    if ($user->phone) $q->orWhere('phone', $user->phone);
                })
                ->first();

            if ($foundPatient) {
                $targetPatient = $foundPatient;
            }
        }

        $clinicId = $targetPatient->clinic_id;
        TenantContext::setClinicId($clinicId);

        $search = $request->search;

        $conditionsQuery = PatientMedicalHistory::where('patient_id', $targetPatient->id);
        if ($search) {
            $conditionsQuery->where(function ($q) use ($search) {
                $q->where('condition_name', 'like', "%{$search}%")
                    ->orWhere('doctor_name', 'like', "%{$search}%");
            });
        }
        $conditions = $conditionsQuery->get()
            ->map(function ($history) {
                return [
                    'id' => $history->id,
                    'name' => $history->condition_name ?? 'Unknown Condition',
                    'diagnosed' => $history->diagnosed_date ? $history->diagnosed_date->format('Y-m-d') : '',
                    'status' => $history->status ?? 'Active',
                    'doctor' => $history->doctor_name ?? 'Unknown Doctor',
                ];
            });

        $allergiesQuery = PatientAllergy::where('patient_id', $targetPatient->id);
        if ($search) {
            $allergiesQuery->where(function ($q) use ($search) {
                $q->where('allergy_name', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%");
            });
        }
        $allergies = $allergiesQuery->get()
            ->map(function ($allergy) {
                return [
                    'id' => $allergy->id,
                    'allergen' => $allergy->allergy_name ?? 'Unknown Allergen',
                    'reaction' => $allergy->notes ?? '',
                    'severity' => $allergy->severity ?? 'Unknown',
                ];
            });

        $surgeriesQuery = PatientSurgery::where('patient_id', $targetPatient->id);
        if ($search) {
            $surgeriesQuery->where(function ($q) use ($search) {
                $q->where('surgery_name', 'like', "%{$search}%")
                    ->orWhere('hospital_name', 'like', "%{$search}%")
                    ->orWhere('surgeon_name', 'like', "%{$search}%");
            });
        }
        $surgeries = $surgeriesQuery->get()
            ->map(function ($surgery) {
                return [
                    'id' => $surgery->id,
                    'procedure' => $surgery->surgery_name,
                    'hospital' => $surgery->hospital_name ?? 'Unknown Hospital',
                    'date' => $surgery->surgery_date ? $surgery->surgery_date->format('Y-m-d') : '',
                    'surgeon' => $surgery->surgeon_name ?? '',
                ];
            });

        $immunizationsQuery = PatientImmunization::where('patient_id', $targetPatient->id);
        if ($search) {
            $immunizationsQuery->where(function ($q) use ($search) {
                $q->where('vaccine_name', 'like', "%{$search}%")
                    ->orWhere('provider_name', 'like', "%{$search}%");
            });
        }
        $immunizations = $immunizationsQuery->get()
            ->map(function ($imm) {
                return [
                    'id' => $imm->id,
                    'vaccine' => $imm->vaccine_name,
                    'provider' => $imm->provider_name ?? 'Unknown Provider',
                    'date' => $imm->immunization_date ? $imm->immunization_date->format('Y-m-d') : '',
                ];
            });

        return response()->json([
            'conditions' => $conditions,
            'allergies' => $allergies,
            'surgeries' => $surgeries,
            'immunizations' => $immunizations,
            'download_url' => URL::signedRoute('patient.medical-history.download', ['patient' => $targetPatient->id]),
        ]);
    }
}
