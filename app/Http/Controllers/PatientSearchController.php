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
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        // If it's a direct browser request to /patients/global-search, show the view
        if (! $request->ajax() && $request->routeIs('patients.search')) {
            return view('patients.search');
        }

        // Otherwise, handle as API search (for Select2 or Global Search AJAX)
        $term = $request->input('term');
        $page = $request->input('page', 1);
        $limit = 20;

        $query = Patient::withoutTenant();

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
            $isLinked = $patient->clinics()->whereKey(auth()->user()->clinic_id)->exists()
                        || $patient->clinic_id == auth()->user()->clinic_id;

            return [
                'id' => $patient->id,
                'text' => $patient->name.' ('.($patient->patient_code ?? 'N/A').')',
                'patient' => array_merge($patient->toArray(), ['is_linked' => $isLinked]),
            ];
        });

        return response()->json([
            'results' => $results,
            'pagination' => [
                'more' => $patients->hasMorePages(),
            ],
        ]);
    }

    /**
     * Check if a patient exists globally by Phone or NID.
     * Used for real-time validation in registration form.
     */
    public function check(Request $request)
    {
        $query = Patient::withoutTenant();

        $phone = $request->input('phone');
        $nid = $request->input('nid');

        if (! $phone && ! $nid) {
            return response()->json(['exists' => false]);
        }

        $patient = $query->where(function ($q) use ($phone, $nid) {
            if ($phone) {
                $q->orWhere('phone', $phone);
            }
            if ($nid) {
                $q->orWhere('nid_number', $nid);
            }
        })->first();

        if ($patient) {
            return response()->json([
                'exists' => true,
                'patient' => [
                    'id' => $patient->id,
                    'name' => $patient->name,
                    'phone' => $patient->phone,
                    'nid' => $patient->nid_number,
                    'code' => $patient->patient_code,
                    'is_current_clinic' => $patient->clinics()->whereKey(auth()->user()->clinic_id)->exists()
                                           || $patient->clinic_id == auth()->user()->clinic_id,
                ],
            ]);
        }

        return response()->json(['exists' => false]);
    }

    /**
     * Automatically link an existing global patient to the current clinic.
     */
    public function link(Request $request, $id)
    {
        // Use withoutTenant() to find the patient across all clinics
        $patient = Patient::withoutTenant()->findOrFail($id);

        $clinicId = auth()->user()->clinic_id;

        // Security check: Ensure patient exists (handled by route model binding)
        // and is not already linked
        $alreadyLinked = $patient->clinics()->whereKey($clinicId)->exists()
                        || $patient->clinic_id == $clinicId;

        if ($alreadyLinked) {
            return redirect()->route('patients.show', $patient)
                ->with('info', 'Patient is already registered in your clinic.');
        }

        // Perform the link
        $patient->clinics()->syncWithoutDetaching([$clinicId]);

        return redirect()->route('patients.show', $patient)
            ->with('success', 'Patient has been successfully linked to your clinic.');
    }
}
