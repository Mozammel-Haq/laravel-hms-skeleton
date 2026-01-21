<?php

namespace App\Http\Controllers\Extras;

use App\Http\Controllers\Controller;
use App\Models\Admission;

class NursingNotesController extends Controller
{
    public function index()
    {
        $query = Admission::with(['patient']);

        if (request()->filled('search')) {
            $search = request('search');
            $query->whereHas('patient', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
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
