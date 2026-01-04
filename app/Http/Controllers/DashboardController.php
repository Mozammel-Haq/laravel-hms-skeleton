<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('Super Admin')) {
            return view('dashboards.super_admin');
        }

        if ($user->hasRole('Clinic Admin')) {
            return view('dashboards.clinic_admin');
        }

        if ($user->hasRole('Doctor')) {
            return view('dashboards.doctor');
        }

        if ($user->hasRole('Nurse')) {
            return view('dashboards.nurse');
        }

        if ($user->hasRole('Receptionist')) {
            return view('dashboards.receptionist');
        }

        if ($user->hasRole('Lab Technician')) {
            return view('dashboards.lab_technician');
        }

        if ($user->hasRole('Pharmacist')) {
            return view('dashboards.pharmacist');
        }

        if ($user->hasRole('Accountant')) {
            return view('dashboards.accountant');
        }

        return view('dashboard'); // Fallback
    }
}
