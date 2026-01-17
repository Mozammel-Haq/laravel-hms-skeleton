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

class PrescriptionController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Prescription::class);
        
        $query = Prescription::with(['consultation.patient', 'consultation.doctor.user']);

        // Filter by doctor if the user is a doctor
        $doctor = Doctor::where('user_id', auth()->user()->id)->first();
        if ($doctor) {
            $query->whereHas('consultation', function($q) use ($doctor) {
                $q->where('doctor_id', $doctor->id);
            });
        }

        if (request('status') === 'trashed') {
            $query->onlyTrashed();
        }

        $prescriptions = $query->latest('issued_at')->paginate(20);

        return view('clinical.prescription.index', compact('prescriptions'));
    }

    public function destroy(Prescription $prescription)
    {
        Gate::authorize('delete', $prescription);
        $prescription->delete();
        return redirect()->route('clinical.prescriptions.index')->with('success', 'Prescription deleted successfully.');
    }

    public function restore($id)
    {
        $prescription = Prescription::withTrashed()->findOrFail($id);
        Gate::authorize('delete', $prescription);
        $prescription->restore();
        return redirect()->route('clinical.prescriptions.index')->with('success', 'Prescription restored successfully.');
    }

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


        return view('clinical.prescription.show', compact('prescription'));
    }


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

    public function store(Request $request, Consultation $consultation)
    {
        Gate::authorize('create', Prescription::class);
        if ($consultation->status === 'completed') {
            return redirect()->back()->with('error', 'Consultation is already completed.');
        }

        if (!auth()->user()->hasRole('Doctor')) {
            abort(403);
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

        return redirect()
            ->route('clinical.prescriptions.show', $consultation->prescription)
            ->with('success', 'Prescription created successfully.');
    }
}
