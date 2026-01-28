<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Department;
use App\Models\Doctor;
use Illuminate\Http\Request;

class BookingApiController extends Controller
{
    public function index(Request $request)
    {
        $selectedClinicId = $request->header('X-Clinic-ID');
        // return response()->json([
        //     'clinic_id' => $selectedClinicId,
        // ]);
        $clinics = Clinic::withoutGlobalScopes()->with('doctors.department', 'doctors.user:id,name,email','doctors')->where('clinics.id', $selectedClinicId)->get();

        $departments = Department::withoutGlobalScopes()->where('departments.clinic_id', $selectedClinicId)->get();

        return response()->json([
            'clinics' => $clinics,
            'departments' => $departments,
        ]);
    }
}
