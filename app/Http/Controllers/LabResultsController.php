<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\LabTestResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * Manages the display and download of lab test results.
 */
class LabResultsController extends Controller
{
    /**
     * Display a listing of lab test results.
     * Supports filtering by search term, status (via order), and date range.
     *
     * @return \Illuminate\View\View
     */
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

    /**
     * Download the result PDF (for authorized staff).
     *
     * @param \App\Models\LabTestResult $result
     * @return \Symfony\Component\HttpFoundation\StreamedResponse|\Illuminate\Http\RedirectResponse
     */
    public function download(LabTestResult $result)
    {
        if (!$result->pdf_path || !Storage::disk('public')->exists($result->pdf_path)) {
            return back()->with('error', 'No result file attached.');
        }

        return Storage::disk('public')->download($result->pdf_path);
    }

    /**
     * View the result PDF in browser (for authorized staff).
     *
     * @param \App\Models\LabTestResult $result
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\RedirectResponse
     */
    public function viewFile(LabTestResult $result)
    {
        if (!$result->pdf_path || !Storage::disk('public')->exists($result->pdf_path)) {
            return back()->with('error', 'No result file attached.');
        }

        return response()->file(Storage::disk('public')->path($result->pdf_path));
    }

    /**
     * Download the result PDF via signed link (for patients).
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\LabTestResult $result
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function patientDownload(Request $request, LabTestResult $result)
    {
        if (! $request->hasValidSignature()) {
            abort(403, 'Invalid or expired link.');
        }

        if (!$result->pdf_path || !Storage::disk('public')->exists($result->pdf_path)) {
            abort(404);
        }

        ActivityLog::create([
            'user_id' => Auth::id(), // Likely null for public link
            'clinic_id' => $result->clinic_id,
            'action' => 'download',
            'description' => 'Patient downloaded lab result via signed link',
            'entity_type' => LabTestResult::class,
            'entity_id' => $result->id,
            'ip_address' => $request->ip(),
        ]);

        return Storage::disk('public')->download($result->pdf_path);
    }
}
