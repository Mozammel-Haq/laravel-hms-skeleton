<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', Patient::class);

        $query = Patient::query();

        if (request('status') === 'trashed') {
            $query->onlyTrashed();
        }

        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('patient_code', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        if (request()->filled('from')) {
            $query->whereDate('created_at', '>=', request('from'));
        }

        if (request()->filled('to')) {
            $query->whereDate('created_at', '<=', request('to'));
        }

        $patients = $query->latest()->paginate(10);
        return view('patients.index', compact('patients'));
    }

    public function restore($id)
    {
        $patient = Patient::withTrashed()->findOrFail($id);
        Gate::authorize('delete', $patient);

        $patient->restore();
        $patient->update(['status' => 'active']);

        return redirect()->route('patients.index')->with('success', 'Patient restored successfully.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Patient::class);
        return view('patients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePatientRequest $request)
    {
        Gate::authorize('create', Patient::class);

        $patient = Patient::create($request->validated() + [
            'clinic_id'    => auth()->user()->clinic_id,
            'patient_code' => 'TEST',
        ]);

        return redirect()
            ->route('patients.show', $patient)
            ->with('success', 'Patient registered successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        Gate::authorize('view', $patient);

        $patient->load([
            'appointments.doctor.user',
            'appointments.doctor.department',
            'admissions.doctor.user',
            'vitals',
            'medicalHistory',
            'invoices.payments',
        ]);

        return view('patients.show', compact('patient'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient)
    {
        Gate::authorize('update', $patient);
        return view('patients.edit', compact('patient'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePatientRequest $request, Patient $patient)
    {
        Gate::authorize('update', $patient);

        $patient->update($request->validated());

        return redirect()->route('patients.show', $patient)
            ->with('success', 'Patient updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        Gate::authorize('delete', $patient);

        $patient->update(['status' => 'inactive']); // Ensure status is inactive
        $patient->delete();

        return redirect()->route('patients.index')
            ->with('success', 'Patient deleted successfully.');
    }
}
