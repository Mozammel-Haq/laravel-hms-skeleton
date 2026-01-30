<?php

namespace App\Http\Controllers;

use App\Models\BedAssignment;

/**
 * BedAssignmentController
 *
 * Manages the assignment of patients to beds (Admissions).
 * Tracks active and released assignments.
 */
class BedAssignmentController extends Controller
{
    /**
     * Display a listing of bed assignments.
     *
     * Supports filtering by:
     * - Status: 'active' (occupied), 'released'
     * - Search: Patient name, code
     * - Date Range: Assignment date
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $query = BedAssignment::with(['bed', 'admission.patient']);

        if (request()->filled('search')) {
            $search = request('search');
            $query->whereHas('admission.patient', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('patient_code', 'like', "%{$search}%");
            });
        }

        if (request()->filled('status')) {
            if (request('status') === 'active') {
                $query->whereNull('released_at');
            } elseif (request('status') === 'released') {
                $query->whereNotNull('released_at');
            }
        }

        if (request()->filled('from')) {
            $query->whereDate('assigned_at', '>=', request('from'));
        }

        if (request()->filled('to')) {
            $query->whereDate('assigned_at', '<=', request('to'));
        }

        $assignments = $query->latest('assigned_at')->paginate(20)->withQueryString();
        return view('ipd.bed_assignments.index', compact('assignments'));
    }

    /**
     * Display the specified bed assignment details.
     *
     * @param  \App\Models\BedAssignment  $bedAssignment
     * @return \Illuminate\View\View
     */
    public function show(BedAssignment $bedAssignment)
    {
        $bedAssignment->load(['bed', 'admission.patient']);
        return view('ipd.bed_assignments.show', ['assignment' => $bedAssignment]);
    }
}
