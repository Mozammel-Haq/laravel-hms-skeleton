<?php

namespace App\Http\Controllers\Extras;

use App\Http\Controllers\Controller;
use App\Models\Admission;

/**
 * Manages nursing notes for admitted patients.
 *
 * Responsibilities:
 * - Listing admissions for nursing notes
 * - Filtering admissions by status, patient name, and date
 */
class NursingNotesController extends Controller
{
    /**
     * Display a listing of admissions for nursing notes.
     *
     * Supports filtering by:
     * - Search: Patient first name, last name, or ID
     * - Status: 'admitted', 'all', or specific status (Default: 'admitted')
     * - Date Range: Admission creation date
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $query = Admission::with(['patient']);

        if (request()->filled('search')) {
            $search = request('search');
            $query->whereHas('patient', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('patient_id', 'like', "%{$search}%");
            });
        }

        if (request()->filled('status') && request('status') !== 'all') {
            $query->where('status', request('status'));
        } elseif (!request()->filled('status')) {
            $query->where('status', 'admitted');
        }

        if (request()->filled('from')) {
            $query->whereDate('created_at', '>=', request('from'));
        }

        if (request()->filled('to')) {
            $query->whereDate('created_at', '<=', request('to'));
        }

        $admissions = $query->latest()->paginate(10)->withQueryString();
        return view('nursing.notes.index', compact('admissions'));
    }
}
