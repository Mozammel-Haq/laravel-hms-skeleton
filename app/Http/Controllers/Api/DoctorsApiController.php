<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Department;
use App\Models\Doctor;
use App\Support\TenantContext;
use Illuminate\Http\Request;

/**
 * DoctorsApiController
 *
 * Handles API requests related to doctors.
 * Allows retrieving a list of doctors and their details.
 */
class DoctorsApiController extends Controller
{
    /**
     * Display a listing of doctors.
     * Supports multi-tenancy context for appointment counts.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $query = Doctor::with(['department', 'educations', 'clinics', 'schedules', 'user:id,name']);

        if (TenantContext::hasClinic()) {
            $clinicId = TenantContext::getClinicId();
            $query->withCount(['appointments' => function ($q) use ($clinicId) {
                $q->where('appointments.clinic_id', $clinicId);
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
     * Display the specified doctor details.
     * Includes department, education, clinics, and schedules.
     *
     * @param  \App\Models\Doctor  $doctor
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Doctor $doctor)
    {
        if ($doctor->status !== 'active') {
            return response()->json(['message' => 'Doctor not found'], 404);
        }
        $doctor->load(['department', 'educations', 'clinics', 'schedules', 'user:id,name']);
        return response()->json($doctor);
    }
}
