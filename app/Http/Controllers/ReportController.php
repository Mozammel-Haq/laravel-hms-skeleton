<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Admission;
use App\Models\Visit;
use App\Models\Payment;
use App\Models\Consultation;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;

class ReportController extends Controller
{
    private function getDateRange(Request $request)
    {
        $range = $request->get('range', 'month'); // day, week, month, year, custom
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        if ($range == 'custom' && $startDate && $endDate) {
            return [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ];
        }

        $now = Carbon::now();

        switch ($range) {
            case 'today':
                return [$now->clone()->startOfDay(), $now->clone()->endOfDay()];
            case 'week':
                return [$now->clone()->startOfWeek(), $now->clone()->endOfWeek()];
            case 'year':
                return [$now->clone()->startOfYear(), $now->clone()->endOfYear()];
            case 'month':
            default:
                return [$now->clone()->startOfMonth(), $now->clone()->endOfMonth()];
        }
    }

    private function getFormatForGroup($range)
    {
        switch ($range) {
            case 'today':
                return '%H:00'; // Hourly
            case 'year':
                return '%Y-%m'; // Monthly
            default:
                return '%Y-%m-%d'; // Daily
        }
    }

    public function index()
    {
        Gate::authorize('view_reports');
        return view('reports.index');
    }

    public function financial(Request $request)
    {
        Gate::authorize('view_financial_reports');

        [$startDate, $endDate] = $this->getDateRange($request);

        $query = Invoice::whereBetween('created_at', [$startDate, $endDate]);

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // KPIs
        $revenue = (clone $query)->sum('total_amount');
        $paid = (clone $query)->where('status', 'paid')->sum('total_amount');
        $pending = (clone $query)->where('status', 'unpaid')->sum('total_amount');
        $invoiceCount = (clone $query)->count();

        // Revenue by Type (Pie Chart)
        $byType = (clone $query)
            ->selectRaw('invoice_type, SUM(total_amount) as total')
            ->groupBy('invoice_type')
            ->pluck('total', 'invoice_type');

        // Revenue Over Time (Line Chart)
        $groupBy = $request->get('range') == 'year' ? 'DATE_FORMAT(created_at, "%Y-%m")' : 'DATE(created_at)';

        $daily = Invoice::selectRaw("$groupBy as date, SUM(total_amount) as total, SUM(CASE WHEN status='paid' THEN total_amount ELSE 0 END) as paid_amount")
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Payment Methods (Donut Chart)
        $paymentMethods = Payment::whereBetween('paid_at', [$startDate, $endDate])
            ->selectRaw('payment_method, SUM(amount) as total')
            ->groupBy('payment_method')
            ->pluck('total', 'payment_method');

        $data = compact('revenue', 'paid', 'pending', 'invoiceCount', 'startDate', 'endDate', 'byType', 'daily', 'paymentMethods');

        if ($request->has('export')) {
            return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\Reports\FinancialExport($data), 'financial-report.xlsx');
        }

        return view('reports.financial', $data);
    }

    public function compare(Request $request)
    {
        if (!auth()->user()->hasRole('Super Admin')) {
            abort(403, 'Only Super Admin can compare clinics.');
        }

        [$startDate, $endDate] = $this->getDateRange($request);

        $clinicIds = $request->input('clinics', []);
        $clinics = Clinic::all();
        $selectedClinics = collect();
        $stats = [];

        if (!empty($clinicIds)) {
            $selectedClinics = Clinic::whereIn('id', $clinicIds)->get();

            foreach ($selectedClinics as $clinic) {
                $stats[$clinic->id] = [
                    'patients' => Patient::withoutGlobalScope('clinic')
                        ->where('clinic_id', $clinic->id)
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->count(),
                    'appointments' => Appointment::withoutGlobalScope('clinic')
                        ->where('clinic_id', $clinic->id)
                        ->whereBetween('appointment_date', [$startDate, $endDate])
                        ->count(),
                    'revenue' => Invoice::withoutGlobalScope('clinic')
                        ->where('clinic_id', $clinic->id)
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->sum('total_amount'),
                    'staff' => \App\Models\User::withoutGlobalScope('clinic')
                        ->where('clinic_id', $clinic->id)
                        ->count(), // Keep staff as total count for capacity context
                ];
            }
        }

        $data = compact('clinics', 'selectedClinics', 'stats', 'clinicIds', 'startDate', 'endDate');

        if ($request->has('export')) {
            return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\Reports\CompareExport($data), 'clinic-comparison.xlsx');
        }

        return view('reports.compare', $data);
    }

    public function patientDemographics(Request $request)
    {
        Gate::authorize('view_reports');
        [$startDate, $endDate] = $this->getDateRange($request);

        // Gender Distribution (Pie)
        $genderStats = Patient::whereBetween('created_at', [$startDate, $endDate])
            ->select('gender', DB::raw('count(*) as total'))
            ->groupBy('gender')
            ->pluck('total', 'gender');

        // Age Groups (Bar)
        $patients = Patient::whereBetween('created_at', [$startDate, $endDate])
            ->select('date_of_birth')
            ->get();

        $ageGroups = [
            '0-12' => 0,
            '13-19' => 0,
            '20-39' => 0,
            '40-59' => 0,
            '60+' => 0
        ];

        foreach ($patients as $p) {
            $age = $p->age; // Accessor from model
            if ($age <= 12) $ageGroups['0-12']++;
            elseif ($age <= 19) $ageGroups['13-19']++;
            elseif ($age <= 39) $ageGroups['20-39']++;
            elseif ($age <= 59) $ageGroups['40-59']++;
            else $ageGroups['60+']++;
        }

        // New Patients Trend (Line)
        $groupBy = $request->get('range') == 'year' ? 'DATE_FORMAT(created_at, "%Y-%m")' : 'DATE(created_at)';
        $newPatients = Patient::selectRaw("$groupBy as date, count(*) as total")
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $data = compact('genderStats', 'ageGroups', 'newPatients', 'startDate', 'endDate');

        if ($request->has('export')) {
            return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\Reports\DemographicsExport($data), 'patient-demographics.xlsx');
        }

        return view('reports.demographics', $data);
    }

    public function summary(Request $request)
    {
        Gate::authorize('view_reports');

        [$startDate, $endDate] = $this->getDateRange($request);

        $patientsTotal = Patient::count(); // Total ever
        $newPatientsTotal = Patient::whereBetween('created_at', [$startDate, $endDate])->count(); // New in period
        $admissionsTotal = Admission::whereBetween('created_at', [$startDate, $endDate])->count();
        $invoicesTotal = Invoice::whereBetween('created_at', [$startDate, $endDate])->sum('total_amount');
        $paymentsTotal = Payment::whereBetween('paid_at', [$startDate, $endDate])->sum('amount');

        // Recent Activity
        $admissions = Admission::with(['patient', 'doctor'])->latest()->take(5)->get();

        // Income vs Expense (if we had expenses, currently just Income)
        $groupBy = $request->get('range') == 'year' ? 'DATE_FORMAT(created_at, "%Y-%m")' : 'DATE(created_at)';

        $incomeTrend = Invoice::selectRaw("$groupBy as date, SUM(total_amount) as total")
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Appointment Status Distribution
        $appointmentStats = Appointment::whereBetween('appointment_date', [$startDate, $endDate])
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $data = compact(
            'patientsTotal',
            'newPatientsTotal',
            'admissionsTotal',
            'invoicesTotal',
            'paymentsTotal',
            'admissions',
            'incomeTrend',
            'appointmentStats',
            'startDate',
            'endDate'
        );

        if ($request->has('export')) {
            return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\Reports\SummaryExport($data), 'executive-summary.xlsx');
        }

        return view('reports.summary', $data);
    }

    public function doctorPerformance(Request $request)
    {
        Gate::authorize('view_reports');
        [$startDate, $endDate] = $this->getDateRange($request);

        $topDoctors = Doctor::with('user')
            ->get()
            ->map(function ($d) use ($startDate, $endDate) {
                $consults = Consultation::whereBetween('created_at', [$startDate, $endDate])
                    ->whereHas('visit.appointment', function ($q) use ($d) {
                        $q->where('doctor_id', $d->id);
                    })->count();

                $admissions = Admission::whereBetween('created_at', [$startDate, $endDate])
                    ->where('admitting_doctor_id', $d->id)->count();

                $revenue = Invoice::whereBetween('created_at', [$startDate, $endDate])
                    ->where(function ($query) use ($d) {
                        $query->whereHas('visit.appointment', function ($q) use ($d) {
                            $q->where('doctor_id', $d->id);
                        })->orWhereHas('admission', function ($q) use ($d) {
                            $q->where('admitting_doctor_id', $d->id);
                        });
                    })->sum('total_amount');

                return [
                    'id' => $d->id,
                    'name' => optional($d->user)->name ?? 'Unknown Doctor',
                    'department' => optional($d->department)->name ?? 'N/A',
                    'consults' => $consults,
                    'admissions' => $admissions,
                    'revenue' => $revenue,
                    'score' => $consults + ($admissions * 2)
                ];
            })
            ->sortByDesc('score')
            ->values(); // Reset keys for JSON

        $data = compact('topDoctors', 'startDate', 'endDate');

        if ($request->has('export')) {
            return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\Reports\DoctorPerformanceExport($data), 'doctor-performance.xlsx');
        }

        return view('reports.doctor_performance', $data);
    }

    public function tax(Request $request)
    {
        Gate::authorize('view_financial_reports');
        [$startDate, $endDate] = $this->getDateRange($request);

        $query = Invoice::query()->whereBetween('created_at', [$startDate, $endDate]);

        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('id', $search)
                    ->orWhere('invoice_type', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%');
            });
        }

        // Tax Summary (assuming 10% tax for demo purposes)
        $totalRevenue = Invoice::whereBetween('created_at', [$startDate, $endDate])->sum('total_amount');
        $totalTax = $totalRevenue * 0.10;

        // Monthly Tax Trend
        $groupBy = $request->get('range') == 'year' ? 'DATE_FORMAT(created_at, "%Y-%m")' : 'DATE(created_at)';
        $taxTrend = Invoice::selectRaw("$groupBy as date, SUM(total_amount * 0.10) as tax")
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        if ($request->has('export')) {
            $invoices = $query->latest()->get();
            $data = compact('invoices', 'totalRevenue', 'totalTax', 'taxTrend', 'startDate', 'endDate');
            return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\Reports\TaxExport($data), 'tax-report.xlsx');
        }

        $invoices = $query->latest()->paginate(20);

        return view('reports.tax', compact('invoices', 'totalTax', 'taxTrend', 'startDate', 'endDate'));
    }
}
