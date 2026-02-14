<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    /**
     * Display a listing of inquiries for the current tenant.
     */
    public function index()
    {
        $inquiries = Inquiry::with(['patient', 'user'])
            ->latest()
            ->paginate(10);
            
        return response()->json([
            'status' => 'success',
            'data' => $inquiries
        ]);
    }

    /**
     * Store a new inquiry.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'patient_id' => 'nullable|exists:patients,id',
            'priority' => 'required|in:low,medium,high',
            'source' => 'required|string',
        ]);

        $inquiry = Inquiry::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Inquiry created successfully',
            'data' => $inquiry
        ], 201);
    }

    /**
     * Update an inquiry status.
     */
    public function update(Request $request, Inquiry $inquiry)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,responded,closed',
        ]);

        $inquiry->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Inquiry status updated',
            'data' => $inquiry
        ]);
    }
}
