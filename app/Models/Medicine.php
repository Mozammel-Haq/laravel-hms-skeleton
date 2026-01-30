<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Medicine Model
 *
 * Represents a pharmaceutical product.
 * Manages inventory, pricing, and batches.
 *
 * @property int $id
 * @property string $name
 * @property string|null $generic_name
 * @property string|null $manufacturer
 * @property string|null $strength
 * @property string|null $dosage_form
 * @property string $price
 * @property string $status 'active', 'inactive'
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MedicineBatch[] $batches
 * @property-read \App\Models\InvoiceItem|null $invoiceItem
 */

use App\Models\Concerns\LogsActivity;

class Medicine extends Model
{
    use LogsActivity;

    public function getActivityDescription($action)
    {
        return ucfirst($action) . " medicine {$this->name} ({$this->generic_name})";
    }

    protected $guarded = ['id'];
    public $timestamps = false;

    /**
     * Get the batches for the medicine.
     *
     * Relationship: Has Many.
     * Tracks different production batches/expiry dates.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function batches(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MedicineBatch::class);
    }

    /**
     * Get the invoice item associated with this medicine.
     *
     * Relationship: Has One (Polymorphic-like).
     * Links to invoice item where item_type matches table name.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function invoiceItem(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(InvoiceItem::class, 'reference_id')->where('item_type', $this->getTable());
    }
    protected $casts = [
        'created_at' => 'date',
    ];
}
