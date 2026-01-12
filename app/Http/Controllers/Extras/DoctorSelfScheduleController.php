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

        $appointments = Appointment::where('doctor_id', $doctor->id)
            ->where('clinic_id', auth()->user()->clinic_id)
            ->with(['patient'])
            ->orderBy('appointment_date')
            ->orderBy('start_time')
            ->take(10)
            ->get();
            
        return view('doctor.schedule.index', compact('doctor', 'appointments'));
    }
}
