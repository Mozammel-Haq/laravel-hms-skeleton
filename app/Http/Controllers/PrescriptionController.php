<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\Consultation;
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
        $prescriptions = Prescription::with(['consultation.patient', 'consultation.doctor'])
            ->orderBy('issued_at', 'desc')
            ->paginate(20);
        return view('clinical.prescription.index', compact('prescriptions'));
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
        if (!auth()->user()->hasRole('Doctor')) {
            abort(403);
        }
        $consultation->load([
            'visit.appointment.patient',
            'visit.appointment.doctor.user',
            'visit.appointment.doctor.department',
        ]);
        $medicines = Medicine::where('status', 'active')->orderBy('name')->get();
        return view('clinical.prescription.create', compact('consultation', 'medicines'));
    }

    public function store(Request $request, Consultation $consultation)
    {
        // dd($consultation, $request->all());
        Gate::authorize('create', Prescription::class);
        if (!auth()->user()->hasRole('Doctor')) {
            abort(403);
        }
        $request->validate([
            'notes' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.medicine_id' => 'required|exists:medicines,id',
            'items.*.dosage' => 'required|string',
            'items.*.frequency' => 'required|string',
            'items.*.duration_days' => 'required|integer|min:1',
            'items.*.instructions' => 'nullable|string',
            'complaints' => 'nullable|array',
            'complaints.*' => 'nullable|string',
        ]);

        $prescription = null;
        DB::transaction(function () use ($request, $consultation, &$prescription) {
            $consultation->loadMissing('visit.appointment');
            $prescription = Prescription::create([
                'clinic_id' => $consultation->clinic_id,
                'consultation_id' => $consultation->id,
                'issued_at' => now(),
                'notes' => $request->notes,
            ]);

            foreach ($request->items as $item) {
                PrescriptionItem::create([
                    'prescription_id' => $prescription->id,
                    'medicine_id' => $item['medicine_id'],
                    'dosage' => $item['dosage'],
                    'frequency' => $item['frequency'],
                    'duration_days' => $item['duration_days'],
                    'instructions' => $item['instructions'] ?? null,
                ]);
            }

            if (is_array($request->complaints)) {
                foreach ($request->complaints as $c) {
                    $name = trim((string)$c);
                    if ($name === '') continue;
                    $complaint = PatientComplaint::firstOrCreate(['name' => $name]);
                    $prescription->complaints()->syncWithoutDetaching([$complaint->id]);
                }
            }

            $consultation->update([
                'patient_id' => optional($consultation->visit->appointment)->patient_id,
                'doctor_id' => optional($consultation->visit->appointment)->doctor_id,
                'follow_up_required' => (bool)($request->is_followup ?? false),
                'diagnosis' => $request->diagnosis ?? null,
                'doctor_notes' => $request->notes ?? null,
                'follow_up_date' => $request->followup_date ?? null,
            ]);

            // Mark appointment as completed
            if ($consultation->visit && $consultation->visit->appointment) {
                $consultation->visit->appointment->update(['status' => 'completed']);
                $consultation->visit->update(['visit_status' => 'completed']);
                $consultation->update(['status' => 'completed']);
            }
        });

        return redirect()->route('clinical.prescriptions.show', $prescription)
            ->with('success', 'Prescription created successfully.');
    }
}
