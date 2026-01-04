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

        $revenue = Invoice::whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_amount');

        $paid = Invoice::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'paid')
            ->sum('total_amount');

        return view('reports.financial', compact('revenue', 'paid', 'startDate', 'endDate'));
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
}
