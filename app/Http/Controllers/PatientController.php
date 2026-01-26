<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\PatientWelcomeMail;

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
        } elseif (request()->filled('status')) {
            $query->where('status', request('status'));
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

        $patients = $query->latest()->paginate(10)->withQueryString();
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

        \Illuminate\Support\Facades\Log::info('Patient Store Request Data:', $request->all());
        \Illuminate\Support\Facades\Log::info('Has File profile_photo:', ['has' => $request->hasFile('profile_photo')]);
        if ($request->hasFile('profile_photo')) {
            \Illuminate\Support\Facades\Log::info('File details:', [
                'name' => $request->file('profile_photo')->getClientOriginalName(),
                'valid' => $request->file('profile_photo')->isValid(),
            ]);
        }

        $data = $request->validated();

        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destination = public_path('assets/img/patients');

            if (!is_dir($destination)) {
                mkdir($destination, 0755, true);
            }

            $file->move($destination, $filename);
            $data['profile_photo'] = 'assets/img/patients/' . $filename;
        }

        $defaultPassword = env('PATIENT_DEFAULT_PASSWORD', 'Default123!');
        $data['password'] = Hash::make($defaultPassword);
        $data['must_change_password'] = true;

        $patient = Patient::create($data + [
            'clinic_id'    => auth()->user()->clinic_id,
            'patient_code' => 'TEST',
        ]);

        if (!empty($patient->email)) {
            Mail::to($patient->email)->send(new PatientWelcomeMail($patient, $defaultPassword));
        }

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

        $data = $request->validated();

        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destination = public_path('assets/img/patients');

            if (!is_dir($destination)) {
                mkdir($destination, 0755, true);
            }

            $file->move($destination, $filename);
            $data['profile_photo'] = 'assets/img/patients/' . $filename;
        }

        $patient->update($data);

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
