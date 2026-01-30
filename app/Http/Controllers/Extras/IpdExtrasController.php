<?php

namespace App\Http\Controllers\Extras;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use App\Models\Ward;
use Illuminate\Support\Facades\Gate;

/**
 * Handles additional IPD (In-Patient Department) functionalities.
 *
 * Responsibilities:
 * - Managing rounds listing
 * - Viewing bed status overview
 */
class IpdExtrasController extends Controller
{
    /**
     * Display a list of current admissions for rounds.
     *
     * Supports filtering by:
     * - Search: Patient name, code, or Doctor name
     * - Date Range: Admission creation date
     *
     * @return \Illuminate\View\View
     */
    public function rounds()
    {
        Gate::authorize('view_ipd');
        $query = Admission::with(['patient', 'doctor'])->where('status', 'admitted');

        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('patient', function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('patient_code', 'like', "%{$search}%");
                })->orWhereHas('doctor.user', function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%");
                });
            });
        }

        if (request()->filled('from')) {
            $query->whereDate('created_at', '>=', request('from'));
        }

        if (request()->filled('to')) {
            $query->whereDate('created_at', '<=', request('to'));
        }

        $admissions = $query->latest()->paginate(20)->withQueryString();
        return view('ipd.rounds.index', compact('admissions'));
    }

    /**
     * Display the current status of all beds across wards.
     *
     * @return \Illuminate\View\View
     */
    public function bedStatus()
    {
        Gate::authorize('view_ipd');
        $wards = Ward::with(['rooms.beds'])->orderBy('name')->get();
        return view('ipd.bed_status', compact('wards'));
    }
}
