<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\PatientAllergy;
use App\Models\PatientMedicalHistory;
use App\Support\TenantContext;
use Illuminate\Http\Request;

class MedicalHistoryApiController extends Controller
{
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

        $conditions = PatientMedicalHistory::where('patient_id', $targetPatient->id)
            ->get()
            ->map(function ($history) {
                return [
                    'id' => $history->id,
                    'name' => $history->condition_name ?? 'Unknown Condition',
                    'diagnosed' => $history->diagnosed_date ? $history->diagnosed_date->format('Y-m-d') : '',
                    'status' => $history->status ?? 'Active',
                    'doctor' => 'Unknown Doctor',
                ];
            });

        $allergies = PatientAllergy::where('patient_id', $targetPatient->id)
            ->get()
            ->map(function ($allergy) {
                return [
                    'id' => $allergy->id,
                    'allergen' => $allergy->allergy_name ?? 'Unknown Allergen', // Corrected column
                    'reaction' => $allergy->notes ?? '', // Mapped notes to reaction as fallback
                    'severity' => $allergy->severity ?? 'Unknown',
                ];
            });

        return response()->json([
            'conditions' => $conditions,
            'allergies' => $allergies,
            'surgeries' => [], // Schema doesn't have surgeries table yet, keep empty
            'immunizations' => [], // Schema doesn't have immunizations table yet, keep empty
        ]);
    }
}
