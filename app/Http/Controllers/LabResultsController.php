<?php

namespace App\Http\Controllers;

use App\Models\LabTestResult;
use Illuminate\Support\Facades\Storage;

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

        if (request()->filled('status')) {
            if (request('status') !== 'all') {
                $query->whereHas('order', function ($q) {
                    $q->where('status', request('status'));
                });
            }
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

    public function download(LabTestResult $result)
    {
        if (!$result->pdf_path || !Storage::disk('public')->exists($result->pdf_path)) {
            return back()->with('error', 'No result file attached.');
        }

        return Storage::disk('public')->download($result->pdf_path);
    }

    public function viewFile(LabTestResult $result)
    {
        if (!$result->pdf_path || !Storage::disk('public')->exists($result->pdf_path)) {
            return back()->with('error', 'No result file attached.');
        }

        return response()->file(Storage::disk('public')->path($result->pdf_path));
    }
}
