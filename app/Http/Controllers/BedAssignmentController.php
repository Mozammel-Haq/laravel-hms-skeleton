<?php

namespace App\Http\Controllers;

use App\Models\BedAssignment;

class BedAssignmentController extends Controller
{
    public function index()
    {
        $assignments = BedAssignment::with(['bed','admission.patient'])
            ->latest('assigned_at')
            ->paginate(20);
        return view('ipd.bed_assignments.index', compact('assignments'));
    }

    public function show(BedAssignment $bedAssignment)
    {
        $bedAssignment->load(['bed','admission.patient']);
        return view('ipd.bed_assignments.show', ['assignment' => $bedAssignment]);
    }
}
