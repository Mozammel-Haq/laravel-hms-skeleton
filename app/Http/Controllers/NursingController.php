<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use Illuminate\Support\Facades\Gate;

/**
 * Manages nursing-related functionality.
 *
 * Responsibilities:
 * - Listing admissions for nursing notes
 * - Filtering admissions by status and date
 */
class NursingController extends Controller
{
    /**
     * Display a listing of admissions for nursing notes.
     *
     * Supports filtering by:
     * - Status: 'admitted', 'all', or specific status (Default: 'admitted')
     * - Search: Patient name or code
     * - Date Range: Admission date
     *
     * @return \Illuminate\View\View
     */
    public function notesIndex()
    {
        Gate::authorize('view_nursing_notes');
        $query = Admission::with(['patient']);

        if (request()->filled('status')) {
            if (request('status') !== 'all') {
                $query->where('status', request('status'));
            }
        } else {
            $query->where('status', 'admitted');
        }

        if (request()->filled('search')) {
            $search = request('search');
            $query->whereHas('patient', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('patient_code', 'like', "%{$search}%");
            });
        }

        if (request()->filled('from')) {
            $query->whereDate('admission_date', '>=', request('from'));
        }

        if (request()->filled('to')) {
            $query->whereDate('admission_date', '<=', request('to'));
        }

        $admissions = $query->latest()->paginate(20)->withQueryString();
        return view('nursing.notes.index', compact('admissions'));
    }
}
