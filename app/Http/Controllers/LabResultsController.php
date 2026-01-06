<?php

namespace App\Http\Controllers;

use App\Models\LabTestResult;

class LabResultsController extends Controller
{
    public function index()
    {
        $results = LabTestResult::with(['order.patient', 'order.test'])
            ->latest('recorded_at')
            ->paginate(20);
        return view('lab.results.index', compact('results'));
    }
}
