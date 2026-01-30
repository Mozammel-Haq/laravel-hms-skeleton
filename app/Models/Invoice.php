<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;
use App\Models\Concerns\LogsActivity;
use App\Models\Concerns\NotifiesRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Invoice Model
 *
 * Represents a billing invoice for a patient.
 * Tracks total amount, paid amount, and payment status.
 *
 * @property int $id
 * @property int $clinic_id
 * @property int $patient_id
 * @property int|null $appointment_id
 * @property int|null $visit_id
 * @property int|null $admission_id
 * @property string $invoice_number
 * @property string|null $invoice_type
 * @property string $subtotal
 * @property string $discount
 * @property string $tax
 * @property string $total_amount
 * @property string $status 'unpaid', 'partial', 'paid', 'cancelled'
 * @property string $state 'draft', 'finalized'
 * @property \Illuminate\Support\Carbon|null $issued_at
 * @property \Illuminate\Support\Carbon|null $finalized_at
 * @property int|null $finalized_by
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 *
 * @property-read \App\Models\Patient $patient
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\InvoiceItem[] $items
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Payment[] $payments
 * @property-read \App\Models\Appointment|null $appointment
 * @property-read \App\Models\Visit|null $visit
 * @property-read \App\Models\Admission|null $admission
 */

class Invoice extends BaseTenantModel
{
    use SoftDeletes, LogsActivity, NotifiesRoles;

    protected static function booted()
    {
        static::created(function ($invoice) {
            $invoice->notifyRole('Accountant', 'New Invoice', "Invoice #{$invoice->invoice_number} generated.");
        });
    }

    public function getActivityDescription($action)
    {
        $patientName = $this->patient ? $this->patient->name : 'Unknown Patient';
        return ucfirst($action) . " invoice #{$this->invoice_number} for {$patientName} (Amount: {$this->total_amount})";
    }

    /**
     * Get the patient associated with the invoice.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the items included in the invoice.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    /**
     * Get the payments made against this invoice.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the appointment associated with the invoice.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Get the visit associated with the invoice.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    /**
     * Get the admission associated with the invoice.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function admission()
    {
        return $this->belongsTo(Admission::class);
    }
    protected $casts = [
        'created_at' => 'date',
    ];
}
