<?php

namespace App\Http\Controllers\Extras;

use App\Http\Controllers\Controller;
use App\Models\Invoice;

class PaymentsController extends Controller
{
    public function cash()
    {
        $invoices = Invoice::latest()->take(100)->get();
        return view('payments.cash', compact('invoices'));
    }

    public function digital()
    {
        $invoices = Invoice::latest()->take(100)->get();
        return view('payments.digital', compact('invoices'));
    }
}
