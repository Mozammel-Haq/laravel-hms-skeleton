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

    if ($appointment->status !== 'confirmed') {
        return redirect()->route('appointments.index')
            ->with('warning', 'Consultation can only start for confirmed appointments.');
    }

    return view('clinical.consultation.create', [
        'appointment' => $appointment,
        'patient'     => $appointment->patient,
    ]);
}



   public function store(Request $request, Appointment $appointment)
{
    Gate::authorize('create', Consultation::class);

    $data = $request->validate([
        'diagnosis' => 'required|string|max:255',
        'doctor_notes' => 'required|string',
        'follow_up_required' => 'nullable|boolean',
        'follow_up_date' => 'nullable|date',
    ]);

    $consultation = null;

    DB::transaction(function () use ($appointment, $data, &$consultation) {

        $visit = Visit::firstOrCreate(
            ['appointment_id' => $appointment->id],
            [
                'check_in_time' => now(),
                'visit_status' => 'in_progress',
            ]
        );

        $consultation = Consultation::create([
            'visit_id' => $visit->id,
            'clinic_id' => $appointment->clinic_id,
            'doctor_id' => $appointment->doctor_id,
            'patient_id' => $appointment->patient_id,
            'diagnosis' => $data['diagnosis'],
            'doctor_notes' => $data['doctor_notes'],
            'follow_up_required' => (bool)($data['follow_up_required'] ?? false),
            'follow_up_date' => $data['follow_up_date'] ?? null,
            'status' => 'completed',
        ]);

        $appointment->update(['status' => 'completed']);

        $visit->update([
            'check_out_time' => now(),
            'visit_status' => 'completed',
        ]);
    });

    return redirect()
        ->route('clinical.prescriptions.create.withConsultation', $consultation)
        ->with('success', 'Consultation saved. Please write prescription.');
}


    public function show(Consultation $consultation)
    {
        Gate::authorize('view', $consultation);
        $consultation->load(['visit.appointment.patient', 'visit.appointment.doctor', 'prescription.items.medicine', 'visit']);
        return view('clinical.consultation.show', compact('consultation'));
    }
}
