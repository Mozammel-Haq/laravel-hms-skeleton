<?php

namespace App\Http\Controllers\Extras;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Support\Facades\Gate;

class BillingPaymentsController extends Controller
{
    public function index()
    {
        Gate::authorize('view_billing');
        $payments = Payment::with(['invoice','patient'])->latest()->take(50)->get();
        return view('billing.payments.index', compact('payments'));
    }
}
