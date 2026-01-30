<?php

namespace App\Http\Controllers\Extras;

use App\Http\Controllers\Controller;
use App\Models\Invoice;

/**
 * Manages specific payment view pages.
 *
 * Responsibilities:
 * - Rendering dedicated views for Cash payments
 * - Rendering dedicated views for Digital payments
 * - Providing recent invoice lists for quick payment processing
 */
class PaymentsController extends Controller
{
    /**
     * Display the cash payments view.
     *
     * Lists the most recent 100 invoices to facilitate quick cash payment entry.
     *
     * @return \Illuminate\View\View
     */
    public function cash()
    {
        $invoices = Invoice::latest()->take(100)->get();
        return view('payments.cash', compact('invoices'));
    }

    /**
     * Display the digital payments view.
     *
     * @return \Illuminate\View\View
     */
    public function digital()
    {
        $invoices = Invoice::latest()->take(100)->get();
        return view('payments.digital', compact('invoices'));
    }
}
