<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;
use App\Models\Concerns\LogsActivity;

/**
 * MedicineBatch Model
 *
 * Represents a batch of medicine in inventory.
 * Tracks expiry dates, stock quantities, and purchase prices.
 *
 * @property int $id
 * @property int $clinic_id
 * @property int $medicine_id
 * @property string $batch_number
 * @property \Illuminate\Support\Carbon|null $expiry_date
 * @property int $quantity_in_stock
 * @property string $purchase_price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\Medicine $medicine
 */
class MedicineBatch extends BaseTenantModel
{
    use LogsActivity;

    protected $fillable = [
        'clinic_id',
        'medicine_id',
        'batch_number',
        'expiry_date',
        'quantity_in_stock',
        'purchase_price',
    ];

    public function getActivityDescription($action)
    {
        $medicineName = $this->medicine ? $this->medicine->name : 'Unknown Medicine';
        return ucfirst($action) . " batch {$this->batch_number} for {$medicineName} (Qty: {$this->quantity_in_stock})";
    }

    /**
     * Get the medicine associated with the batch.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
    protected $casts = [
        'expiry_date' => 'date',
    ];
}
