<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Doctor;
use Illuminate\Http\Request;

class BookingApiController extends Controller
{
    public function index(Request $request)
    {
        $selectedClinicId = $request->header('X-Clinic-ID');

        $doctors = Doctor::with('department', 'user:id,name,email')->whereHas('user', function ($query) use ($selectedClinicId) {
            $query->where('clinic_id', $selectedClinicId);
        })->get();

        $departments = Department::where('clinic_id', $selectedClinicId)->get();

        return response()->json([
            'doctors' => $doctors,
            'departments' => $departments,
        ]);
    }
}
