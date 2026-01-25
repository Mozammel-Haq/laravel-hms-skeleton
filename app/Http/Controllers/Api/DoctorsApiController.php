<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Department;
use App\Models\Doctor;
use App\Support\TenantContext;
use Illuminate\Http\Request;

class DoctorsApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Doctor::with(['department', 'educations', 'clinics', 'schedules', 'user:id,name']);

        if (TenantContext::hasClinic()) {
            $clinicId = TenantContext::getClinicId();
            $query->withCount(['appointments' => function ($q) use ($clinicId) {
                $q->where('clinic_id', $clinicId);
            }]);
        } else {
            $query->withCount('appointments');
        }

        $doctors = $query->get();
        $clinics = Clinic::all();
        $departments = Department::all();
        return response()->json(compact('doctors', 'clinics', 'departments'));
    }


    /**
     * Display the specified resource.
     */
    public function show(Doctor $doctor)
    {
        $doctor->load(['department', 'educations', 'clinics', 'schedules', 'user:id,name']);
        return response()->json($doctor);
    }
}
