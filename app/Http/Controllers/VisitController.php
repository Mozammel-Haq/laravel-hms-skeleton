<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Consultation;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class VisitController extends Controller
{
    public function index()
    {
        $visits = Visit::with(['appointment.patient', 'consultation'])->latest()->paginate(20);
        return view('visits.index', compact('visits'));
    }

    public function show(Visit $visit)
    {
        $visit->load(['appointment.patient', 'consultation']);
        return view('visits.show', compact('visit'));
    }

    public function create()
    {
        Gate::authorize('create', Visit::class);
        $appointments = Appointment::whereIn('status', ['pending', 'confirmed'])
            ->with(['patient', 'doctor'])
            ->latest()
            ->take(50)
            ->get();
        return view('visits.create', compact('appointments'));
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Visit::class);
        $data = $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
        ]);

        $appointment = Appointment::findOrFail($data['appointment_id']);

        $visit = DB::transaction(function () use ($appointment) {
            $visit = Visit::where('appointment_id', $appointment->id)->latest()->first();
            if (!$visit) {
                $visit = Visit::create([
                    'appointment_id' => $appointment->id,
                    'check_in_time' => now(),
                    'visit_status' => 'in_progress',
                ]);
            }

            $consultation = $visit->consultation;
            if (!$consultation) {
                $consultation = Consultation::create([
                    'visit_id' => $visit->id,
                    'doctor_id' => $appointment->doctor_id,
                    'patient_id' => $appointment->patient_id,
                ]);
                $visit->consultation_id = $consultation->id;
                $visit->save();
            }

            return $visit;
        });

        return redirect()->route('visits.show', $visit)
            ->with('success', 'Visit started. Consultation is ready.');
    }
}
