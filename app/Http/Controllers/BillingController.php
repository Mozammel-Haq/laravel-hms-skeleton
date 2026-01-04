<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Patient;
use App\Services\BillingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BillingController extends Controller
{
    protected $billingService;

    public function __construct(BillingService $billingService)
    {
        $this->billingService = $billingService;
    }

    public function index()
    {
        Gate::authorize('viewAny', Invoice::class);
        $invoices = Invoice::with(['patient', 'payments'])->latest()->paginate(20);
        return view('billing.index', compact('invoices'));
    }

    public function create()
    {
        Gate::authorize('create', Invoice::class);
        $patients = Patient::all();
        return view('billing.create', compact('patients'));
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Invoice::class);

        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.item_type' => 'required|string|in:service,medicine,consultation,other',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
        ]);

        $patient = Patient::findOrFail($request->patient_id);
        
        $invoice = $this->billingService->createInvoice(
            $patient,
            $request->items,
            $request->appointment_id, // Optional
            $request->discount ?? 0,
            $request->tax ?? 0
        );

        return redirect()->route('billing.show', $invoice)
            ->with('success', 'Invoice generated successfully.');
    }

    public function show(Invoice $invoice)
    {
        Gate::authorize('view', $invoice);
        $invoice->load(['items', 'payments', 'patient']);
        return view('billing.show', compact('invoice'));
    }

    public function addPayment(Invoice $invoice)
    {
        Gate::authorize('update', $invoice); // Assuming update permission covers payments
        return view('billing.payment', compact('invoice'));
    }

    public function storePayment(Request $request, Invoice $invoice)
    {
        Gate::authorize('update', $invoice);

        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string|in:cash,card,insurance,online',
            'transaction_reference' => 'nullable|string',
        ]);

        $this->billingService->recordPayment(
            $invoice,
            $request->amount,
            $request->payment_method,
            auth()->user(),
            $request->transaction_reference
        );

        return redirect()->route('billing.show', $invoice)
            ->with('success', 'Payment recorded successfully.');
    }
}
