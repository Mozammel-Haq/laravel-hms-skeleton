<?php

namespace App\Http\Controllers\Extras;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Support\Facades\Gate;

/**
 * Class BillingPaymentsController
 *
 * Manages the display of billing payments.
 *
 * @package App\Http\Controllers\Extras
 */
class BillingPaymentsController extends Controller
{
    /**
     * Display a listing of recent payments.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        Gate::authorize('view_billing');
        $payments = Payment::with(['invoice','patient'])->latest()->take(50)->get();
        return view('billing.payments.index', compact('payments'));
    }
}
