<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\Consultation;
use App\Models\Medicine;
use App\Models\PrescriptionItem;
use Illuminate\Http\Request;
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
        $prescription->load(['items.medicine', 'consultation.patient', 'consultation.doctor', 'consultation']);
        return view('clinical.prescription.show', compact('prescription'));
    }

    public function create(Consultation $consultation)
    {
        Gate::authorize('create', Prescription::class);
        $medicines = Medicine::where('status', 'active')->orderBy('name')->get();
        return view('clinical.prescription.create', compact('consultation', 'medicines'));
    }

    public function store(Request $request, Consultation $consultation)
    {
        Gate::authorize('create', Prescription::class);
        $request->validate([
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.medicine_id' => 'required|exists:medicines,id',
            'items.*.dosage' => 'required|string',
            'items.*.frequency' => 'required|string',
            'items.*.duration_days' => 'required|integer|min:1',
            'items.*.instructions' => 'nullable|string',
        ]);

        $prescription = null;
        \DB::transaction(function () use ($request, $consultation, &$prescription) {
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
        });

        return redirect()->route('clinical.prescriptions.show', $prescription)
            ->with('success', 'Prescription created successfully.');
    }

    public function print(Prescription $prescription)
    {
        Gate::authorize('view', $prescription);
        $prescription->load(['items.medicine', 'consultation.patient', 'consultation.doctor']);
        return view('clinical.prescription.print', compact('prescription'));
    }
}
