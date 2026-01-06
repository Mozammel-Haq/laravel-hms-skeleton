<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\Gate;

class PaymentController extends Controller
{
    public function index()
    {
        Gate::authorize('view_billing');
        $payments = Payment::with(['invoice.patient'])->latest()->paginate(50);
        return view('billing.payments.index', compact('payments'));
    }

    public function cash()
    {
        Gate::authorize('process_payments');
        $invoices = Invoice::latest()->take(100)->get();
        return view('payments.cash', compact('invoices'));
    }

    public function digital()
    {
        Gate::authorize('process_payments');
        $invoices = Invoice::latest()->take(100)->get();
        return view('payments.digital', compact('invoices'));
    }
}
