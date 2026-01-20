<?php

namespace App\Http\Controllers;

use App\Models\LabTestResult;

class LabResultsController extends Controller
{
    public function index()
    {
        $query = LabTestResult::with(['order.patient', 'order.test']);

        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('order.patient', function ($sub) use ($search) {
                    $sub->where('name', 'like', '%' . $search . '%')
                        ->orWhere('patient_code', 'like', '%' . $search . '%');
                })->orWhereHas('order.test', function ($sub) use ($search) {
                    $sub->where('name', 'like', '%' . $search . '%');
                })->orWhere('result_value', 'like', '%' . $search . '%');
            });
        }

        if (request()->filled('from')) {
            $query->whereDate('reported_at', '>=', request('from'));
        }

        if (request()->filled('to')) {
            $query->whereDate('reported_at', '<=', request('to'));
        }

        $results = $query->latest('reported_at')->paginate(20)->withQueryString();

        return view('lab.results.index', compact('results'));
    }
}
