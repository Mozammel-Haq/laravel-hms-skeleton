<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\Gate;

/**
 * Manages payment records and transaction history.
 *
 * Responsibilities:
 * - Listing all payment transactions
 * - Filtering payments by method (cash, digital), status, and date
 * - Handling soft deletion and restoration of payment records
 * - Providing specialized views for cash and digital payments
 */
class PaymentController extends Controller
{
    /**
     * Display a listing of payments.
     *
     * Features:
     * - Filters by status ('trashed' or active)
     * - Filters by payment method ('cash', 'card', 'bank_transfer', 'digital')
     * - Search by Invoice number or Patient name
     * - Date range filtering
     * - Pagination support
     *
     * @return \Illuminate\View\View
     */
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
            $method = request('method');
            if ($method === 'digital') {
                $query->whereIn('payment_method', ['card', 'bank_transfer']);
            } else {
                $query->where('payment_method', $method);
            }
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

    /**
     * Soft delete the specified payment.
     *
     * @param Payment $payment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Payment $payment)
    {
        Gate::authorize('delete', $payment);
        $payment->delete();
        return redirect()->back()->with('success', 'Payment deleted successfully.');
    }

    /**
     * Restore a soft-deleted payment.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore($id)
    {
        $payment = Payment::withTrashed()->findOrFail($id);
        Gate::authorize('delete', $payment);
        $payment->restore();
        return redirect()->back()->with('success', 'Payment restored successfully.');
    }

    /**
     * Display cash payments.
     *
     * Convenience method to list only cash payments.
     * Relies on index() with 'method' => 'cash' parameter.
     *
     * @return \Illuminate\View\View
     */
    public function cash()
    {
        request()->merge(['method' => 'cash']);
        return $this->index();
    }

    /**
     * Display digital payments (Card + Bank Transfer).
     *
     * Convenience method to list only digital payments.
     * Relies on index() with 'method' => 'digital' parameter.
     *
     * @return \Illuminate\View\View
     */
    public function digital()
    {
        request()->merge(['method' => 'digital']);
        return $this->index();
    }
}
