<?php

namespace App\Http\Controllers\Extras;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Visit;
use App\Models\PatientVital;
use App\Models\Admission;
use App\Models\InpatientRound;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class VitalsController extends Controller
{
    public function record(Request $request)
    {
        Gate::authorize('create', PatientVital::class);
        $visit = null;
        $appointment = null;
        $round = null;

        if ($request->filled('inpatient_round_id')) {
            $round = InpatientRound::with('admission.patient')->findOrFail($request->query('inpatient_round_id'));
            $admission = $round->admission;
            $patients = collect([$admission->patient])->filter();
        } elseif ($request->filled('admission_id')) {
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
            $patients = collect();
            if ($request->has('patient_id') || old('patient_id')) {
                $patientId = $request->input('patient_id') ?? old('patient_id');
                $patient = Patient::find($patientId);
                if ($patient) {
                    $patients->push($patient);
                }
            }
        }

        return view('vitals.record', compact('patients', 'visit', 'appointment'));
    }

    public function store(Request $request)
    {
        Gate::authorize('create', PatientVital::class);

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
        $round = null;

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

        if (!empty($data['inpatient_round_id'])) {
            $round = InpatientRound::with('admission.patient')->findOrFail($data['inpatient_round_id']);
            if (!empty($data['admission_id']) && $round->admission_id !== (int) $data['admission_id']) {
                return redirect()->back()->withErrors([
                    'inpatient_round_id' => 'Selected round does not belong to the admission.',
                ]);
            }
            if ($round->admission && $round->admission->patient_id !== (int) $data['patient_id']) {
                return redirect()->back()->withErrors([
                    'patient_id' => 'Selected patient does not match the round admission.',
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

        if ($admission) {
            return redirect()
                ->route('ipd.show', $admission)
                ->with('success', 'Vitals recorded successfully.');
        }

        return redirect()
            ->route('vitals.history')
            ->with('success', 'Vitals recorded successfully.');
    }

    public function history(Request $request)
    {
        $query = PatientVital::with(['patient', 'visit.appointment'])->latest('recorded_at');

        $patient = null;
        if ($request->filled('patient_id')) {
            $patientId = (int) $request->input('patient_id');
            $query->where('patient_id', $patientId);
            $patient = Patient::find($patientId);
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('patient', function ($sub) use ($search) {
                    $sub->where('name', 'like', '%' . $search . '%')
                        ->orWhere('patient_code', 'like', '%' . $search . '%');
                })->orWhere('notes', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('from')) {
            $query->whereDate('recorded_at', '>=', $request->input('from'));
        }

        if ($request->filled('to')) {
            $query->whereDate('recorded_at', '<=', $request->input('to'));
        }

        $vitals = $query->paginate(20);

        return view('vitals.history', compact('vitals', 'patient'));
    }
}
