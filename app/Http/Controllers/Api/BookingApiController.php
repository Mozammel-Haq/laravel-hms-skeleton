<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Department;
use App\Models\Doctor;
use Illuminate\Http\Request;

/**
 * BookingApiController
 *
 * Handles API requests for fetching booking prerequisites.
 * Returns available clinics and departments for the booking flow.
 */
class BookingApiController extends Controller
{
    /**
     * Display a listing of clinics and departments.
     * Used to populate dropdowns in the booking interface.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $selectedClinicId = $request->header('X-Clinic-ID');
        // return response()->json([
        //     'clinic_id' => $selectedClinicId,
        // ]);
        $clinics = Clinic::withoutGlobalScopes()->with([
            'doctors' => function ($query) {
                $query->where($query->qualifyColumn('status'), 'active');
            },
            'doctors.department',
            'doctors.user:id,name,email'
        ])->where('clinics.id', $selectedClinicId)->get();

        $departments = Department::withoutGlobalScopes()->where('departments.clinic_id', $selectedClinicId)->get();

        return response()->json([
            'clinics' => $clinics,
            'departments' => $departments,
        ]);
    }
}
