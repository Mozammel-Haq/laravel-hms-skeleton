<?php

namespace App\Http\Controllers\Extras;

use App\Http\Controllers\Controller;
use App\Models\Appointment;

class DoctorSelfScheduleController extends Controller
{
    public function index()
    {
        $doctor = optional(auth()->user())->doctor;
        $appointments = Appointment::with(['patient'])->orderBy('appointment_date')->take(8)->get();
        return view('doctor.schedule.index', compact('doctor', 'appointments'));
    }
}
