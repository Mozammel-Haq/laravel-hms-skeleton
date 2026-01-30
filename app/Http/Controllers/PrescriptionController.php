<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\Consultation;
use App\Models\Doctor;
use App\Models\Medicine;
use App\Models\PrescriptionItem;
use App\Models\PatientComplaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

/**
 * Manages prescriptions created by doctors and processed by pharmacy.
 *
 * Responsibilities:
 * - Creation of prescriptions during consultations
 * - Viewing prescription history (Clinical & Pharmacy views)
 * - Filtering by fulfillment status (Pending/Fulfilled)
 * - Printing and Patient Portal access
 */
class PrescriptionController extends Controller
{
    /**
     * Display a listing of prescriptions.
     *
     * Supports filtering by:
     * - Status: 'pending' (not sold), 'fulfilled' (sold), 'trashed', 'all'
     * - Search: ID, Patient name, Doctor name
     * - Date Range: Issued at
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        Gate::authorize('viewAny', Prescription::class);

        $query = Prescription::with(['consultation.patient', 'consultation.doctor.user']);

        // Scope to Doctor's own prescriptions if user is a Doctor
        $doctor = Doctor::where('user_id', auth()->user()->id)->first();
        if ($doctor) {
            $query->whereHas('consultation', function ($q) use ($doctor) {
                $q->where('doctor_id', $doctor->id);
            });
        }

        // Filter by Status (Pharmacy Logic)
        if (request('status') === 'trashed') {
            $query->onlyTrashed();
        } elseif (request('status') === 'all') {
            $query->withTrashed();
        } elseif (request('status') === 'pending') {
            $query->doesntHave('pharmacySale');
        } elseif (request('status') === 'fulfilled') {
            $query->whereHas('pharmacySale');
        } elseif (request()->filled('status')) {
            // Fallback for direct status column matches
            $query->where('status', request('status'));
        }

        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('id', $search)
                    ->orWhereHas('consultation.patient', function ($sub) use ($search) {
                        $sub->where('name', 'like', '%' . $search . '%')
                            ->orWhere('patient_code', 'like', '%' . $search . '%');
                    })->orWhereHas('consultation.doctor.user', function ($sub) use ($search) {
                        $sub->where('name', 'like', '%' . $search . '%');
                    })->orWhere('status', 'like', '%' . $search . '%');
            });
        }

        if (request()->filled('from')) {
            $query->whereDate('issued_at', '>=', request('from'));
        }

        if (request()->filled('to')) {
            $query->whereDate('issued_at', '<=', request('to'));
        }

        $prescriptions = $query->latest()->paginate(20)->withQueryString();

        return view('clinical.prescription.index', compact('prescriptions'));
    }

    /**
     * Soft delete the specified prescription.
     *
     * @param  \App\Models\Prescription  $prescription
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Prescription $prescription)
    {
        Gate::authorize('delete', $prescription);
        $prescription->delete();
        return redirect()->route('clinical.prescriptions.index')->with('success', 'Prescription deleted successfully.');
    }

    /**
     * Restore a soft-deleted prescription.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore($id)
    {
        $prescription = Prescription::withTrashed()->findOrFail($id);
        Gate::authorize('delete', $prescription);
        $prescription->restore();
        return redirect()->route('clinical.prescriptions.index')->with('success', 'Prescription restored successfully.');
    }

    /**
     * Display the specified prescription.
     *
     * Shows prescription details, items, vitals history, and complaint linkage.
     *
     * @param  \App\Models\Prescription  $prescription
     * @return \Illuminate\View\View
     */
    public function show(Prescription $prescription)
    {
        Gate::authorize('view', $prescription);

        $prescription->load([
            'clinic',
            'items.medicine',
            'complaints',
            'consultation.patient',
            'consultation.doctor.user',
            'consultation.doctor.department',
        ]);

        $vitalsHistory = collect();
        if ($prescription->consultation && $prescription->consultation->visit) {
            $vitalsHistory = \App\Models\PatientVital::where('visit_id', $prescription->consultation->visit->id)
                ->orderByDesc('recorded_at')
                ->get();
        }

        return view('clinical.prescription.show', compact('prescription', 'vitalsHistory'));
    }


    /**
     * Show the form for creating a new prescription.
     *
     * Loads consultation details, patient vitals history, and available medicines.
     * Prevents creation if consultation is already completed.
     *
     * @param  \App\Models\Consultation  $consultation
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create(Consultation $consultation)
    {
        Gate::authorize('create', Prescription::class);
        if ($consultation->status === 'completed') {
            return redirect()->back()->with('error', 'Consultation is already completed.');
        }

        $consultation->load([
            'visit.appointment.patient',
            'visit.appointment.doctor.user',
            'visit.appointment.doctor.department',
        ]);
        $vitalsHistory = collect();
        if ($consultation->visit) {
            $vitalsHistory = \App\Models\PatientVital::where('visit_id', $consultation->visit->id)
                ->orderByDesc('recorded_at')
                ->get();
        }
        $medicines = Medicine::where('status', 'active')->orderBy('name')->get();
        return view('clinical.prescription.create', compact('consultation', 'medicines', 'vitalsHistory'));
    }

    /**
     * Store a newly created prescription in storage.
     *
     * Validates input, creates prescription and items, and updates consultation status.
     * Notifies the patient upon success.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Consultation  $consultation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Consultation $consultation)
    {
        Gate::authorize('create', Prescription::class);
        if ($consultation->status === 'completed') {
            return redirect()->back()->with('error', 'Consultation is already completed.');
        }

        if (!auth()->user()->hasRole('Doctor')) {
            abort(403, 'Only Doctors can make prescriptions.');
        }

        $validated = $request->validate([
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.medicine_id' => 'required|exists:medicines,id',
            'items.*.dosage' => 'required|string',
            'items.*.frequency' => 'required|string',
            'items.*.duration_days' => 'required|integer|min:1',
            'items.*.instructions' => 'nullable|string',
            'complaints' => 'nullable|array',
            'complaints.*' => 'nullable|string',
        ]);

        DB::transaction(function () use ($consultation, $validated, &$prescription) {

            $consultation->loadMissing('visit.appointment');
            // dd($consultation);
            $prescription = Prescription::create([
                'clinic_id' => $consultation->clinic_id,
                'consultation_id' => $consultation->id,
                'issued_at' => now(),
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($validated['items'] as $item) {
                PrescriptionItem::create([
                    'prescription_id' => $prescription->id,
                    'medicine_id' => $item['medicine_id'],
                    'dosage' => $item['dosage'],
                    'frequency' => $item['frequency'],
                    'duration_days' => $item['duration_days'],
                    'instructions' => $item['instructions'] ?? null,
                ]);
            }

            if (!empty($validated['complaints'])) {
                foreach ($validated['complaints'] as $c) {
                    $name = trim($c);
                    if ($name === '') continue;

                    $complaint = PatientComplaint::firstOrCreate(['name' => $name]);
                    $prescription->complaints()->syncWithoutDetaching([$complaint->id]);
                }
            }

            $consultation->update(['status' => 'completed']);
            $consultation->visit->appointment->update(['status' => 'completed']);
        });

        // 🔑 NOW refresh
        $consultation->refresh()->load('prescription');

        // Notify Patient
        if ($consultation->patient) {
            $consultation->patient->notify(new \App\Notifications\NewPrescriptionNotification($prescription));
        }

        return redirect()
            ->route('clinical.prescriptions.show', $consultation->prescription)
            ->with('success', 'Prescription created successfully.');
    }

    /**
     * Print the prescription (Doctor/Staff view).
     *
     * @param  \App\Models\Prescription  $prescription
     * @return \Illuminate\View\View
     */
    public function print(Prescription $prescription)
    {
        Gate::authorize('view', $prescription);
        $prescription->load(['clinic', 'items.medicine', 'complaints', 'consultation.patient', 'consultation.doctor.user', 'consultation.doctor.department']);
        $vitalsHistory = collect();
        if ($prescription->consultation && $prescription->consultation->visit) {
            $vitalsHistory = \App\Models\PatientVital::where('visit_id', $prescription->consultation->visit->id)->orderByDesc('recorded_at')->get();
        }
        return view('clinical.prescription.print', compact('prescription', 'vitalsHistory'));
    }

    /**
     * Print the prescription (Patient public view via signed URL).
     *
     * Validates the signed URL before showing the print view.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Prescription  $prescription
     * @return \Illuminate\View\View
     */
    public function patientPrint(Request $request, Prescription $prescription)
    {
        if (! $request->hasValidSignature()) {
            abort(403, 'Invalid or expired link.');
        }

        $prescription->load(['clinic', 'items.medicine', 'complaints', 'consultation.patient', 'consultation.doctor.user', 'consultation.doctor.department']);
        $vitalsHistory = collect();
        if ($prescription->consultation && $prescription->consultation->visit) {
            $vitalsHistory = \App\Models\PatientVital::where('visit_id', $prescription->consultation->visit->id)->orderByDesc('recorded_at')->get();
        }
        return view('clinical.prescription.print', compact('prescription', 'vitalsHistory'));
    }
}
