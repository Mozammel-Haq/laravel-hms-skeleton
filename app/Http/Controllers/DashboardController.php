<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\LabTestOrder;
use App\Models\Patient;
use App\Models\PharmacySale;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * DashboardController
 *
 * Manages the dashboard views for different user roles.
 * Displays key statistics and metrics relevant to the user's role.
 */
class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     *
     * Shows different statistics based on the user's role:
     * - Super Admin: Global system stats.
     * - Clinic Admin: Clinic-specific stats (doctors, patients, appointments).
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $clinic = $user->clinic;

        // Super Admin Context Switch
        if ($user->hasRole('Super Admin')) {
            $selectedClinicId = \Illuminate\Support\Facades\Session::get('selected_clinic_id');
            $clinic = $selectedClinicId ? \App\Models\Clinic::find($selectedClinicId) : null;

            $months = collect();
            for ($i = 11; $i >= 0; $i--) {
                $months->push(now()->subMonths($i)->format('Y-m'));
            }

            $driver = DB::connection()->getDriverName();
            $prefix = DB::getTablePrefix();
            $patientTable = (new \App\Models\Patient)->getTable();
            $prefixedPatientTable = $prefix.$patientTable;

            if ($driver === 'sqlite') {
                $monthExpression = "strftime('%Y-%m', $prefixedPatientTable.created_at)";
            } else {
                $monthExpression = "DATE_FORMAT($prefixedPatientTable.created_at, '%Y-%m')";
            }

            // Clinic Month Expression
            $clinicTable = (new \App\Models\Clinic)->getTable();
            $prefixedClinicTable = $prefix.$clinicTable;
            if ($driver === 'sqlite') {
                $clinicMonthExpression = "strftime('%Y-%m', $prefixedClinicTable.created_at)";
            } else {
                $clinicMonthExpression = "DATE_FORMAT($prefixedClinicTable.created_at, '%Y-%m')";
            }

            if ($clinic) {
                // Scoped Stats for Selected Clinic
                $stats = [
                    'clinics_total' => 1,
                    'clinics_active' => $clinic->status === 'active' ? 1 : 0,
                    'users_total' => \App\Models\User::where('clinic_id', $clinic->id)->count(),
                    'patients_total' => $clinic->patients()->count(),
                    'context_name' => $clinic->name,
                ];

                // Scoped Chart Data
                // Clinic Growth is flat (1) for single clinic context
                $clinicGrowthData = $months->mapWithKeys(fn ($m) => [$m => 1]);

                $patientGrowthData = $clinic->patients()
                    ->select(DB::raw("$monthExpression as month"), DB::raw('count(*) as count'))
                    ->whereRaw("$prefixedPatientTable.created_at >= ?", [now()->subMonths(11)->startOfMonth()])
                    ->groupBy('month')
                    ->pluck('count', 'month');

                // Scoped User Roles
                $userRoles = \App\Models\User::withoutGlobalScopes()
                    ->from(DB::raw($prefix.'users as u'))
                    ->join(DB::raw($prefix.'user_role as ur'), DB::raw('u.id'), '=', DB::raw('ur.user_id'))
                    ->join(DB::raw($prefix.'roles as r'), DB::raw('ur.role_id'), '=', DB::raw('r.id'))
                    ->whereRaw('u.clinic_id = ?', [$clinic->id])
                    ->whereNull(DB::raw('u.deleted_at'))
                    ->selectRaw('r.name as role, count(*) as count')
                    ->groupBy(DB::raw('r.name'))
                    ->get();
            } else {
                // Global Stats
                $stats = [
                    'clinics_total' => \App\Models\Clinic::count(),
                    'clinics_active' => \App\Models\Clinic::where('status', 'active')->count(),
                    'users_total' => \App\Models\User::withoutTenant()->count(),
                    'patients_total' => \App\Models\Patient::withoutTenant()->count(),
                    'context_name' => 'Global System',
                ];

                $clinicGrowthData = \App\Models\Clinic::select(DB::raw("$clinicMonthExpression as month"), DB::raw('count(*) as count'))
                    ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
                    ->groupBy('month')
                    ->pluck('count', 'month');

                $patientGrowthData = \App\Models\Patient::withoutTenant()
                    ->select(DB::raw("$monthExpression as month"), DB::raw('count(*) as count'))
                    ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
                    ->groupBy('month')
                    ->pluck('count', 'month');

                $userRoles = \App\Models\User::withoutGlobalScopes()
                    ->from(DB::raw($prefix.'users as u'))
                    ->join(DB::raw($prefix.'user_role as ur'), DB::raw('u.id'), '=', DB::raw('ur.user_id'))
                    ->join(DB::raw($prefix.'roles as r'), DB::raw('ur.role_id'), '=', DB::raw('r.id'))
                    ->whereNull(DB::raw('u.deleted_at'))
                    ->selectRaw('r.name as role, count(*) as count')
                    ->groupBy(DB::raw('r.name'))
                    ->get();
            }

            $chartData = [
                'system_growth' => [
                    'months' => $months->values()->toArray(),
                    'clinics' => $months->map(fn ($m) => $clinicGrowthData[$m] ?? 0)->toArray(),
                    'patients' => $months->map(fn ($m) => $patientGrowthData[$m] ?? 0)->toArray(),
                ],
                'user_roles' => [
                    'labels' => $userRoles->pluck('role')->toArray(),
                    'counts' => $userRoles->pluck('count')->toArray(),
                ],
            ];

            return view('dashboards.super_admin', compact('stats', 'chartData'));
        }

        if ($user->hasRole('Clinic Admin') && $clinic) {
            $clinicId = $clinic->id;

            $stats = [
                'doctors' => [
                    'total' => $clinic->doctors()->count(),
                ],
                'patients' => [
                    'total' => $clinic->patients()->count(),
                ],
                'appointments' => [
                    'total' => $clinic->appointments()->count(),
                ],
                'revenue' => [
                    'last_month' => $clinic->invoices()
                        ->whereBetween((new Invoice)->getTable().'.created_at', [now()->subMonth(), now()])->sum('total_amount'),
                    'expenses_last_30_days' => Expense::where('clinic_id', $clinic->id)
                        ->whereBetween('expense_date', [now()->subDays(30), now()])->sum('amount'),
                ],
            ];
            $popularDoctors = $clinic->doctors()->select((new Doctor)->getTable().'.*')
                ->join('doctor_clinic as dc', 'dc.doctor_id', '=', 'doctors.id')
                ->where('dc.clinic_id', $clinicId)
                ->withCount(['appointments' => function ($q) use ($clinicId) {
                    $q->where('clinic_id', $clinicId);
                }])
                ->orderByDesc('appointments_count')
                ->take(5)
                ->get();
            $prefix = DB::getTablePrefix();
            $topDepartments = $clinic->appointments()->where((new Appointment)->getTable().'.clinic_id', $clinicId)
                ->join('doctors', 'appointments.doctor_id', '=', 'doctors.id')
                ->join('departments', 'doctors.primary_department_id', '=', 'departments.id')
                ->selectRaw($prefix.'departments.name as name, COUNT('.$prefix.'appointments.id) as total')
                ->groupBy(DB::raw($prefix.'departments.name'))
                ->orderByDesc('total')
                ->take(3)
                ->get();
            $doctorAvailability = [
                'active' => $clinic->doctors()->join('doctor_clinic as dc', 'dc.doctor_id', '=', 'doctors.id')
                    ->where('dc.clinic_id', $clinicId)->where((new Doctor)->getTable().'.status', 'active')->count(),
                'inactive' => $clinic->doctors()->join('doctor_clinic as dc', 'dc.doctor_id', '=', 'doctors.id')
                    ->where('dc.clinic_id', $clinicId)->where((new Doctor)->getTable().'.status', 'inactive')->count(),
            ];
            $incomeByDepartment = collect();

            // 1. Income from Appointments
            $apptIncome = $clinic->invoices()
                ->where((new Invoice)->getTable().'.clinic_id', $clinicId)
                ->whereNotNull('appointment_id')
                ->join('appointments', 'invoices.appointment_id', '=', 'appointments.id')
                ->join('departments', 'appointments.department_id', '=', 'departments.id')
                ->selectRaw($prefix.'departments.name as department, SUM('.$prefix.'invoices.total_amount) as revenue')
                ->groupBy(DB::raw($prefix.'departments.name'))
                ->pluck('revenue', 'department');

            // 2. Income from Admissions (IPD)
            $admIncome = $clinic->invoices()
                ->where((new Invoice)->getTable().'.clinic_id', $clinicId)
                ->whereNotNull('admission_id')
                ->join('admissions', 'invoices.admission_id', '=', 'admissions.id')
                ->join('doctors', 'admissions.admitting_doctor_id', '=', 'doctors.id')
                ->join('departments', 'doctors.primary_department_id', '=', 'departments.id')
                ->selectRaw($prefix.'departments.name as department, SUM('.$prefix.'invoices.total_amount) as revenue')
                ->groupBy(DB::raw($prefix.'departments.name'))
                ->pluck('revenue', 'department');

            // Merge incomes
            $allDepts = $apptIncome->keys()->merge($admIncome->keys())->unique();
            foreach ($allDepts as $dept) {
                $total = ($apptIncome[$dept] ?? 0) + ($admIncome[$dept] ?? 0);
                if ($total > 0) {
                    $incomeByDepartment->push([
                        'department' => $dept,
                        'revenue' => $total,
                    ]);
                }
            }

            $latestAppointments = $clinic->appointments()
                ->with(['doctor', 'patient'])
                ->latest()
                ->take(5)
                ->get();
            $recentTransactions = $clinic->invoices()
                ->latest()
                ->take(5)
                ->get();

            $topPatients = $clinic->patients()
                ->withCount(['appointments' => function ($q) use ($clinicId) {
                    $q->where('clinic_id', $clinicId);
                }])
                ->orderByDesc('appointments_count')
                ->take(5)
                ->get();

            // Chart Data: Revenue Trend (Last 30 Days)
            $dates = collect();
            for ($i = 29; $i >= 0; $i--) {
                $dates->push(now()->subDays($i)->format('Y-m-d'));
            }

            $revenueData = $clinic->invoices()
                ->where('created_at', '>=', now()->subDays(30))
                ->selectRaw('DATE(created_at) as date, sum(total_amount) as total')
                ->groupBy('date')
                ->pluck('total', 'date');

            $appointmentData = $clinic->appointments()
                ->where('created_at', '>=', now()->subDays(30))
                ->selectRaw('DATE(created_at) as date, count(*) as count')
                ->groupBy('date')
                ->pluck('count', 'date');

            $performanceTrend = [
                'dates' => $dates->values()->toArray(),
                'revenue' => $dates->map(fn ($date) => $revenueData[$date] ?? 0)->toArray(),
                'appointments' => $dates->map(fn ($date) => $appointmentData[$date] ?? 0)->toArray(),
            ];

            $incomeByDepartmentChart = $incomeByDepartment->sortByDesc('revenue')->take(5);

            $topDoctorsChart = $popularDoctors->map(function ($doctor) {
                return [
                    'name' => $doctor->user ? $doctor->user->name : ('Doctor #'.$doctor->id),
                    'count' => $doctor->appointments_count,
                ];
            });

            $chartData = [
                'performance_trend' => $performanceTrend,
                'income_by_department' => [
                    'labels' => $incomeByDepartmentChart->pluck('department')->toArray(),
                    'amounts' => $incomeByDepartmentChart->pluck('revenue')->toArray(),
                ],
                'doctor_status' => [
                    'labels' => ['Active', 'Inactive'],
                    'counts' => [
                        $doctorAvailability['active'],
                        $doctorAvailability['inactive'],
                    ],
                ],
                'top_doctors' => [
                    'names' => $topDoctorsChart->pluck('name')->toArray(),
                    'counts' => $topDoctorsChart->pluck('count')->toArray(),
                ],
            ];

            return view('dashboards.clinic_admin', [
                'stats' => $stats,
                'popularDoctors' => $popularDoctors,
                'topPatients' => $topPatients,
                'topDepartments' => $topDepartments,
                'doctorAvailability' => $doctorAvailability,
                'latestAppointments' => $latestAppointments,
                'recentTransactions' => $recentTransactions,
                'chartData' => $chartData,
            ]);
        }

        if ($user->hasRole('Doctor')) {
            $prefix = DB::getTablePrefix();
            $cards = [
                'appointments_today' => Appointment::where('appointment_date', now()->toDateString())
                    ->where('doctor_id', optional($user->doctor)->id)
                    ->count(),
                'prescriptions_month' => Prescription::withoutGlobalScopes()->from(DB::raw($prefix.'prescriptions as p'))
                    ->join(DB::raw($prefix.'consultations as c'), DB::raw('p.consultation_id'), '=', DB::raw('c.id'))
                    ->join(DB::raw($prefix.'visits as v'), DB::raw('c.visit_id'), '=', DB::raw('v.id'))
                    ->join(DB::raw($prefix.'appointments as a'), DB::raw('v.appointment_id'), '=', DB::raw('a.id'))
                    ->where(DB::raw('a.doctor_id'), optional($user->doctor)->id)
                    ->where(DB::raw('p.clinic_id'), auth()->user()->clinic_id)
                    ->whereNull(DB::raw('p.deleted_at'))
                    ->whereBetween(DB::raw('p.issued_at'), [now()->startOfMonth(), now()->endOfMonth()])
                    ->count(),
                'lab_orders_pending' => LabTestOrder::where('doctor_id', optional($user->doctor)->id)->where('status', 'pending')->count(),
            ];
            $appointments = Appointment::with(['patient'])
                ->where('doctor_id', optional($user->doctor)->id)
                ->latest()
                ->take(10)
                ->get();
            $prescriptions = Prescription::withoutGlobalScopes()->from(DB::raw($prefix.'prescriptions as p'))
                ->join(DB::raw($prefix.'consultations as c'), DB::raw('p.consultation_id'), '=', DB::raw('c.id'))
                ->join(DB::raw($prefix.'visits as v'), DB::raw('c.visit_id'), '=', DB::raw('v.id'))
                ->join(DB::raw($prefix.'appointments as a'), DB::raw('v.appointment_id'), '=', DB::raw('a.id'))
                ->where(DB::raw('a.doctor_id'), optional($user->doctor)->id)
                ->where(DB::raw('p.clinic_id'), auth()->user()->clinic_id)
                ->whereNull(DB::raw('p.deleted_at'))
                ->orderBy(DB::raw('p.issued_at'), 'desc')
                ->select(DB::raw('p.*'))
                ->with(['consultation.patient', 'consultation.doctor'])
                ->take(10)
                ->get();
            $dates = collect();
            for ($i = 6; $i >= 0; $i--) {
                $dates->push(now()->subDays($i)->format('Y-m-d'));
            }

            $appointmentsTrendData = Appointment::where('doctor_id', optional($user->doctor)->id)
                ->where('created_at', '>=', now()->subDays(7))
                ->selectRaw('DATE(appointment_date) as date, count(*) as count')
                ->groupBy('date')
                ->pluck('count', 'date');

            $prescriptionsTrendData = Prescription::withoutGlobalScopes()->from(DB::raw($prefix.'prescriptions as p'))
                ->join(DB::raw($prefix.'consultations as c'), DB::raw('p.consultation_id'), '=', DB::raw('c.id'))
                ->join(DB::raw($prefix.'visits as v'), DB::raw('c.visit_id'), '=', DB::raw('v.id'))
                ->join(DB::raw($prefix.'appointments as a'), DB::raw('v.appointment_id'), '=', DB::raw('a.id'))
                ->where(DB::raw('a.doctor_id'), optional($user->doctor)->id)
                ->where(DB::raw('p.clinic_id'), auth()->user()->clinic_id)
                ->whereNull(DB::raw('p.deleted_at'))
                ->where(DB::raw('p.issued_at'), '>=', now()->subDays(7))
                ->selectRaw('DATE(p.issued_at) as date, count(*) as count')
                ->groupBy('date')
                ->pluck('count', 'date');

            $consultationActivity = [
                'dates' => $dates->values()->toArray(),
                'appointments' => $dates->map(fn ($date) => $appointmentsTrendData[$date] ?? 0)->toArray(),
                'prescriptions' => $dates->map(fn ($date) => $prescriptionsTrendData[$date] ?? 0)->toArray(),
            ];

            $appointmentStatus = Appointment::where('doctor_id', optional($user->doctor)->id)
                ->selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status');

            $chartData = [
                'consultation_activity' => $consultationActivity,
                'appointment_status' => [
                    'labels' => $appointmentStatus->keys()->toArray(),
                    'counts' => $appointmentStatus->values()->toArray(),
                ],
            ];

            return view('dashboards.doctor', compact('cards', 'appointments', 'prescriptions', 'chartData'));
        }

        if ($user->hasRole('Nurse')) {
            $prefix = DB::getTablePrefix();

            $bedStats = [
                'total' => \App\Models\Bed::withoutTenant()->from(DB::raw($prefix.'beds as b'))
                    ->join(DB::raw($prefix.'rooms as r'), DB::raw('b.room_id'), '=', DB::raw('r.id'))
                    ->join(DB::raw($prefix.'wards as w'), DB::raw('r.ward_id'), '=', DB::raw('w.id'))
                    ->where(DB::raw('w.clinic_id'), auth()->user()->clinic_id)
                    ->count(),
                'available' => \App\Models\Bed::withoutTenant()->from(DB::raw($prefix.'beds as b'))
                    ->join(DB::raw($prefix.'rooms as r'), DB::raw('b.room_id'), '=', DB::raw('r.id'))
                    ->join(DB::raw($prefix.'wards as w'), DB::raw('r.ward_id'), '=', DB::raw('w.id'))
                    ->where(DB::raw('w.clinic_id'), auth()->user()->clinic_id)
                    ->where(DB::raw('b.status'), 'available')
                    ->count(),
                'occupied' => \App\Models\Bed::withoutTenant()->from(DB::raw($prefix.'beds as b'))
                    ->join(DB::raw($prefix.'rooms as r'), DB::raw('b.room_id'), '=', DB::raw('r.id'))
                    ->join(DB::raw($prefix.'wards as w'), DB::raw('r.ward_id'), '=', DB::raw('w.id'))
                    ->where(DB::raw('w.clinic_id'), auth()->user()->clinic_id)
                    ->where(DB::raw('b.status'), 'occupied')
                    ->count(),
                'maintenance' => \App\Models\Bed::withoutTenant()->from(DB::raw($prefix.'beds as b'))
                    ->join(DB::raw($prefix.'rooms as r'), DB::raw('b.room_id'), '=', DB::raw('r.id'))
                    ->join(DB::raw($prefix.'wards as w'), DB::raw('r.ward_id'), '=', DB::raw('w.id'))
                    ->where(DB::raw('w.clinic_id'), auth()->user()->clinic_id)
                    ->where(DB::raw('b.status'), 'maintenance')
                    ->count(),
            ];

            $cards = [
                'admissions_active' => Admission::where('status', 'admitted')->count(),
                'beds_available' => $bedStats['available'],
            ];
            $admissions = Admission::with(['patient', 'doctor'])->where('status', 'admitted')->latest()->take(12)->get();
            $admissionsTrend = Admission::where('created_at', '>=', now()->subDays(7))
                ->selectRaw('DATE(admission_date) as date, count(*) as count')
                ->groupBy('date')
                ->pluck('count', 'date');

            $dischargesTrend = Admission::where('discharge_date', '>=', now()->subDays(7))
                ->selectRaw('DATE(discharge_date) as date, count(*) as count')
                ->groupBy('date')
                ->pluck('count', 'date');

            $dates = collect();
            for ($i = 6; $i >= 0; $i--) {
                $dates->push(now()->subDays($i)->format('Y-m-d'));
            }

            $bedStatus = \App\Models\Bed::withoutTenant()->from(DB::raw($prefix.'beds as b'))
                ->join(DB::raw($prefix.'rooms as r'), DB::raw('b.room_id'), '=', DB::raw('r.id'))
                ->join(DB::raw($prefix.'wards as w'), DB::raw('r.ward_id'), '=', DB::raw('w.id'))
                ->where(DB::raw('w.clinic_id'), auth()->user()->clinic_id)
                ->selectRaw('b.status, count(*) as count')
                ->groupBy(DB::raw('b.status'))
                ->pluck('count', 'b.status');

            // Ward Occupancy for Radial Bar Chart
            $wardOccupancy = \App\Models\Bed::withoutTenant()->from(DB::raw($prefix.'beds as b'))
                ->join(DB::raw($prefix.'rooms as r'), DB::raw('b.room_id'), '=', DB::raw('r.id'))
                ->join(DB::raw($prefix.'wards as w'), DB::raw('r.ward_id'), '=', DB::raw('w.id'))
                ->where(DB::raw('w.clinic_id'), auth()->user()->clinic_id)
                ->selectRaw('w.name as ward, count(*) as total, sum(case when b.status = "occupied" then 1 else 0 end) as occupied')
                ->groupBy(DB::raw('w.name'))
                ->get()
                ->map(function ($ward) {
                    return [
                        'name' => $ward->ward,
                        'rate' => $ward->total > 0 ? round(($ward->occupied / $ward->total) * 100, 1) : 0,
                    ];
                });

            $chartData = [
                'patient_movement' => [
                    'dates' => $dates->values()->toArray(),
                    'admissions' => $dates->map(fn ($date) => $admissionsTrend[$date] ?? 0)->toArray(),
                    'discharges' => $dates->map(fn ($date) => $dischargesTrend[$date] ?? 0)->toArray(),
                ],
                'ward_occupancy' => [
                    'labels' => $wardOccupancy->pluck('name')->toArray(),
                    'rates' => $wardOccupancy->pluck('rate')->toArray(),
                ],
                'bed_status' => [
                    'labels' => $bedStatus->keys()->toArray(),
                    'counts' => $bedStatus->values()->toArray(),
                ],
                'bed_occupancy' => [
                    'total' => $bedStats['total'],
                    'occupied' => $bedStats['occupied'],
                    'rate' => $bedStats['total'] > 0 ? round(($bedStats['occupied'] / $bedStats['total']) * 100, 1) : 0,
                ],
            ];

            return view('dashboards.nurse', compact('cards', 'admissions', 'chartData', 'bedStats'));
        }

        if ($user->hasRole('Receptionist')) {
            $doctorAvailability = [
                'active' => \App\Models\Doctor::join('doctor_clinic as dc', 'dc.doctor_id', '=', 'doctors.id')
                    ->where('dc.clinic_id', auth()->user()->clinic_id)->where((new Doctor)->getTable().'.status', 'active')->count(),
                'inactive' => \App\Models\Doctor::join('doctor_clinic as dc', 'dc.doctor_id', '=', 'doctors.id')
                    ->where('dc.clinic_id', auth()->user()->clinic_id)->where((new Doctor)->getTable().'.status', 'inactive')->count(),
            ];

            $cards = [
                'appointments_today' => Appointment::where('appointment_date', now()->toDateString())->count(),
                'patients_total' => Patient::count(),
                'doctors_active' => $doctorAvailability['active'],
            ];
            $appointments = Appointment::with(['patient', 'doctor'])->latest()->take(12)->get();
            $patients = Patient::latest()->take(12)->get();
            $registrationTrend = Patient::where('created_at', '>=', now()->subDays(7))
                ->selectRaw('DATE(created_at) as date, count(*) as count')
                ->groupBy('date')
                ->pluck('count', 'date');

            $appointmentsTrend = Appointment::where('appointment_date', '>=', now()->subDays(7))
                ->selectRaw('DATE(appointment_date) as date, count(*) as count')
                ->groupBy('date')
                ->pluck('count', 'date');

            $dates = collect();
            for ($i = 6; $i >= 0; $i--) {
                $dates->push(now()->subDays($i)->format('Y-m-d'));
            }

            $chartData = [
                'front_desk_activity' => [
                    'dates' => $dates->values()->toArray(),
                    'registrations' => $dates->map(fn ($date) => $registrationTrend[$date] ?? 0)->toArray(),
                    'appointments' => $dates->map(fn ($date) => $appointmentsTrend[$date] ?? 0)->toArray(),
                ],
                'doctor_status' => [
                    'labels' => ['Active', 'Inactive'],
                    'counts' => [$doctorAvailability['active'], $doctorAvailability['inactive']],
                ],
            ];

            return view('dashboards.receptionist', compact('cards', 'appointments', 'patients', 'chartData'));
        }

        if ($user->hasRole('Lab Technician')) {
            $orderStats = LabTestOrder::selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status');

            $cards = [
                'orders_pending' => $orderStats['pending'] ?? 0,
                'orders_completed' => $orderStats['completed'] ?? 0,
            ];
            $orders = LabTestOrder::with(['patient'])->latest()->take(15)->get();

            $labOrdersTrend = LabTestOrder::where('created_at', '>=', now()->subDays(7))
                ->selectRaw('DATE(order_date) as date, count(*) as count')
                ->groupBy('date')
                ->pluck('count', 'date');

            $labCompletedTrend = LabTestOrder::where('created_at', '>=', now()->subDays(7))
                ->where('status', 'completed')
                ->selectRaw('DATE(order_date) as date, count(*) as count')
                ->groupBy('date')
                ->pluck('count', 'date');

            $dates = collect();
            for ($i = 6; $i >= 0; $i--) {
                $dates->push(now()->subDays($i)->format('Y-m-d'));
            }

            $chartData = [
                'lab_activity' => [
                    'dates' => $dates->values()->toArray(),
                    'requests' => $dates->map(fn ($date) => $labOrdersTrend[$date] ?? 0)->toArray(),
                    'completed' => $dates->map(fn ($date) => $labCompletedTrend[$date] ?? 0)->toArray(),
                ],
                'lab_order_status' => [
                    'labels' => $orderStats->keys()->toArray(),
                    'counts' => $orderStats->values()->toArray(),
                ],
            ];

            return view('dashboards.lab_technician', compact('cards', 'orders', 'chartData'));
        }

        if ($user->hasRole('Pharmacist')) {
            $pendingPrescriptions = Prescription::doesntHave('pharmacySale')->count();
            $dispensedPrescriptions = Prescription::has('pharmacySale')->count();

            $cards = [
                'prescriptions_active' => $pendingPrescriptions + $dispensedPrescriptions,
                'sales_today' => PharmacySale::whereDate('sale_date', now()->toDateString())->sum('total_amount'),
            ];
            $prescriptions = Prescription::with(['consultation.patient'])
                ->orderBy('issued_at', 'desc')
                ->take(10)
                ->get();
            $sales = PharmacySale::with(['patient'])->latest()->take(10)->get();
            $salesTrend = PharmacySale::where('sale_date', '>=', now()->subDays(7))
                ->selectRaw('DATE(sale_date) as date, sum(total_amount) as total')
                ->groupBy('date')
                ->pluck('total', 'date');

            $dispensedTrend = PharmacySale::where('sale_date', '>=', now()->subDays(7))
                ->selectRaw('DATE(sale_date) as date, count(*) as count')
                ->groupBy('date')
                ->pluck('count', 'date');

            $dates = collect();
            for ($i = 6; $i >= 0; $i--) {
                $dates->push(now()->subDays($i)->format('Y-m-d'));
            }

            $chartData = [
                'pharmacy_activity' => [
                    'dates' => $dates->values()->toArray(),
                    'sales' => $dates->map(fn ($date) => $salesTrend[$date] ?? 0)->toArray(),
                    'dispensed' => $dates->map(fn ($date) => $dispensedTrend[$date] ?? 0)->toArray(),
                ],
                'prescription_status' => [
                    'labels' => ['Pending', 'Dispensed'],
                    'counts' => [$pendingPrescriptions, $dispensedPrescriptions],
                ],
            ];

            return view('dashboards.pharmacist', compact('cards', 'prescriptions', 'sales', 'chartData'));
        }

        if ($user->hasRole('Accountant')) {
            $invoiceStatus = Invoice::selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status');

            $cards = [
                'invoices_unpaid' => $invoiceStatus['unpaid'] ?? 0,
                'invoices_paid' => $invoiceStatus['paid'] ?? 0,
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

            $dates = collect();
            for ($i = 29; $i >= 0; $i--) {
                $dates->push(now()->subDays($i)->format('Y-m-d'));
            }

            $incomeTrend = Invoice::where('created_at', '>=', now()->subDays(30))
                ->selectRaw('DATE(created_at) as date, sum(total_amount) as total')
                ->groupBy('date')
                ->pluck('total', 'date');

            $invoiceVolumeTrend = Invoice::where('created_at', '>=', now()->subDays(30))
                ->selectRaw('DATE(created_at) as date, count(*) as count')
                ->groupBy('date')
                ->pluck('count', 'date');

            $chartData = [
                'financial_performance' => [
                    'dates' => $dates->values()->toArray(),
                    'revenue' => $dates->map(fn ($date) => $incomeTrend[$date] ?? 0)->toArray(),
                    'invoices' => $dates->map(fn ($date) => $invoiceVolumeTrend[$date] ?? 0)->toArray(),
                ],
                'invoice_status' => [
                    'labels' => $invoiceStatus->keys()->toArray(),
                    'counts' => $invoiceStatus->values()->toArray(),
                ],
            ];

            return view('dashboards.accountant', compact('cards', 'invoices', 'chartData'));
        }

        // Default fallback if no role matches
        abort(403, 'Unauthorized access: User has no assigned role.');
    }
}
