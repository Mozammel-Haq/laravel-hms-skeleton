<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Consultation;
use App\Models\Doctor;
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
        $doctor = Doctor::where('user_id', auth()->user()->id)->first();

        $query = Consultation::with(['patient', 'doctor']);

        if ($doctor) {
            $query->where('doctor_id', $doctor->id)
                ->whereHas('visit.appointment', function ($q) use ($doctor) {
                    $q->where('doctor_id', $doctor->id);
                });
        }

        if (request('status') === 'trashed') {
            $query->onlyTrashed();
        } else {
            $query->latest();
        }

        $consultations = $query->paginate(perPage: 50);

        return view('clinical.consultations.index', compact('consultations'));
    }

    public function destroy(Consultation $consultation)
    {
        Gate::authorize('delete', $consultation); // Assuming delete policy exists or maps to appropriate permission
        $consultation->delete();
        return redirect()->route('clinical.consultations.index')->with('success', 'Consultation deleted successfully.');
    }

    public function restore($id)
    {
        $consultation = Consultation::withTrashed()->findOrFail($id);
        Gate::authorize('delete', $consultation);
        $consultation->restore();
        return redirect()->route('clinical.consultations.index')->with('success', 'Consultation restored successfully.');
    }
    /**
     * Start a consultation for an appointment.
     */
    public function create(Appointment $appointment)
    {
        Gate::authorize('create', Consultation::class);
        $doctor = Doctor::where('user_id', auth()->id())->first();
        if (!$doctor || $appointment->doctor_id !== $doctor->id) {
            return redirect()->route('appointments.index')
                ->with('warning', 'You are not authorized to start a consultation for this appointment.');
        }
        $consultationInvoice = \App\Models\Invoice::where('appointment_id', $appointment->id)
            ->where('invoice_type', 'consultation')
            ->latest()
            ->first();
        if (!$consultationInvoice || $consultationInvoice->status !== 'paid') {
            return redirect()->route('appointments.index')
                ->with(
                    'warning',
                    'Consultation can only start after the consultation invoice has been generated and fully paid.'
                );
        }
        if (!in_array($appointment->status, ['confirmed', 'arrived'], true)) {
            return redirect()->route('appointments.index')
                ->with('warning', 'Consultation can only start for confirmed or arrived appointments.');
        }

        $visit = $appointment->visit;
        $latestVitals = null;
        $vitalsHistory = collect();
        if ($visit) {
            $vitalsHistory = \App\Models\PatientVital::where('visit_id', $visit->id)
                ->orderByDesc('recorded_at')
                ->get();
            $latestVitals = $vitalsHistory->first();
        }

        return view('clinical.consultation.create', [
            'appointment' => $appointment,
            'patient'     => $appointment->patient,
            'latestVitals' => $latestVitals,
            'vitalsHistory' => $vitalsHistory,
        ]);
    }



    public function store(Request $request, Appointment $appointment)
    {
        Gate::authorize('create', Consultation::class);
        $doctor = Doctor::where('user_id', auth()->id())->first();
        if (!$doctor || $appointment->doctor_id !== $doctor->id) {
            return redirect()->route('appointments.index')
                ->with('warning', 'You are not authorized to start a consultation for this appointment.');
        }
        if (!in_array($appointment->status, ['confirmed', 'arrived'], true)) {
            return redirect()->route('appointments.index')
                ->with('warning', 'Consultation can only start for confirmed or arrived appointments.');
        }
        $data = $request->validate([
            'diagnosis' => 'required|string|max:255',
            'doctor_notes' => 'required|string',
            'symptoms' => 'nullable|array',
            'symptoms.*' => 'nullable|string|max:255',
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

            $symptoms = array_values(
                array_filter($data['symptoms'] ?? [], function ($value) {
                    return trim($value) !== '';
                })
            );

            $consultation = Consultation::create([
                'visit_id' => $visit->id,
                'clinic_id' => $appointment->clinic_id,
                'doctor_id' => $appointment->doctor_id,
                'patient_id' => $appointment->patient_id,
                'diagnosis' => $data['diagnosis'],
                'doctor_notes' => $data['doctor_notes'],
                'symptoms' => $symptoms,
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
        $doctor = $consultation->visit?->appointment?->doctor ?? $consultation->doctor;
        $patientId = $consultation->visit?->appointment?->patient_id ?? $consultation->patient_id;
        $feeInfo = app(\App\Services\AppointmentService::class)->calculateFee($doctor, $patientId);
        return view('clinical.consultation.show', compact('consultation', 'feeInfo'));
    }
}
