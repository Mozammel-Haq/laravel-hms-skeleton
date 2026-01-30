<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

/**
 * Handles AJAX requests for searching patients.
 *
 * Responsibilities:
 * - Patient search for Select2 dropdowns
 * - Filtering patients by eligibility (e.g., for lab tests)
 */
class PatientSearchController extends Controller
{
    /**
     * Search patients for Select2 via AJAX.
     *
     * Supports filtering by:
     * - Term: Name, Patient Code, Phone, Email
     * - Type: 'lab_eligible' (checks appointments or admissions)
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $term = $request->input('term');
        $page = $request->input('page', 1);
        $limit = 20;

        $query = Patient::query();

        // Filter for Lab Eligibility
        if ($request->input('type') === 'lab_eligible') {
            $query->where(function ($q) {
                $q->whereHas('appointments', function ($sub) {
                    $sub->where('status', 'completed');
                })->orWhereHas('admissions', function ($sub) {
                    $sub->where('status', 'admitted');
                });
            });
        }

        if ($term) {
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhere('patient_code', 'like', "%{$term}%")
                    ->orWhere('phone', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%");
            });
        }

        $patients = $query->orderBy('name')
            ->paginate($limit, ['*'], 'page', $page);

        $results = $patients->map(function ($patient) {
            return [
                'id' => $patient->id,
                'text' => $patient->name . ' (' . ($patient->patient_code ?? 'N/A') . ')',
                'patient' => $patient // Optional: pass full object if needed by frontend
            ];
        });

        return response()->json([
            'results' => $results,
            'pagination' => [
                'more' => $patients->hasMorePages()
            ]
        ]);
    }
}
