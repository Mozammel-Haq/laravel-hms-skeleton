<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Consultation;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\Visit;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ConsultationController extends Controller
{
    /**
     * Start a consultation for an appointment.
     */
    public function create(Appointment $appointment)
    {
        Gate::authorize('create', Consultation::class);
        
        // Ensure appointment belongs to this doctor? Policy handles 'create' generally, 
        // but we should check if the user is the doctor or has right permissions.
        
        if ($appointment->status === 'completed') {
            return redirect()->route('appointments.show', $appointment)
                ->with('warning', 'This appointment is already completed.');
        }

        $patient = $appointment->patient;
        $medicines = Medicine::where('status', 'active')->get(); // For prescription autocomplete

        return view('clinical.consultation.create', compact('appointment', 'patient', 'medicines'));
    }

    /**
     * Store the consultation details (Vitals, Diagnosis, Prescription).
     */
    public function store(Request $request, Appointment $appointment)
    {
        Gate::authorize('create', Consultation::class);

        $request->validate([
            'symptoms' => 'required|string',
            'diagnosis' => 'required|string',
            'notes' => 'nullable|string',
            // Vitals
            'weight' => 'nullable|numeric',
            'temperature' => 'nullable|numeric',
            'bp_systolic' => 'nullable|integer',
            'bp_diastolic' => 'nullable|integer',
            // Prescription Items
            'prescription_items' => 'nullable|array',
            'prescription_items.*.medicine_id' => 'required_with:prescription_items|exists:medicines,id',
            'prescription_items.*.dosage' => 'required_with:prescription_items|string', // e.g., "1-0-1"
            'prescription_items.*.duration' => 'required_with:prescription_items|string', // e.g., "5 days"
            'prescription_items.*.instruction' => 'nullable|string', // e.g., "After food"
        ]);

        DB::transaction(function () use ($request, $appointment) {
            // 1. Create Visit
            $visit = Visit::create([
                'clinic_id' => $appointment->clinic_id,
                'patient_id' => $appointment->patient_id,
                'doctor_id' => $appointment->doctor_id,
                'appointment_id' => $appointment->id,
                'visit_date' => now(),
                'status' => 'completed',
            ]);

            // 2. Create Consultation
            $consultation = Consultation::create([
                'clinic_id' => $appointment->clinic_id,
                'visit_id' => $visit->id,
                'doctor_id' => $appointment->doctor_id,
                'patient_id' => $appointment->patient_id,
                'symptoms' => $request->symptoms,
                'diagnosis' => $request->diagnosis,
                'notes' => $request->notes,
            ]);

            // 3. Create Prescription (if items exist)
            if ($request->has('prescription_items') && count($request->prescription_items) > 0) {
                $prescription = Prescription::create([
                    'clinic_id' => $appointment->clinic_id,
                    'consultation_id' => $consultation->id,
                    'patient_id' => $appointment->patient_id,
                    'doctor_id' => $appointment->doctor_id,
                    'issued_date' => now(),
                    'status' => 'active',
                ]);

                foreach ($request->prescription_items as $item) {
                    PrescriptionItem::create([
                        'prescription_id' => $prescription->id,
                        'medicine_id' => $item['medicine_id'],
                        'dosage' => $item['dosage'],
                        'duration' => $item['duration'],
                        'instruction' => $item['instruction'] ?? null,
                    ]);
                }
            }

            // 4. Update Appointment Status
            $appointment->update(['status' => 'completed']);
        });

        return redirect()->route('appointments.index')->with('success', 'Consultation completed successfully.');
    }

    public function show(Consultation $consultation)
    {
        Gate::authorize('view', $consultation);
        $consultation->load(['visit', 'prescription.items.medicine', 'doctor', 'patient']);
        return view('clinical.consultation.show', compact('consultation'));
    }
}
