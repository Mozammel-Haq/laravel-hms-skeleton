<?php

namespace App\Notifications;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PaymentReceivedNotification extends Notification
{
    use Queueable;

    public $payment;
    public $invoice;

    public function __construct(Payment $payment, Invoice $invoice)
    {
        $this->payment = $payment;
        $this->invoice = $invoice;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Payment Received',
            'message' => "We received a payment of " . number_format($this->payment->amount, 2) . " for Invoice #{$this->invoice->invoice_number}.",
            'link' => route('billing.show', $this->invoice->id), // Assuming patient has access to billing view or similar
            'type' => 'success',
            'invoice_id' => $this->invoice->id,
            'payment_id' => $this->payment->id
        ];
    }
}
