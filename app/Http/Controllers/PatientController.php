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

        $data = $request->validated();
        $clinicId = auth()->user()->clinic_id;

        // 1. Check for existing patient globally
        $query = Patient::withoutTenant();

        $matchFound = false;
        $conditions = [];
        if (!empty($data['nid_number'])) $conditions[] = ['nid_number', $data['nid_number']];
        if (!empty($data['birth_certificate_number'])) $conditions[] = ['birth_certificate_number', $data['birth_certificate_number']];
        if (!empty($data['passport_number'])) $conditions[] = ['passport_number', $data['passport_number']];
        if (!empty($data['email'])) $conditions[] = ['email', $data['email']];
        if (!empty($data['phone'])) $conditions[] = ['phone', $data['phone']];

        $existingPatient = null;
        if (count($conditions) > 0) {
             $existingPatient = $query->where(function($q) use ($conditions) {
                foreach ($conditions as $cond) {
                    $q->orWhere($cond[0], $cond[1]);
                }
             })->first();
        }

        if ($existingPatient) {
            // Check if already in THIS clinic
            $alreadyLinked = $existingPatient->clinics()->whereKey($clinicId)->exists();

            // Legacy check
            if (!$alreadyLinked && $existingPatient->clinic_id == $clinicId) {
                $alreadyLinked = true;
            }

            if ($alreadyLinked) {
                return redirect()->route('patients.show', $existingPatient)
                    ->with('info', 'Patient is already registered in this clinic.');
            }

            // Link to clinic
            $existingPatient->clinics()->syncWithoutDetaching([$clinicId]);

            return redirect()->route('patients.show', $existingPatient)
                ->with('success', 'Existing patient record found and successfully registered to your clinic.');
        }

        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');

            if ($file->isValid()) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $destination = public_path('assets/img/patients');

                if (!is_dir($destination)) {
                    mkdir($destination, 0755, true);
                }

                try {
                    $file->move($destination, $filename);
                    $data['profile_photo'] = 'assets/img/patients/' . $filename;
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('File upload failed: ' . $e->getMessage());
                    // Continue without the file or return error
                }
            }
        }

        $defaultPassword = env('PATIENT_DEFAULT_PASSWORD', 'PT1234');
        $data['password'] = Hash::make($defaultPassword);
        $data['must_change_password'] = true;

        // Remove clinic_id from data to rely on pivot
        if (isset($data['clinic_id'])) unset($data['clinic_id']);

        $patient = Patient::create($data + [
            'clinic_id'    => null, // Global patient
            'patient_code' => 'TEMP-' . uniqid(),
        ]);

        // Update with correct code format based on ID
        $patient->update([
            'patient_code' => 'P-' . str_pad($patient->id, 4, '0', STR_PAD_LEFT)
        ]);

        // Attach to clinic
        $patient->clinics()->attach($clinicId);

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

            if ($file->isValid()) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $destination = public_path('assets/img/patients');

                if (!is_dir($destination)) {
                    mkdir($destination, 0755, true);
                }

                try {
                    // Delete old photo if exists
                    if ($patient->profile_photo && file_exists(public_path($patient->profile_photo))) {
                        unlink(public_path($patient->profile_photo));
                    }

                    $file->move($destination, $filename);
                    $data['profile_photo'] = 'assets/img/patients/' . $filename;
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('File upload failed: ' . $e->getMessage());
                }
            }
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
