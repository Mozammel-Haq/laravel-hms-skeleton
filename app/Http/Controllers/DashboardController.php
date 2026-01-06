<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use App\Models\Prescription;
use App\Models\LabTestOrder;
use App\Models\PharmacySale;
use App\Models\Admission;
use App\Models\Invoice;
use App\Models\Patient;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('Super Admin')) {
            return view('dashboards.super_admin');
        }

        if ($user->hasRole('Clinic Admin')) {
            $stats = [
                'patients' => Patient::count(),
                'appointments_today' => Appointment::whereDate('created_at', now()->toDateString())->count(),
                'admissions_active' => Admission::where('status', 'admitted')->count(),
                'revenue_month' => Invoice::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->sum('total_amount'),
            ];
            $latestAppointments = Appointment::with(['patient', 'doctor'])->latest()->take(10)->get();
            $latestAdmissions = Admission::with(['patient', 'doctor'])->latest()->take(10)->get();
            return view('dashboards.clinic_admin', compact('stats', 'latestAppointments', 'latestAdmissions'));
        }

        if ($user->hasRole('Doctor')) {
            $cards = [
                'appointments_today' => Appointment::whereDate('created_at', now()->toDateString())
                    ->where('doctor_id', optional($user->doctor)->id)
                    ->count(),
                'prescriptions_month' => Prescription::join('consultations', 'prescriptions.consultation_id', '=', 'consultations.id')
                    ->join('visits', 'consultations.visit_id', '=', 'visits.id')
                    ->join('appointments', 'visits.appointment_id', '=', 'appointments.id')
                    ->where('appointments.doctor_id', optional($user->doctor)->id)
                    ->whereBetween('prescriptions.issued_at', [now()->startOfMonth(), now()->endOfMonth()])
                    ->count(),
                'lab_orders_pending' => LabTestOrder::where('doctor_id', optional($user->doctor)->id)->where('status', 'pending')->count(),
            ];
            $appointments = Appointment::with(['patient'])
                ->where('doctor_id', optional($user->doctor)->id)
                ->latest()
                ->take(10)
                ->get();
            $prescriptions = Prescription::join('consultations', 'prescriptions.consultation_id', '=', 'consultations.id')
                ->join('visits', 'consultations.visit_id', '=', 'visits.id')
                ->join('appointments', 'visits.appointment_id', '=', 'appointments.id')
                ->where('appointments.doctor_id', optional($user->doctor)->id)
                ->orderBy('prescriptions.issued_at', 'desc')
                ->select('prescriptions.*')
                ->with(['consultation.patient', 'consultation.doctor'])
                ->take(10)
                ->get();
            return view('dashboards.doctor', compact('cards', 'appointments', 'prescriptions'));
        }

        if ($user->hasRole('Nurse')) {
            $cards = [
                'admissions_active' => Admission::where('status', 'admitted')->count(),
                'beds_available' => \App\Models\Bed::withoutTenant()
                    ->join('rooms', 'beds.room_id', '=', 'rooms.id')
                    ->join('wards', 'rooms.ward_id', '=', 'wards.id')
                    ->where('wards.clinic_id', auth()->user()->clinic_id)
                    ->where('beds.status', 'available')
                    ->count(),
            ];
            $admissions = Admission::with(['patient', 'doctor'])->where('status', 'admitted')->latest()->take(12)->get();
            return view('dashboards.nurse', compact('cards', 'admissions'));
        }

        if ($user->hasRole('Receptionist')) {
            $cards = [
                'appointments_today' => Appointment::whereDate('created_at', now()->toDateString())->count(),
                'patients_total' => Patient::count(),
            ];
            $appointments = Appointment::with(['patient', 'doctor'])->latest()->take(12)->get();
            $patients = Patient::latest()->take(12)->get();
            return view('dashboards.receptionist', compact('cards', 'appointments', 'patients'));
        }

        if ($user->hasRole('Lab Technician')) {
            $cards = [
                'orders_pending' => LabTestOrder::where('status', 'pending')->count(),
                'orders_completed' => LabTestOrder::where('status', 'completed')->count(),
            ];
            $orders = LabTestOrder::with(['patient'])->latest()->take(15)->get();
            return view('dashboards.lab_technician', compact('cards', 'orders'));
        }

        if ($user->hasRole('Pharmacist')) {
            $cards = [
                'prescriptions_active' => Prescription::count(),
                'sales_today' => PharmacySale::whereDate('sale_date', now()->toDateString())->sum('total_amount'),
            ];
            $prescriptions = Prescription::with(['consultation.patient'])
                ->orderBy('issued_at', 'desc')
                ->take(10)
                ->get();
            $sales = PharmacySale::with(['patient'])->latest()->take(10)->get();
            return view('dashboards.pharmacist', compact('cards', 'prescriptions', 'sales'));
        }

        if ($user->hasRole('Accountant')) {
            $cards = [
                'invoices_unpaid' => Invoice::where('status', 'unpaid')->count(),
                'invoices_paid' => Invoice::where('status', 'paid')->count(),
                'revenue_month' => Invoice::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->sum('total_amount'),
            ];
            $invoices = Invoice::latest()->take(15)->get();
            return view('dashboards.accountant', compact('cards', 'invoices'));
        }

        return view('dashboard');
    }
}
