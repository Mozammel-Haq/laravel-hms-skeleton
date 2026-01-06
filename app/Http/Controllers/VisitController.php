<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use Illuminate\Http\Request;

class VisitController extends Controller
{
    public function index()
    {
        $visits = Visit::with(['appointment.patient', 'consultation'])->latest()->paginate(20);
        return view('visits.index', compact('visits'));
    }

    public function show(Visit $visit)
    {
        $visit->load(['appointment.patient', 'consultation']);
        return view('visits.show', compact('visit'));
    }
}
