<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

/**
 * AdmissionDeposit Model
 *
 * Represents a financial deposit made for an admission.
 *
 * @property int $id
 * @property int $clinic_id
 * @property int $admission_id
 * @property string $amount
 * @property string $payment_method 'cash', 'card', 'bank_transfer'
 * @property string|null $transaction_reference
 * @property \Illuminate\Support\Carbon|null $received_at
 * @property int|null $received_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\Admission $admission
 * @property-read \App\Models\User|null $receiver
 */
class AdmissionDeposit extends BaseTenantModel
{
    protected $guarded = ['id'];

    /**
     * Get the admission associated with the deposit.
     *
     * Relationship: Belongs to Admission.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function admission(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Admission::class);
    }

    /**
     * Get the user who received the deposit.
     *
     * Relationship: Belongs to User.
     * Tracks the staff member who processed the deposit.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function receiver(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }
    protected $casts = [
        'created_at' => 'date',
    ];
}
