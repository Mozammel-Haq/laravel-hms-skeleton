<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Http\Request;

class ApoointmentsApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // return response()->json($request->all());
        $patient = Patient::find($request->user()->id);
        // return response()->json($patient);
        $clinics = $patient->clinic_id;
        // return response()->json($clinics);
        $appointments = Appointment::with('doctor', 'doctor.user:id,name,email')->where('clinic_id', $clinics)
        ->where('patient_id', $patient->id)
        ->get();
        return response()->json($appointments);
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
