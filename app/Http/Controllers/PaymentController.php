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
        $query = Payment::with(['invoice.patient']);

        if (request('status') === 'trashed') {
            $query->onlyTrashed()->latest();
        } else {
            $query->latest();
        }

        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('invoice', function ($sub) use ($search) {
                    $sub->where('invoice_number', 'like', '%' . $search . '%')
                        ->orWhereHas('patient', function ($p) use ($search) {
                            $p->where('name', 'like', '%' . $search . '%');
                        });
                });
            });
        }

        if (request()->filled('method')) {
            $query->where('payment_method', request('method'));
        }

        if (request()->filled('from')) {
            $query->whereDate('paid_at', '>=', request('from'));
        }

        if (request()->filled('to')) {
            $query->whereDate('paid_at', '<=', request('to'));
        }

        $payments = $query->paginate(50)->withQueryString();
        return view('billing.payments.index', compact('payments'));
    }

    public function destroy(Payment $payment)
    {
        Gate::authorize('delete', $payment);
        $payment->delete();
        return redirect()->back()->with('success', 'Payment deleted successfully.');
    }

    public function restore($id)
    {
        $payment = Payment::withTrashed()->findOrFail($id);
        Gate::authorize('delete', $payment);
        $payment->restore();
        return redirect()->back()->with('success', 'Payment restored successfully.');
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
