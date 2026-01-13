<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Consultation;
use App\Models\LabTest;
use App\Models\Medicine;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\InvoicePayment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class BillingController extends Controller
{
    // List all invoices
    public function index()
    {
        Gate::authorize('viewAny', Invoice::class);

        $invoices = Invoice::with('patient')->latest()->paginate(20);
        return view('billing.index', compact('invoices'));
    }

    // Show create invoice form
    public function create()
    {
        Gate::authorize('create', Invoice::class);

        $patients = Patient::orderBy('name')->get();
        return view('billing.create', compact('patients'));
    }

    // Store invoice and items
    public function store(Request $request)
    {
        Gate::authorize('create', Invoice::class);

        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'discount'   => 'nullable|numeric|min:0',
            'tax'        => 'nullable|numeric|min:0',
            'items'      => 'required|array|min:1',
            'items.*.reference_id' => 'required',
            'items.*.item_type'    => 'required|string',
            'items.*.quantity'     => 'required|numeric|min:1',
            'items.*.unit_price'   => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {

            $subtotal = 0;
            foreach ($request->items as $item) {
                $modelClass = match ($item['item_type']) {
                    'Consultation' => Consultation::class,
                    'LabTest'      => LabTest::class,
                    'Medicine'     => Medicine::class,
                    default        => null,
                };
                if (!$modelClass) {
                    abort(422);
                }
                $ref = $modelClass::where('id', $item['reference_id'])->first();
                if (!$ref) {
                    abort(422);
                }
                if (isset($ref->patient_id) && (int)$ref->patient_id !== (int)$request->patient_id) {
                    abort(422);
                }
                if (isset($ref->invoice_id) && !empty($ref->invoice_id)) {
                    abort(422);
                }
                $subtotal += $item['quantity'] * $item['unit_price'];
            }

            $discount = $request->discount ?? 0;
            $tax = $request->tax ?? 0;
            $taxAmount = ($subtotal - $discount) * ($tax / 100);
            $grandTotal = $subtotal - $discount + $taxAmount;

            $invoice = Invoice::create([
                'patient_id' => $request->patient_id,
                'subtotal'   => $subtotal,
                'discount'   => $discount,
                'tax'        => $tax,
                'tax_amount' => $taxAmount,
                'total'      => $grandTotal,
                'status'     => 'unpaid', // default status
            ]);

            // Insert invoice items
            foreach ($request->items as $item) {
                InvoiceItem::create([
                    'invoice_id'   => $invoice->id,
                    'reference_id' => $item['reference_id'],
                    'item_type'    => $item['item_type'],
                    'quantity'     => $item['quantity'],
                    'unit_price'   => $item['unit_price'],
                    'total'        => $item['quantity'] * $item['unit_price'],
                ]);

                // Optionally mark the referenced items as invoiced
                $modelClass = match ($item['item_type']) {
                    'Consultation' => Consultation::class,
                    'LabTest'      => LabTest::class,
                    'Medicine'     => Medicine::class,
                    default        => null,
                };
                if ($modelClass) {
                    $modelClass::where('id', $item['reference_id'])->update(['invoice_id' => $invoice->id]);
                }
            }
        });

        return redirect()->route('billing.index')->with('success', 'Invoice generated successfully.');
    }

    // Fetch pending items for a specific patient (AJAX)
    public function getPatientItems(Patient $patient)
    {
        $consultations = Consultation::where('patient_id', $patient->id)
            ->whereNull('invoice_id')
            ->get(['id', DB::raw("'Consultation' as type"), 'diagnosis as description', 'fee as price']);

        $lab_tests = LabTest::where('patient_id', $patient->id)
            ->whereNull('invoice_id')
            ->get(['id', DB::raw("'LabTest' as type"), 'name as description', 'price']);

        $medicines = Medicine::where('patient_id', $patient->id)
            ->whereNull('invoice_id')
            ->get(['id', DB::raw("'Medicine' as type"), 'name as description', 'price']);

        return response()->json([
            'consultations' => $consultations,
            'lab_tests'     => $lab_tests,
            'medicines'     => $medicines,
        ]);
    }

    // Show payment form for a specific invoice
    public function addPayment(Invoice $invoice)
    {
        Gate::authorize('create', Invoice::class);

        $invoice->load('patient', 'payments');
        return view('billing.payment', compact('invoice'));
    }

    // Store payment for an invoice
    public function storePayment(Request $request, Invoice $invoice)
    {
        Gate::authorize('create', Invoice::class);

        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $invoice->total_remaining,
            'payment_method' => 'required|string|in:cash,card,bank_transfer',
            'note' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request, $invoice) {
            // Create payment record
            $payment = $invoice->payments()->create([
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'note' => $request->note,
            ]);

            // Update invoice status
            $invoice->refresh();
            if ($invoice->total_remaining <= 0) {
                $invoice->status = 'paid';
            } elseif ($invoice->total_remaining < $invoice->total) {
                $invoice->status = 'partial';
            } else {
                $invoice->status = 'unpaid';
            }
            $invoice->save();
        });

        return redirect()->route('billing.show', $invoice)->with('success', 'Payment recorded successfully.');
    }
}
