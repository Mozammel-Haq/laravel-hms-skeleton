<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientClinicsController extends Controller
{
    /**
     * Get a list of clinics the authenticated patient belongs to.
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
            ->with('clinic')
            ->where(function ($query) use ($user) {
                if ($user->email) {
                    $query->where('email', $user->email);
                }
                if ($user->phone) {
                    $query->orWhere('phone', $user->phone);
                }
            })
            ->get();

        // Extract unique clinics
        $clinics = $patients->pluck('clinic')
            ->filter() // Remove nulls if any
            ->unique('id')
            ->values();

        return response()->json([
            'clinics' => $clinics,
        ]);
    }
}
