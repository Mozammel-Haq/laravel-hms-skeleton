<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use Illuminate\Support\Facades\Gate;

class NursingController extends Controller
{
    public function notesIndex()
    {
        Gate::authorize('view_nursing_notes');
        $admissions = Admission::with(['patient'])
            ->where('status', 'admitted')
            ->latest()
            ->paginate(20);
        return view('nursing.notes.index', compact('admissions'));
    }
}
