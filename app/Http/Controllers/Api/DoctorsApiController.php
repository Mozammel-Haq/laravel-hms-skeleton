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
        $query = Doctor::with(['department','educations','clinics','schedules','user:id,name']);

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
        return response()->json(compact('doctors','clinics','departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
