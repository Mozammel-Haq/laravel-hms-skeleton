<?php

namespace App\Http\Controllers\Extras;

use App\Http\Controllers\Controller;
use App\Models\Appointment;

class DoctorSelfScheduleController extends Controller
{
    public function index()
    {
        $doctor = optional(auth()->user())->doctor;

        if (!$doctor) {
            abort(403, 'User is not associated with a doctor profile.');
        }

        $clinicId = auth()->user()->clinic_id;

        $schedules = $doctor->schedules()
            ->where('clinic_id', $clinicId)
            ->whereNull('schedule_date')
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day_of_week');

        $appointments = Appointment::where('doctor_id', $doctor->id)
            ->where('clinic_id', $clinicId)
            ->with(['patient'])
            ->orderBy('appointment_date')
            ->orderBy('start_time')
            ->take(10)
            ->get();

        return view('doctor.schedule.index', compact('doctor', 'appointments', 'schedules'));
    }
}
