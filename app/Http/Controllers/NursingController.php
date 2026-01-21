<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use Illuminate\Support\Facades\Gate;

class NursingController extends Controller
{
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
