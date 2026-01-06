<?php

namespace App\Http\Controllers\Extras;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Doctor;

class DoctorsExtrasController extends Controller
{
    public function assignment()
    {
        $clinics = Clinic::orderBy('name')->get();
        $doctors = Doctor::with(['user', 'clinics', 'primaryDepartment'])->orderByDesc('created_at')->get();
        return view('doctors.assignment', compact('clinics', 'doctors'));
    }

    public function schedules()
    {
        $doctors = Doctor::with('user')->orderBy('user_id')->get();
        return view('doctors.schedules', compact('doctors'));
    }
}
