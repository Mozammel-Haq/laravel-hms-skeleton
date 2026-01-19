<?php

namespace App\Http\Controllers;

use App\Models\LabTest;
use App\Models\LabTestOrder;
use App\Models\LabTestResult;
use App\Models\Patient;
use App\Models\Visit;
use App\Services\BillingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class LabController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', LabTestOrder::class);
        $query = LabTestOrder::with(['patient', 'test']);

        if (request('status') === 'trashed') {
            $query->onlyTrashed();
        } else {
            $query->latest();
        }

        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('id', $search)
                    ->orWhere('status', 'like', '%' . $search . '%')
                    ->orWhereHas('patient', function ($sub) use ($search) {
                        $sub->where('name', 'like', '%' . $search . '%')
                            ->orWhere('patient_code', 'like', '%' . $search . '%');
                    })->orWhereHas('test', function ($sub) use ($search) {
                        $sub->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        if (request()->filled('from')) {
            $query->whereDate('created_at', '>=', request('from'));
        }

        if (request()->filled('to')) {
            $query->whereDate('created_at', '<=', request('to'));
        }

        $orders = $query->paginate(20);
        return view('lab.index', compact('orders'));
    }

    public function create()
    {
        Gate::authorize('create', LabTestOrder::class);
        $patients = Patient::all();
        $tests = LabTest::all();
        return view('lab.create', compact('patients', 'tests'));
    }

    public function store(Request $request)
    {
        Gate::authorize('create', LabTestOrder::class);

        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'lab_test_id' => 'required|exists:lab_tests,id',
            'doctor_id' => 'nullable|exists:doctors,id',
        ]);

        $order = LabTestOrder::create($request->all() + [
            'clinic_id' => auth()->user()->clinic_id,
            'status' => 'pending',
            'ordered_at' => now(),
        ]);

        // Create Lab invoice under the visit (if exists) using test price
        $test = LabTest::find($request->lab_test_id);
        $visit = Visit::where('appointment_id', $order->appointment_id)->latest()->first();
        $items = [[
            'item_type' => 'lab',
            'reference_id' => $test?->id,
            'description' => $test?->name ?? 'Lab Test',
            'quantity' => 1,
            'unit_price' => (float)($test?->price ?? 0),
        ]];
        app(BillingService::class)->createInvoice(
            $order->patient,
            $items,
            $order->appointment_id,
            discount: 0,
            tax: 0,
            visitId: optional($visit)->id,
            invoiceType: 'lab',
            createdBy: auth()->id(),
            finalize: true
        );

        return redirect()->route('lab.show', $order)->with('success', 'Lab test ordered successfully.');
    }

    public function show(LabTestOrder $order)
    {
        Gate::authorize('view', $order);
        $order->load(['patient', 'test', 'results']);
        return view('lab.show', compact('order'));
    }

    public function addResult(LabTestOrder $order)
    {
        Gate::authorize('update', $order);
        return view('lab.result', compact('order'));
    }

    public function storeResult(Request $request, LabTestOrder $order)
    {
        Gate::authorize('update', $order);

        $request->validate([
            'result_value' => 'required|string',
            'notes' => 'nullable|string',
            'report_pdf' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        $pdfPath = null;
        if ($request->hasFile('report_pdf')) {
            $pdfPath = $request->file('report_pdf')->store('lab_results', 'public');
        }

        LabTestResult::create([
            'clinic_id' => $order->clinic_id,
            'lab_test_order_id' => $order->id,
            'result_value' => $request->result_value,
            'notes' => $request->notes,
            'recorded_at' => now(),
            'recorded_by' => auth()->id(),
            'pdf_path' => $pdfPath,
        ]);

        $order->update(['status' => 'completed']);

        return redirect()->route('lab.show', $order)->with('success', 'Result recorded successfully.');
    }

    public function destroy(LabTestOrder $order)
    {
        Gate::authorize('delete', $order);
        $order->delete();
        return redirect()->route('lab.index')->with('success', 'Lab test order deleted successfully.');
    }

    public function restore($id)
    {
        $order = LabTestOrder::withTrashed()->findOrFail($id);
        Gate::authorize('delete', $order);
        $order->restore();
        return redirect()->route('lab.index')->with('success', 'Lab test order restored successfully.');
    }
}
