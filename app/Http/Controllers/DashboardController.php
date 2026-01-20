<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use App\Models\Prescription;
use App\Models\LabTestOrder;
use App\Models\PharmacySale;
use App\Models\Admission;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\LeaveRequest;
use App\Models\Patient;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        // dd($user);
        $clinic = $user->clinic;
        if ($user->hasRole('Super Admin')) {
            $stats = [
                'clinics_total' => \App\Models\Clinic::count(),
                'clinics_active' => \App\Models\Clinic::where('status', 'active')->count(),
                'users_total' => \App\Models\User::count(),
                'patients_total' => \App\Models\Patient::withoutTenant()->count(),
            ];
            return view('dashboards.super_admin', compact('stats'));
        }

        if ($user->hasRole('Clinic Admin')) {
            $clinicId = $clinic->id;
            $stats = [
                'doctors' => [


                    'total' => $clinic->doctors()->where('clinic_id', $clinicId)->count(),

                    'last_7_days' => $clinic->doctors()->where('clinic_id', $clinicId)
                        ->whereBetween((new Doctor())->getTable() . '.created_at', [now()->subDays(7), now()])->count(),
                    'last_30_days' => $clinic->doctors()->where('clinic_id', $clinicId)
                        ->whereBetween((new Doctor())->getTable() . '.created_at', [now()->subDays(30), now()])->count(),
                    'last_year' => $clinic->doctors()->where('clinic_id', $clinicId)
                        ->whereBetween((new Doctor())->getTable() . '.created_at', [now()->subYear(), now()])->count(),
                ],
                'patients' => [
                    'total' => $clinic->patients()->where('clinic_id', $clinicId)->count(),
                    'last_7_days' => $clinic->patients()->where('clinic_id', $clinicId)
                        ->whereBetween((new Patient())->getTable() . '.created_at', [now()->subDays(7), now()])->count(),
                ],
                'appointments' => [
                    'total' => $clinic->appointments()->where('clinic_id', $clinicId)->count(),
                    'last_7_days' => $clinic->appointments()->where('clinic_id', $clinicId)
                        ->whereBetween((new Appointment())->getTable() . '.created_at', [now()->subDays(7), now()])->count(),
                    'last_30_days' => $clinic->appointments()->where('clinic_id', $clinicId)
                        ->whereBetween((new Appointment())->getTable() . '.created_at', [now()->subDays(30), now()])->count(),
                    'last_year' => $clinic->appointments()->where('clinic_id', $clinicId)
                        ->whereBetween((new Appointment())->getTable() . '.created_at', [now()->subYear(), now()])->count(),
                    'active_last_7_days' => $clinic->appointments()->where('clinic_id', $clinicId)
                        ->where((new Appointment())->getTable() . '.status', 'active')
                        ->whereBetween((new Appointment())->getTable() . '.created_at', [now()->subDays(7), now()])->count(),
                    'completed_last_7_days' => $clinic->appointments()->where('clinic_id', $clinicId)
                        ->where((new Appointment())->getTable() . '.status', 'completed')
                        ->whereBetween((new Appointment())->getTable() . '.created_at', [now()->subDays(7), now()])->count(),
                    'cancelled_last_7_days' => $clinic->appointments()->where('clinic_id', $clinicId)
                        ->where((new Appointment())->getTable() . '.status', 'cancelled')
                        ->whereBetween((new Appointment())->getTable() . '.created_at', [now()->subDays(7), now()])->count(),
                    'pending_last_7_days' => $clinic->appointments()->where('clinic_id', $clinicId)
                        ->where((new Appointment())->getTable() . '.status', 'pending')
                        ->whereBetween((new Appointment())->getTable() . '.created_at', [now()->subDays(7), now()])->count(),
                    'active_last_30_days' => $clinic->appointments()->where('clinic_id', $clinicId)
                        ->where((new Appointment())->getTable() . '.status', 'active')
                        ->whereBetween((new Appointment())->getTable() . '.created_at', [now()->subDays(30), now()])->count(),
                    'completed_last_30_days' => $clinic->appointments()->where('clinic_id', $clinicId)
                        ->where((new Appointment())->getTable() . '.status', 'completed')
                        ->whereBetween((new Appointment())->getTable() . '.created_at', [now()->subDays(30), now()])->count(),
                    'cancelled_last_30_days' => $clinic->appointments()->where('clinic_id', $clinicId)
                        ->where((new Appointment())->getTable() . '.status', 'cancelled')
                        ->whereBetween((new Appointment())->getTable() . '.created_at', [now()->subDays(30), now()])->count(),
                    'pending_last_30_days' => $clinic->appointments()->where('clinic_id', $clinicId)
                        ->where((new Appointment())->getTable() . '.status', 'pending')
                        ->whereBetween((new Appointment())->getTable() . '.created_at', [now()->subDays(30), now()])->count(),
                    'active_last_year' => $clinic->appointments()->where('clinic_id', $clinicId)
                        ->where((new Appointment())->getTable() . '.status', 'active')
                        ->whereBetween((new Appointment())->getTable() . '.created_at', [now()->subYear(), now()])->count(),
                    'completed_last_year' => $clinic->appointments()->where('clinic_id', $clinicId)
                        ->where((new Appointment())->getTable() . '.status', 'completed')
                        ->whereBetween((new Appointment())->getTable() . '.created_at', [now()->subYear(), now()])->count(),
                    'cancelled_last_year' => $clinic->appointments()->where('clinic_id', $clinicId)
                        ->where((new Appointment())->getTable() . '.status', 'cancelled')
                        ->whereBetween((new Appointment())->getTable() . '.created_at', [now()->subYear(), now()])->count(),
                    'pending_last_year' => $clinic->appointments()->where('clinic_id', $clinicId)
                        ->where((new Appointment())->getTable() . '.status', 'pending')
                        ->whereBetween((new Appointment())->getTable() . '.created_at', [now()->subYear(), now()])->count(),
                ],
                'revenue' => [
                    'total' => $clinic->invoices()->where('clinic_id', $clinicId)->sum('total_amount'),
                    'last_7_days' => $clinic->invoices()->where('clinic_id', $clinicId)
                        ->whereBetween((new Invoice())->getTable() . '.created_at', [now()->subDays(7), now()])->sum('total_amount'),
                    'last_30_days' => $clinic->invoices()->where('clinic_id', $clinicId)
                        ->whereBetween((new Invoice())->getTable() . '.created_at', [now()->subDays(30), now()])->sum('total_amount'),
                ],
                'invoices' => [
                    'total' => $clinic->invoices()->where('clinic_id', $clinicId)->count(),
                    'unpaid' => $clinic->invoices()->where('clinic_id', $clinicId)->where('status', 'unpaid')->count(),
                    'paid' => $clinic->invoices()->where('clinic_id', $clinicId)->where('status', 'paid')->count(),
                ],
            ];
            $appointmentStats = $clinic->appointments()->where('clinic_id', $clinicId)
                ->selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status');
            $popularDoctors = $clinic->doctors()->select((new Doctor())->getTable() . '.*')
                ->join('doctor_clinic as dc', 'dc.doctor_id', '=', 'doctors.id')
                ->where('dc.clinic_id', $clinicId)
                ->withCount(['appointments' => function ($q) use ($clinicId) {
                    $q->where('clinic_id', $clinicId);
                }])
                ->orderByDesc('appointments_count')
                ->take(5)
                ->get();
            $calendarAppointments = $clinic->appointments()->where('clinic_id', $clinicId)
                ->whereMonth((new Appointment())->getTable() . '.appointment_date', now()->month)
                ->whereYear((new Appointment())->getTable() . '.appointment_date', now()->year)
                ->select('id', 'appointment_date', DB::raw('start_time as appointment_time'), DB::raw('appointment_type as type'))
                ->get();
            $prefix = DB::getTablePrefix();
            $topDepartments = $clinic->appointments()->where((new Appointment())->getTable() . '.clinic_id', $clinicId)
                ->join('doctors', 'appointments.doctor_id', '=', 'doctors.id')
                ->join('departments', 'doctors.primary_department_id', '=', 'departments.id')
                ->selectRaw($prefix . 'departments.name as name, COUNT(' . $prefix . 'appointments.id) as total')
                ->groupBy(DB::raw($prefix . 'departments.name'))
                ->orderByDesc('total')
                ->take(3)
                ->get();
            $doctorAvailability = [
                'active' => $clinic->doctors()->join('doctor_clinic as dc', 'dc.doctor_id', '=', 'doctors.id')
                    ->where('dc.clinic_id', $clinicId)->where((new Doctor())->getTable() . '.status', 'active')->count(),
                'inactive' => $clinic->doctors()->join('doctor_clinic as dc', 'dc.doctor_id', '=', 'doctors.id')
                    ->where('dc.clinic_id', $clinicId)->where((new Doctor())->getTable() . '.status', 'inactive')->count(),
            ];
            $incomeByDepartment = $clinic->invoices()->where((new Invoice())->getTable() . '.clinic_id', $clinicId)
                ->join('appointments', 'invoices.appointment_id', '=', 'appointments.id')
                ->join('departments', 'appointments.department_id', '=', 'departments.id')
                ->selectRaw($prefix . 'departments.name as department, COUNT(' . $prefix . (new Invoice())->getTable() . '.id) as total_invoices, SUM(' . $prefix . (new Invoice())->getTable() . '.total_amount) as revenue')
                ->groupBy(DB::raw($prefix . 'departments.name'))
                ->get();
            $latestAppointments = $clinic->appointments()->where('clinic_id', $clinicId)
                ->with(['doctor', 'patient'])
                ->latest()
                ->take(5)
                ->get();
            $topPatients = $clinic->patients()->where('clinic_id', $clinicId)
                ->withCount('appointments')
                ->withSum('invoices', 'total_amount')
                ->orderByDesc('invoices_sum_total_amount')
                ->take(5)
                ->get();
            $recentTransactions = $clinic->invoices()->where('clinic_id', $clinicId)
                ->latest()
                ->take(5)
                ->get();
            return view('dashboards.clinic_admin', [
                'stats' => $stats,
                'appointmentStats' => $appointmentStats,
                'popularDoctors' => $popularDoctors,
                'calendarAppointments' => $calendarAppointments,
                'topDepartments' => $topDepartments,
                'doctorAvailability' => $doctorAvailability,
                'incomeByDepartment' => $incomeByDepartment,
                'latestAppointments' => $latestAppointments,
                'topPatients' => $topPatients,
                'recentTransactions' => $recentTransactions,
            ]);
        }

        if ($user->hasRole('Doctor')) {
            $cards = [
                'appointments_today' => Appointment::where('appointment_date', now()->toDateString())
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
                'appointments_today' => Appointment::where('appointment_date', now()->toDateString())->count(),
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
                'revenue_today' => Invoice::whereDate('created_at', now()->toDateString())->sum('total_amount'),
                'revenue_month' => Invoice::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->sum('total_amount'),
                'revenue_total' => Invoice::sum('total_amount'),
            ];

            $query = Invoice::query();

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('invoice_number', 'like', "%{$search}%")
                        ->orWhere('id', 'like', "%{$search}%");
                });
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $invoices = $query->latest()->paginate(15)->withQueryString();

            return view('dashboards.accountant', compact('cards', 'invoices'));
        }

        // Default fallback if no role matches
        // return view('dashboard');
        abort(403, 'Unauthorized access: User has no assigned role.');
    }
}
