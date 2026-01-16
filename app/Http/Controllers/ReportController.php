<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\Invoice;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ReportController extends Controller
{
    public function index()
    {
        Gate::authorize('view_reports');

        return view('reports.index');
    }

    public function financial(Request $request)
    {
        Gate::authorize('view_financial_reports');

        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());

        $query = Invoice::whereBetween('created_at', [$startDate, $endDate]);
        $revenue = (clone $query)->sum('total_amount');

        $paid = (clone $query)->where('status', 'paid')->sum('total_amount');

        $byType = (clone $query)
            ->selectRaw('invoice_type, SUM(total_amount) as total')
            ->groupBy('invoice_type')
            ->pluck('total', 'invoice_type');

        $daily = Invoice::selectRaw('DATE(created_at) as day, SUM(total_amount) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        return view('reports.financial', compact('revenue', 'paid', 'startDate', 'endDate', 'byType', 'daily'));
    }

    public function compare(Request $request)
    {
        if (!auth()->user()->hasRole('Super Admin')) {
            abort(403, 'Only Super Admin can compare clinics.');
        }

        $clinicIds = $request->input('clinics', []);

        if (empty($clinicIds)) {
            return redirect()->back()->with('error', 'Please select at least one clinic to compare.');
        }

        $clinics = Clinic::whereIn('id', $clinicIds)->get();

        // Basic Stats for each clinic
        $stats = [];
        foreach ($clinics as $clinic) {
            $stats[$clinic->id] = [
                'patients' => Patient::withoutGlobalScope('clinic')->where('clinic_id', $clinic->id)->count(),
                'appointments' => Appointment::withoutGlobalScope('clinic')->where('clinic_id', $clinic->id)->count(),
                'revenue' => Invoice::withoutGlobalScope('clinic')->where('clinic_id', $clinic->id)->sum('total_amount'),
            ];
        }

        return view('reports.compare', compact('clinics', 'stats'));
    }

    public function patientDemographics()
    {
        Gate::authorize('view_reports');

        $genderStats = Patient::select('gender', DB::raw('count(*) as total'))
            ->groupBy('gender')
            ->get();

        return view('reports.demographics', compact('genderStats'));
    }

    public function summary()
    {
        Gate::authorize('view_reports');
        $patientsTotal = Patient::count();
        $admissionsToday = \App\Models\Admission::whereDate('created_at', now()->toDateString())->count();
        $invoicesTotal = \App\Models\Invoice::count();
        $paymentsTotal = \App\Models\Payment::sum('amount');
        $admissions = \App\Models\Admission::with(['patient', 'doctor'])->latest()->take(10)->get();
        $visitTotals = \App\Models\Visit::with('appointment.patient')
            ->latest()
            ->take(20)
            ->get()
            ->map(function ($v) {
                $sum = \App\Models\Invoice::where('visit_id', $v->id)->sum('total_amount');
                return ['visit' => $v, 'total' => $sum];
            });
        return view('reports.summary', compact('patientsTotal', 'admissionsToday', 'invoicesTotal', 'paymentsTotal', 'admissions', 'visitTotals'));
    }

    public function doctorPerformance()
    {
        Gate::authorize('view_reports');
        $topDoctors = \App\Models\Doctor::with('user')
            ->get()
            ->map(function ($d) {
                $consults = \App\Models\Consultation::whereHas('visit.appointment', function ($q) use ($d) {
                    $q->where('doctor_id', $d->id);
                })->count();
                $admissions = \App\Models\Admission::where('admitting_doctor_id', $d->id)->count();
                return ['doctor' => $d, 'consults' => $consults, 'admissions' => $admissions, 'score' => $consults + ($admissions * 2)];
            })
            ->sortByDesc('score')
            ->take(10);
        return view('reports.doctor_performance', compact('topDoctors'));
    }

    public function tax()
    {
        Gate::authorize('view_financial_reports');
        $invoices = \App\Models\Invoice::latest()->take(50)->get()->map(function ($inv) {
            $subtotal = $inv->total ?? 0;
            $vat = $subtotal * 0.10;
            $total = $subtotal + $vat;
            return [
                'invoice' => $inv,
                'subtotal' => $subtotal,
                'vat' => $vat,
                'total' => $total,
            ];
        });
        return view('reports.tax', compact('invoices'));
    }
}
