<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\PatientMedicalHistory;
use App\Models\PatientSurgery;
use App\Models\PatientImmunization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PatientMedicalHistoryController extends Controller
{
    // --- Conditions ---
    public function storeCondition(Request $request, Patient $patient)
    {
        Gate::authorize('update', $patient);

        $request->validate([
            'condition_name' => 'required|string|max:255',
            'diagnosed_date' => 'nullable|date',
            'status' => 'nullable|string|max:50',
            'doctor_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $data = $request->all();

        // Auto-fill doctor name if not provided and user is a doctor
        if (empty($data['doctor_name']) && auth()->check() && auth()->user()->hasRole('Doctor')) {
            $data['doctor_name'] = auth()->user()->name;
        }

        $patient->medicalHistory()->create($data);

        return redirect()->back()->with('success', 'Medical condition added successfully.');
    }

    public function destroyCondition(PatientMedicalHistory $history)
    {
        $patient = $history->patient;
        Gate::authorize('update', $patient);

        $history->delete();

        return redirect()->back()->with('success', 'Medical condition removed successfully.');
    }

    // --- Allergies ---
    public function storeAllergy(Request $request, Patient $patient)
    {
        Gate::authorize('update', $patient);

        $request->validate([
            'allergy_name' => 'required|string|max:255',
            'severity' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $patient->allergies()->create($request->all());

        return redirect()->back()->with('success', 'Allergy record added successfully.');
    }

    public function destroyAllergy(PatientAllergy $allergy)
    {
        $patient = $allergy->patient;
        Gate::authorize('update', $patient);

        $allergy->delete();

        return redirect()->back()->with('success', 'Allergy record removed successfully.');
    }

    // --- Surgeries ---
    public function storeSurgery(Request $request, Patient $patient)
    {
        Gate::authorize('update', $patient);

        $request->validate([
            'surgery_name' => 'required|string|max:255',
            'surgery_date' => 'nullable|date',
            'hospital_name' => 'nullable|string|max:255',
            'surgeon_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $patient->surgeries()->create($request->all());

        return redirect()->back()->with('success', 'Surgery record added successfully.');
    }

    public function destroySurgery(PatientSurgery $surgery)
    {
        $patient = $surgery->patient;
        Gate::authorize('update', $patient);

        $surgery->delete();

        return redirect()->back()->with('success', 'Surgery record removed successfully.');
    }

    // --- Immunizations ---
    public function storeImmunization(Request $request, Patient $patient)
    {
        Gate::authorize('update', $patient);

        $request->validate([
            'vaccine_name' => 'required|string|max:255',
            'immunization_date' => 'nullable|date',
            'provider_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $patient->immunizations()->create($request->all());

        return redirect()->back()->with('success', 'Immunization record added successfully.');
    }

    public function destroyImmunization(PatientImmunization $immunization)
    {
        $patient = $immunization->patient;
        Gate::authorize('update', $patient);

        $immunization->delete();

        return redirect()->back()->with('success', 'Immunization record removed successfully.');
    }

    public function patientDownload(Request $request, Patient $patient)
    {
        if (! $request->hasValidSignature()) {
            abort(403, 'Invalid or expired link.');
        }

        $patient->load(['medicalHistory', 'surgeries', 'immunizations', 'allergies']);

        return view('patients.history.print', compact('patient'));
    }
}
