<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Consultation;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\LabTestOrder;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\PharmacySale;
use App\Notifications\PaymentReceivedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * Manages invoice generation, viewing, and payments.
 *
 * Responsibilities:
 * - Invoice lifecycle management (create, view, delete, restore)
 * - Payment processing for invoices
 * - Integration with patient billable items (consultations, lab, medicines)
 * - Automatic status updates (unpaid -> partial -> paid)
 * - Financial reporting (implied via index filtering)
 */
class BillingController extends Controller
{
    /**
     * Display a listing of invoices.
     *
     * Supports filtering by:
     * - Status: 'unpaid', 'paid', 'partial', 'trashed', 'all'
     * - Search: Invoice number, Patient name/code
     * - Date Range: Issued at (from/to)
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        Gate::authorize('viewAny', Invoice::class);

        $query = Invoice::with('patient');

        if (request('status') === 'trashed') {
            $query->onlyTrashed()->latest();
        } else {
            if (request()->filled('status')) {
                if (request('status') !== 'all') {
                    $query->where('status', request('status'));
                }
            }
            $query->latest();
        }

        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', '%'.$search.'%')
                    ->orWhereHas('patient', function ($sub) use ($search) {
                        $sub->where('name', 'like', '%'.$search.'%')
                            ->orWhere('patient_code', 'like', '%'.$search.'%');
                    });
            });
        }

        if (request()->filled('from')) {
            $query->whereDate('issued_at', '>=', request('from'));
        }

        if (request()->filled('to')) {
            $query->whereDate('issued_at', '<=', request('to'));
        }

        $invoices = $query->paginate(20)->withQueryString();

        return view('billing.index', compact('invoices'));
    }

    /**
     * Soft delete the specified invoice.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Invoice $invoice)
    {
        Gate::authorize('delete', $invoice);
        $invoice->delete();

        return redirect()->route('billing.index')->with('success', 'Invoice deleted successfully.');
    }

    /**
     * Display the specified invoice details.
     *
     * Loads related data:
     * - Patient profile
     * - Invoice items (services, meds, etc.)
     * - Payment history
     *
     * @return \Illuminate\View\View
     */
    public function show(Invoice $invoice)
    {
        Gate::authorize('view', $invoice);
        $invoice->load(['patient', 'items', 'payments']);

        return view('billing.show', compact('invoice'));
    }

    /**
     * Restore a soft-deleted invoice.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore($id)
    {
        $invoice = Invoice::withTrashed()->findOrFail($id);
        Gate::authorize('delete', $invoice);
        $invoice->restore();

        return redirect()->route('billing.index')->with('success', 'Invoice restored successfully.');
    }

    /**
     * Show the form for creating a new invoice.
     *
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        Gate::authorize('create', Invoice::class);
        $patients = collect();
        if ($request->has('patient_id') || old('patient_id')) {
            $patientId = $request->input('patient_id') ?? old('patient_id');
            $patient = Patient::find($patientId);
            if ($patient) {
                $patients->push($patient);
            }
        }

        return view('billing.create', compact('patients'));
    }

    /**
     * Store a newly created invoice in storage.
     *
     * Features:
     * - Validates items and prices
     * - Calculates totals, tax, and discount
     * - Creates Invoice record
     * - Creates InvoiceItem records
     * - Updates source records (Consultation, LabTest, etc.) with invoice_id
     * - Wraps operations in a database transaction
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Invoice::class);

        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.reference_id' => 'required',
            'items.*.item_type' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {

            $subtotal = 0;
            foreach ($request->items as $item) {
                $modelClass = match ($item['item_type']) {
                    'consultation' => Consultation::class,
                    'lab_order' => LabTestOrder::class,
                    'pharmacy_sale' => PharmacySale::class,
                    default => null,
                };
                if (! $modelClass) {
                    throw ValidationException::withMessages(['items' => "Invalid item type: {$item['item_type']}"]);
                }
                $ref = $modelClass::where('id', $item['reference_id'])->first();
                if (! $ref) {
                    $desc = $item['description'] ?? 'Unknown Item';
                    throw ValidationException::withMessages(['items' => "Item not found: {$desc}"]);
                }
                if (isset($ref->patient_id) && (int) $ref->patient_id !== (int) $request->patient_id) {
                    $desc = $item['description'] ?? 'Unknown Item';
                    throw ValidationException::withMessages(['items' => "Item does not belong to this patient: {$desc}"]);
                }

                // Check if already invoiced
                if ($item['item_type'] === 'lab_order' && ! empty($ref->invoice_id)) {
                    $desc = $item['description'] ?? 'Unknown Item';
                    throw ValidationException::withMessages(['items' => "Item already invoiced: {$desc}"]);
                }
                if ($item['item_type'] === 'consultation' && $ref->invoiceItem()->exists()) {
                    $desc = $item['description'] ?? 'Unknown Item';
                    throw ValidationException::withMessages(['items' => "Item already invoiced: {$desc}"]);
                }

                $subtotal += $item['quantity'] * $item['unit_price'];
            }

            $discount = $request->discount ?? 0;
            $tax = $request->tax ?? 0;
            $taxAmount = ($subtotal - $discount) * ($tax / 100);
            $grandTotal = $subtotal - $discount + $taxAmount;

            $invoice = Invoice::create([
                'invoice_number' => 'INV-'.date('Ymd').'-'.strtoupper(Str::random(6)),
                'patient_id' => $request->patient_id,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'total_amount' => $grandTotal,
                'status' => 'unpaid',
            ]);

            // Insert invoice items
            foreach ($request->items as $item) {
                // Map frontend item types to database ENUM values
                $dbItemType = match ($item['item_type']) {
                    'lab_order' => 'lab',
                    'pharmacy_sale' => 'medicine',
                    default => $item['item_type'],
                };

                // Fetch description
                $description = 'Item';
                if ($item['item_type'] === 'lab_order') {
                    $ref = LabTestOrder::with('test')->find($item['reference_id']);
                    $description = $ref && $ref->test ? $ref->test->name : 'Lab Test';
                } elseif ($item['item_type'] === 'consultation') {
                    $ref = Consultation::with('doctor.user')->find($item['reference_id']);
                    $description = 'Consultation';
                    if ($ref && $ref->doctor && $ref->doctor->user) {
                        $description .= ' - '.$ref->doctor->user->name;
                    }
                } elseif ($item['item_type'] === 'pharmacy_sale') {
                    $description = 'Medicines';
                }

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'reference_id' => $item['reference_id'],
                    'item_type' => $dbItemType,
                    'description' => $description,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['quantity'] * $item['unit_price'],
                ]);

                // Optionally mark the referenced items as invoiced
                if ($item['item_type'] === 'lab_order') {
                    LabTestOrder::where('id', $item['reference_id'])->update(['invoice_id' => $invoice->id]);
                }
            }
        });

        return redirect()->route('billing.index')->with('success', 'Invoice generated successfully.');
    }

    /**
     * Fetch pending billable items for a specific patient via AJAX.
     *
     * Retrieves all unbilled items for a patient including:
     * - Consultations (not yet invoiced)
     * - Lab Tests (not yet invoiced)
     * - Medicines (not yet invoiced)
     *
     * The response is formatted for easy consumption by the frontend billing UI.
     * Each item includes: id, type, description, and price.
     *
     * @param  \App\Models\Patient  $patient  The patient to fetch items for.
     * @return \Illuminate\Http\JsonResponse JSON response containing grouped billable items.
     */
    public function getPatientItems(Patient $patient)
    {
        $consultations = Consultation::where('patient_id', $patient->id)
            ->whereDoesntHave('invoiceItem')
            ->with('doctor')
            ->get()
            ->map(function ($c) {
                return [
                    'id' => $c->id,
                    'type' => 'consultation',
                    'description' => 'Consultation: '.($c->diagnosis ?? 'General Checkup'),
                    'price' => $c->doctor->consultation_fee ?? 0,
                ];
            });

        $lab_tests = LabTestOrder::where('patient_id', $patient->id)
            ->whereNull('invoice_id')
            ->with('test')
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'type' => 'lab_order',
                    'description' => $order->test->name ?? 'Unknown Test',
                    'price' => $order->test->price ?? 0,
                ];
            });

        // Medicines logic temporarily disabled due to schema mismatch (PharmacySale vs Medicine Catalog)
        $medicines = [];

        return response()->json([
            'consultations' => $consultations,
            'lab_tests' => $lab_tests,
            'medicines' => $medicines,
        ]);
    }

    /**
     * Download/Print the invoice for the patient.
     *
     * @return \Illuminate\View\View
     */
    public function patientPrint(Request $request, Invoice $invoice)
    {
        if (! $request->hasValidSignature()) {
            abort(403, 'Invalid or expired link.');
        }

        $invoice->load(['patient', 'items', 'payments']);

        return view('billing.patient-print', compact('invoice'));
    }

    /**
     * Show the payment form for a specific invoice.
     *
     * @return \Illuminate\View\View
     */
    public function addPayment(Invoice $invoice)
    {
        Gate::authorize('create', Invoice::class);

        $invoice->load('patient', 'payments');

        return view('billing.payment', compact('invoice'));
    }

    /**
     * Store a payment for an invoice and update invoice status.
     *
     * Features:
     * - Validates payment amount (cannot exceed remaining balance)
     * - Records payment transaction
     * - Updates invoice status (unpaid -> partial -> paid)
     * - Automatically confirms related appointment if invoice is fully paid
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storePayment(Request $request, Invoice $invoice)
    {
        Gate::authorize('create', Invoice::class);

        $invoiceTotal = $invoice->total_amount ?? $invoice->total ?? 0;
        $alreadyPaid = $invoice->payments()->where('status', 'success')->sum('amount');
        $remaining = max($invoiceTotal - $alreadyPaid, 0);

        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:'.$remaining,
            'payment_method' => 'required|string|in:cash,card,bank_transfer',
        ]);

        DB::transaction(function () use ($request, $invoice) {
            $payment = $invoice->payments()->create([
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'paid_at' => now(),
                'received_by' => auth()->id(),
                'status' => 'success',
            ]);

            $invoice->refresh();

            $invoiceTotal = $invoice->total_amount ?? $invoice->total ?? 0;
            // Only sum successful payments
            $totalPaid = $invoice->payments()->where('status', 'success')->sum('amount');
            $remaining = max($invoiceTotal - $totalPaid, 0);

            if ($remaining <= 0) {
                $invoice->status = 'paid';
            } elseif ($remaining < $invoiceTotal) {
                $invoice->status = 'partial';
            } else {
                $invoice->status = 'unpaid';
            }

            $invoice->save();

            if ($invoice->status === 'paid' && $invoice->invoice_type === 'consultation' && $invoice->appointment_id) {
                $appointment = Appointment::find($invoice->appointment_id);
                if ($appointment && in_array($appointment->status, ['pending', 'arrived'], true)) {
                    $today = now()->toDateString();
                    $nowTime = now()->format('H:i:s');
                    $appointmentDate = $appointment->appointment_date ? $appointment->appointment_date->toDateString() : null;
                    $isExpired = $appointmentDate
                        && ($appointmentDate < $today || ($appointmentDate === $today && $appointment->end_time && $appointment->end_time <= $nowTime));

                    if (! $isExpired) {
                        $appointment->update(['status' => 'confirmed']);
                    }
                }
            }

            // Notify Patient
            if ($invoice->patient) {
                $invoice->patient->notify(new PaymentReceivedNotification($payment, $invoice));
            }
        });

        if (auth()->user()->hasRole('Receptionist') && $invoice->appointment_id) {
            return redirect()->route('appointments.show', $invoice->appointment_id)->with('success', 'Payment recorded successfully.');
        }

        return redirect()->route('billing.show', $invoice)->with('success', 'Payment recorded successfully.');
    }
}
