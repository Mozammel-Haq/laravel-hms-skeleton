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
    public function index()
    {
        Gate::authorize('view_consultations');
        $consultations = Consultation::with(['visit.appointment.patient', 'visit.appointment.doctor'])
            ->latest()
            ->paginate(50);
        return view('clinical.consultations.index', compact('consultations'));
    }
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
        $medicines = Medicine::orderBy('name')->get();

        return view('clinical.consultation.create', compact('appointment', 'patient', 'medicines'));
    }

    /**
     * Store the consultation details (Vitals, Diagnosis, Prescription).
     */
    public function store(Request $request, Appointment $appointment)
    {
        Gate::authorize('create', Consultation::class);

        $request->validate([
            'doctor_notes' => 'required|string',
            'diagnosis' => 'required|string',
            'follow_up_required' => 'nullable|boolean',
            'follow_up_date' => 'nullable|date',
            'prescription_items' => 'nullable|array',
            'prescription_items.*.medicine_id' => 'required_with:prescription_items|exists:medicines,id',
            'prescription_items.*.dosage' => 'required_with:prescription_items|string',
            'prescription_items.*.frequency' => 'required_with:prescription_items|string',
            'prescription_items.*.duration_days' => 'required_with:prescription_items|integer|min:1',
            'prescription_items.*.instructions' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $appointment) {
            $visit = Visit::create([
                'appointment_id' => $appointment->id,
                'check_in_time' => now(),
                'check_out_time' => now(),
                'visit_status' => 'completed',
            ]);

            $consultation = Consultation::create([
                'visit_id' => $visit->id,
                'doctor_notes' => $request->doctor_notes,
                'diagnosis' => $request->diagnosis,
                'follow_up_required' => (bool)($request->follow_up_required ?? false),
                'follow_up_date' => $request->follow_up_date,
            ]);

            if ($request->has('prescription_items') && count($request->prescription_items) > 0) {
                $prescription = Prescription::create([
                    'consultation_id' => $consultation->id,
                    'issued_at' => now(),
                    'notes' => null,
                ]);

                foreach ($request->prescription_items as $item) {
                    PrescriptionItem::create([
                        'prescription_id' => $prescription->id,
                        'medicine_id' => $item['medicine_id'],
                        'dosage' => $item['dosage'],
                        'frequency' => $item['frequency'],
                        'duration_days' => (int)$item['duration_days'],
                        'instructions' => $item['instructions'] ?? null,
                    ]);
                }
            }

            $appointment->update(['status' => 'completed']);
        });

        return redirect()->route('appointments.index')->with('success', 'Consultation completed successfully.');
    }

    public function show(Consultation $consultation)
    {
        Gate::authorize('view', $consultation);
        $consultation->load(['visit.appointment.patient', 'visit.appointment.doctor', 'prescription.items.medicine', 'visit']);
        return view('clinical.consultation.show', compact('consultation'));
    }
}
