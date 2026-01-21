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

use App\Models\Doctor;

class LabController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', LabTestOrder::class);
        $query = LabTestOrder::with(['patient', 'test']);

        if (request('status') === 'trashed') {
            $query->onlyTrashed()->latest();
        } elseif (request()->filled('status')) {
            if (request('status') !== 'all') {
                $query->where('status', request('status'));
            }
            $query->latest();
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

        $orders = $query->paginate(20)->withQueryString();
        return view('lab.index', compact('orders'));
    }

    public function create(Request $request)
    {
        Gate::authorize('create', LabTestOrder::class);
        $patients = collect(); // Use AJAX search
        if ($request->has('patient_id') || old('patient_id')) {
            $patientId = $request->input('patient_id') ?? old('patient_id');
            $patient = Patient::find($patientId);
            if ($patient) {
                $patients->push($patient);
            }
        }
        $tests = LabTest::all();
        $doctors = Doctor::with('user')->get();
        return view('lab.create', compact('patients', 'tests', 'doctors'));
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
            'order_date' => now(),
        ]);

        // Notify Lab Technicians
        $labTechs = \App\Models\User::whereHas('roles', function ($q) {
            $q->where('name', 'Lab Technician');
        })->where('clinic_id', auth()->user()->clinic_id)->get();

        foreach ($labTechs as $tech) {
            $tech->notify(new \App\Notifications\NewLabOrderNotification($order));
        }

        return redirect()->route('lab.index')->with('success', 'Lab test ordered successfully.');
    }

    public function generateInvoice(LabTestOrder $order)
    {
        Gate::authorize('update', $order);

        if ($order->invoice_id) {
            return back()->with('error', 'Invoice already exists for this order.');
        }

        $test = $order->test;
        $items = [[
            'item_type' => 'lab',
            'reference_id' => $test?->id,
            'description' => $test?->name ?? 'Lab Test',
            'quantity' => 1,
            'unit_price' => (float)($test?->price ?? 0),
        ]];

        $invoice = app(BillingService::class)->createInvoice(
            $order->patient,
            $items,
            $order->appointment_id,
            discount: 0,
            tax: 0,
            visitId: null, // Could fetch visit if needed
            invoiceType: 'lab',
            createdBy: auth()->id(),
            finalize: true
        );

        $order->update(['invoice_id' => $invoice->id]);

        return redirect()->route('lab.show', $order)->with('success', 'Invoice generated successfully.');
    }

    public function show(LabTestOrder $order)
    {
        Gate::authorize('view', $order);
        $order->load(['patient', 'test', 'results', 'invoice']);
        return view('lab.show', compact('order'));
    }

    public function addResult(LabTestOrder $order)
    {
        Gate::authorize('update', $order);

        if (!$order->invoice || $order->invoice->status !== 'paid') {
            return redirect()->route('lab.show', $order)->with('error', 'Cannot add results. Invoice must be generated and paid first.');
        }

        return view('lab.result', compact('order'));
    }

    public function storeResult(Request $request, LabTestOrder $order)
    {
        Gate::authorize('update', $order);

        if (!$order->invoice || $order->invoice->status !== 'paid') {
            return redirect()->route('lab.show', $order)->with('error', 'Cannot add results. Invoice must be generated and paid first.');
        }

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
            'reported_at' => now(),
            'reported_by' => auth()->id(),
            'pdf_path' => $pdfPath,
        ]);

        $order->update(['status' => 'completed']);

        // Notify Patient
        if ($order->patient && $order->patient->user) {
            $order->patient->user->notify(new LabResultReadyNotification($order));
        }

        // Notify Doctor
        if ($order->doctor && $order->doctor->user) {
            $order->doctor->user->notify(new LabResultReadyNotification($order));
        }

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
