<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * PharmacySaleItem Model
 *
 * Represents a specific item within a pharmacy sale.
 * Tracks the medicine, quantity, and price.
 *
 * @property int $id
 * @property int $pharmacy_sale_id
 * @property int $medicine_id
 * @property int $quantity
 * @property string $unit_price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\PharmacySale $sale
 * @property-read \App\Models\Medicine $medicine
 */
class PharmacySaleItem extends Model
{
    protected $guarded = ['id'];
    public $timestamps = false;

    /**
     * Get the sale associated with the item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sale(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PharmacySale::class, 'pharmacy_sale_id');
    }

    /**
     * Get the medicine associated with the item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function medicine(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }
    protected $casts = [
        'created_at' => 'date',
    ];
}
