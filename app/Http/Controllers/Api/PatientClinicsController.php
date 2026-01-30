<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

/**
 * PatientClinicsController
 *
 * Handles API requests for retrieving patient's associated clinics.
 */
class PatientClinicsController extends Controller
{
    /**
     * Get a list of clinics the authenticated patient belongs to.
     * Aggregates clinics from legacy relationships and new pivot tables.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Find all patient records with the same email or phone across all clinics
        $patients = Patient::withoutTenant()
            ->with(['clinic', 'clinics'])
            ->where(function ($query) use ($user) {
                if ($user->email) {
                    $query->where('email', $user->email);
                }
                if ($user->phone) {
                    $query->orWhere('phone', $user->phone);
                }
            })
            ->get();

        // Extract unique clinics from both legacy column and new pivot table
        $clinics = $patients->flatMap(function ($patient) {
            $list = $patient->clinics; // Collection from pivot table
            if ($patient->clinic) {
                $list->push($patient->clinic); // Add legacy clinic
            }
            return $list;
        })
        ->unique('id')
        ->values();

        return response()->json([
            'clinics' => $clinics,
        ]);
    }
}
