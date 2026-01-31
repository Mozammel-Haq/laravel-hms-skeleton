<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;
use App\Models\Concerns\LogsActivity;
use App\Models\Concerns\NotifiesRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Payment Model
 *
 * Represents a payment made against an invoice.
 * Tracks the amount, payment method, and transaction details.
 *
 * @property int $id
 * @property int $invoice_id
 * @property int $clinic_id
 * @property string $amount
 * @property string $payment_method 'cash', 'card', 'mobile_banking', 'bank_transfer'
 * @property string|null $transaction_reference
 * @property \Illuminate\Support\Carbon $paid_at
 * @property int $received_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 *
 * @property-read \App\Models\Invoice $invoice
 * @property-read \App\Models\User $receivedBy
 */
class Payment extends BaseTenantModel
{
    use SoftDeletes, LogsActivity, NotifiesRoles;

    protected static function booted()
    {
        static::created(function ($payment) {
            $payment->notifyRole('Accountant', 'New Payment Received', "Payment of {$payment->amount} received for Invoice #{$payment->invoice?->invoice_number}.");
        });
    }

    public function getActivityDescription($action)
    {
        $invoiceNumber = $this->invoice ? $this->invoice->invoice_number : 'Unknown Invoice';
        return ucfirst($action) . " payment of {$this->amount} for invoice #{$invoiceNumber}";
    }

    /**
     * Get the invoice associated with the payment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function invoice(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Get the user who recorded the payment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function receivedBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }
    protected $casts = [
        'created_at' => 'date',
    ];
}
