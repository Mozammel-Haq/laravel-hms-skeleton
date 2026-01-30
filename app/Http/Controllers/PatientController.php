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

/**
 * PatientController
 *
 * Manages patient records, including registration, profile management,
 * and clinic association. Supports both IPD and OPD patient workflows.
 */
class PatientController extends Controller
{
    /**
     * Display a listing of the patients.
     *
     * Supports filtering by:
     * - Type (ipd/opd): Based on active admissions
     * - Status (active/trashed): Soft delete support
     * - Search: Name, code, phone, or email
     * - Date Range: Created at timestamps
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        Gate::authorize('viewAny', Patient::class);

        $query = Patient::query();

        // Filter by patient type (IPD vs OPD)
        if (request('type') === 'ipd') {
            // IPD: Patients currently admitted
            $query->whereHas('admissions', function ($q) {
                $q->where('status', 'admitted');
            });
        } elseif (request('type') === 'opd') {
            // OPD: Patients NOT currently admitted (optional logic)
            $query->whereDoesntHave('admissions', function ($q) {
                $q->where('status', 'admitted');
            });
        }

        // Filter by status (Soft Deletes)
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

    /**
     * Restore a soft-deleted patient.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore($id)
    {
        $patient = Patient::withTrashed()->findOrFail($id);
        Gate::authorize('delete', $patient);

        $patient->restore();
        $patient->update(['status' => 'active']);

        return redirect()->route('patients.index')->with('success', 'Patient restored successfully.');
    }

    /**
     * Show the form for creating a new patient.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        Gate::authorize('create', Patient::class);
        return view('patients.create');
    }

    /**
     * Store a newly created patient in storage.
     *
     * Features:
     * - Global Duplicate Check: Checks for existing patients by NID, Passport, Email, or Phone.
     * - Clinic Registration: Links existing global patients to the current clinic.
     * - Profile Photo: Handles image upload and storage.
     * - Automatic Credentials: Generates default password and sends welcome email.
     *
     * @param  \App\Http\Requests\StorePatientRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StorePatientRequest $request)
    {
        Gate::authorize('create', Patient::class);

        $data = $request->validated();
        $clinicId = auth()->user()->clinic_id;

        // 1. Check for existing patient globally (by NID, Passport, Email, Phone, etc.)
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
            $existingPatient = $query->where(function ($q) use ($conditions) {
                foreach ($conditions as $cond) {
                    $q->orWhere($cond[0], $cond[1]);
                }
            })->first();
        }

        if ($existingPatient) {
            // Check if patient is already linked to THIS clinic
            $alreadyLinked = $existingPatient->clinics()->whereKey($clinicId)->exists();

            // Legacy check for single-tenant structure compatibility
            if (!$alreadyLinked && $existingPatient->clinic_id == $clinicId) {
                $alreadyLinked = true;
            }

            if ($alreadyLinked) {
                return redirect()->route('patients.show', $existingPatient)
                    ->with('info', 'Patient is already registered in this clinic.');
            }

            // Link existing global patient to this clinic
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
     * Display the specified patient profile.
     *
     * Loads related data:
     * - Appointments (with doctor info)
     * - Admissions
     * - Vitals
     * - Medical History
     * - Invoices
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\View\View
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
     * Show the form for editing the specified patient.
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\View\View
     */
    public function edit(Patient $patient)
    {
        Gate::authorize('update', $patient);
        return view('patients.edit', compact('patient'));
    }

    /**
     * Update the specified patient in storage.
     *
     * Handles:
     * - Profile updates
     * - Profile photo replacement/deletion
     *
     * @param  \App\Http\Requests\UpdatePatientRequest  $request
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\RedirectResponse
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
     * Remove the specified patient from storage.
     *
     * Performs a soft delete and sets status to 'inactive'.
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\RedirectResponse
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
