<?php

namespace App\Http\Controllers;

use App\Models\LabTest;
use App\Models\LabTestOrder;
use App\Models\LabTestResult;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class LabController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', LabTestOrder::class);
        $orders = LabTestOrder::with(['patient', 'test'])->latest()->paginate(20);
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
            'clinic_id' => \App\Support\TenantContext::getClinicId() ?? auth()->user()->clinic_id,
            'status' => 'pending',
            'ordered_at' => now(),
        ]);

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
        ]);

        LabTestResult::create([
            'clinic_id' => $order->clinic_id,
            'lab_test_order_id' => $order->id,
            'result_value' => $request->result_value,
            'notes' => $request->notes,
            'recorded_at' => now(),
            'recorded_by' => auth()->id(),
        ]);

        $order->update(['status' => 'completed']);

        return redirect()->route('lab.show', $order)->with('success', 'Result recorded successfully.');
    }
}
