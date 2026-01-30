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

/**
 * Manages lab test orders, results, and billing integration.
 *
 * Responsibilities:
 * - Order Management: Create, view, delete, restore lab orders.
 * - Result Management: Technicians enter results, upload PDF reports.
 * - Billing Integration: Generate invoices for lab tests.
 * - Notifications: Alert technicians of new orders, alert doctors/patients of results.
 * - Eligibility Checks: Ensure patient is admitted or has a completed consultation.
 */
class LabController extends Controller
{
    /**
     * Display a listing of lab test orders.
     *
     * Supports filtering by:
     * - Status: 'pending', 'completed', 'trashed', 'all'
     * - Search: Patient name/code, Test name, Order ID
     * - Date Range: Creation date (from/to)
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        Gate::authorize('viewAny', LabTestOrder::class);
        $query = LabTestOrder::with(['patient', 'test']);

        // Filter by Status
        if (request('status') === 'trashed') {
            $query->onlyTrashed()->latest();
        } elseif (request()->filled('status')) {
            if (request('status') !== 'all') {
                $query->where('status', request('status'));
            }
            $query->latest();
        } else {
            // Default view (all active orders)
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

    /**
     * Show the form for creating a new lab test order.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        Gate::authorize('create', LabTestOrder::class);
        $patients = collect(); // Use AJAX search

        // Context Data
        $appointmentId = $request->input('appointment_id');
        $doctor = null;
        if (auth()->user()->hasRole('Doctor')) {
            $doctor = auth()->user()->doctor;
        }

        if ($request->has('patient_id') || old('patient_id')) {
            $patientId = $request->input('patient_id') ?? old('patient_id');
            $patient = Patient::find($patientId);
            if ($patient) {
                $patients->push($patient);
            }
        }
        $tests = LabTest::all();
        $doctors = Doctor::with('user')->get();
        return view('lab.create', compact('patients', 'tests', 'doctors', 'doctor', 'appointmentId'));
    }

    /**
     * Store a newly created lab test order.
     *
     * Features:
     * - Validates request data
     * - Auto-assigns doctor if user is a doctor
     * - Enforces eligibility rules (IPD Admitted OR OPD Completed)
     * - Creates LabTestOrder record
     * - Dispatches notifications to Lab Technicians
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        Gate::authorize('create', LabTestOrder::class);

        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'lab_test_id' => 'required|exists:lab_tests,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'appointment_id' => 'nullable|exists:appointments,id',
        ]);

        $data = $request->all();

        // Auto-assign doctor if logged in user is a doctor
        if (auth()->user()->hasRole('Doctor') && empty($data['doctor_id'])) {
            $doctor = auth()->user()->doctor;
            if ($doctor) {
                $data['doctor_id'] = $doctor->id;
            }
        }

        // Eligibility Check (OPD Completed or IPD Admitted)
        $patient = Patient::findOrFail($data['patient_id']);
        $isEligible = false;

        // 1. Check specific appointment context
        if (!empty($data['appointment_id'])) {
            $appt = Appointment::find($data['appointment_id']);
            if ($appt && $appt->patient_id == $patient->id && $appt->status == 'completed') {
                $isEligible = true;
            }
        }

        // 2. Check admission status
        if (!$isEligible) {
            $isAdmitted = \App\Models\Admission::where('patient_id', $patient->id)
                ->where('status', 'admitted')
                ->exists();
            if ($isAdmitted) {
                $isEligible = true;
            }
        }

        // 3. Fallback: Check if patient has ANY completed appointment (OPD)
        // If no specific appointment ID was passed, we link to the latest completed one.
        if (!$isEligible) {
            $latestAppt = $patient->appointments()
                ->where('status', 'completed')
                ->latest()
                ->first();

            if ($latestAppt) {
                $data['appointment_id'] = $latestAppt->id;
                $isEligible = true;
            }
        }

        if (!$isEligible) {
            return back()->with('error', 'Lab orders can only be created for admitted patients or patients with completed consultations.');
        }

        $order = LabTestOrder::create($data + [
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

        return redirect()->route('lab.index')->with('success', 'Lab test order created successfully.');
    }

    /**
     * Generate an invoice for the lab test order.
     *
     * Features:
     * - Checks if invoice already exists
     * - Creates invoice via BillingService
     * - Updates order with invoice ID
     *
     * @param \App\Models\LabTestOrder $order
     * @return \Illuminate\Http\RedirectResponse
     */
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
            finalize: true,
            clinicId: $order->clinic_id
        );

        $order->update(['invoice_id' => $invoice->id]);

        return redirect()->route('lab.show', $order)->with('success', 'Invoice generated successfully.');
    }

    /**
     * Display the specified lab test order details.
     *
     * Loads related data:
     * - Patient profile
     * - Test details
     * - Results history
     * - Invoice status
     *
     * @param \App\Models\LabTestOrder $order
     * @return \Illuminate\View\View
     */
    public function show(LabTestOrder $order)
    {
        Gate::authorize('view', $order);
        $order->load(['patient', 'test', 'results', 'invoice']);
        return view('lab.show', compact('order'));
    }

    /**
     * Show the form to add a result to the lab test order.
     *
     * Check: Invoice must be paid before adding results.
     *
     * @param \App\Models\LabTestOrder $order
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function addResult(LabTestOrder $order)
    {
        Gate::authorize('update', $order);

        if (!$order->invoice || $order->invoice->status !== 'paid') {
            return redirect()->route('lab.show', $order)->with('error', 'Cannot add results. Invoice must be generated and paid first.');
        }

        return view('lab.result', compact('order'));
    }

    /**
     * Store a new result for the lab test order.
     *
     * Features:
     * - Validates result data and file upload (PDF)
     * - Creates LabTestResult record
     * - Updates order status to 'completed'
     * - Notifies Patient and Doctor
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\LabTestOrder $order
     * @return \Illuminate\Http\RedirectResponse
     */
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
            'lab_test_id' => $order->lab_test_id,
            'result_value' => $request->result_value,
            'remarks' => $request->notes,
            'reported_at' => now(),
            'reported_by' => auth()->id(),
            'pdf_path' => $pdfPath,
        ]);

        $order->update(['status' => 'completed']);

        // Notify Patient
        if ($order->patient) {
            $order->patient->notify(new \App\Notifications\LabResultReadyNotification($order));
        }

        // Notify Doctor
        if ($order->doctor && $order->doctor->user) {
            $order->doctor->user->notify(new \App\Notifications\LabResultReadyNotification($order));
        }

        return redirect()->route('lab.show', $order)->with('success', 'Result recorded successfully.');
    }

    /**
     * Soft delete the specified lab test order.
     *
     * @param \App\Models\LabTestOrder $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(LabTestOrder $order)
    {
        Gate::authorize('delete', $order);
        $order->delete();
        return redirect()->route('lab.index')->with('success', 'Lab test order deleted successfully.');
    }

    /**
     * Restore a soft-deleted lab test order.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore($id)
    {
        $order = LabTestOrder::withTrashed()->findOrFail($id);
        Gate::authorize('delete', $order);
        $order->restore();
        return redirect()->route('lab.index')->with('success', 'Lab test order restored successfully.');
    }
}
