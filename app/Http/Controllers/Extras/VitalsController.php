<?php

namespace App\Http\Controllers\Extras;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Visit;
use App\Models\PatientVital;
use App\Models\Admission;
use Illuminate\Http\Request;

class VitalsController extends Controller
{
    public function record(Request $request)
    {
        $visit = null;
        $appointment = null;

        if ($request->filled('admission_id')) {
            $admission = Admission::with('patient')->findOrFail($request->query('admission_id'));
            $visit = null;
            $appointment = null;
            $patients = collect([$admission->patient])->filter();
        } elseif ($request->filled('visit_id')) {
            $visit = Visit::with(['appointment.patient'])->findOrFail($request->query('visit_id'));
            $appointment = $visit->appointment;
            $patients = collect([$appointment->patient])->filter();
        } elseif ($request->filled('appointment_id')) {
            $appointment = Appointment::with(['patient', 'visit'])->findOrFail($request->query('appointment_id'));
            $visit = $appointment->visit;
            $patients = collect([$appointment->patient])->filter();
        } else {
            $patients = Patient::orderBy('name')->take(100)->get();
        }

        return view('vitals.record', compact('patients', 'visit', 'appointment'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'visit_id' => 'nullable|exists:visits,id',
            'admission_id' => 'nullable|exists:admissions,id',
            'inpatient_round_id' => 'nullable|exists:inpatient_rounds,id',
            'blood_pressure' => 'nullable|string|max:50',
            'heart_rate' => 'nullable|integer|min:0',
            'temperature' => 'nullable|numeric',
            'spo2' => 'nullable|numeric',
            'respiratory_rate' => 'nullable|integer|min:0',
            'weight' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'bmi' => 'nullable|numeric',
        ]);

        $visit = null;
        $admission = null;

        if (!empty($data['visit_id'])) {
            $visit = Visit::with('appointment')->findOrFail($data['visit_id']);
            if ($visit->appointment && $visit->appointment->patient_id !== (int) $data['patient_id']) {
                return redirect()->back()->withErrors([
                    'patient_id' => 'Selected patient does not match the visit.',
                ]);
            }
        }

        if (!empty($data['admission_id'])) {
            $admission = Admission::with('patient')->findOrFail($data['admission_id']);
            if ($admission->patient_id !== (int) $data['patient_id']) {
                return redirect()->back()->withErrors([
                    'patient_id' => 'Selected patient does not match the admission.',
                ]);
            }
        }

        PatientVital::create([
            'patient_id' => $data['patient_id'],
            'visit_id' => $data['visit_id'] ?? null,
            'admission_id' => $data['admission_id'] ?? null,
            'inpatient_round_id' => $data['inpatient_round_id'] ?? null,
            'blood_pressure' => $data['blood_pressure'] ?? null,
            'heart_rate' => $data['heart_rate'] ?? null,
            'temperature' => $data['temperature'] ?? null,
            'spo2' => $data['spo2'] ?? null,
            'respiratory_rate' => $data['respiratory_rate'] ?? null,
            'weight' => $data['weight'] ?? null,
            'height' => $data['height'] ?? null,
            'bmi' => $data['bmi'] ?? null,
            'recorded_by' => auth()->id(),
            'recorded_at' => now(),
        ]);

        if ($visit && $visit->appointment) {
            return redirect()
                ->route('appointments.show', $visit->appointment)
                ->with('success', 'Vitals recorded successfully.');
        }

        return redirect()
            ->route('vitals.history')
            ->with('success', 'Vitals recorded successfully.');
    }

    public function history()
    {
        $vitals = PatientVital::with(['patient', 'visit.appointment'])->latest('recorded_at')->paginate(20);

        return view('vitals.history', compact('vitals'));
    }
}
