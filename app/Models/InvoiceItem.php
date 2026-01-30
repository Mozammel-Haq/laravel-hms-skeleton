<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;
use App\Models\Concerns\LogsActivity;

/**
 * InvoiceItem Model
 *
 * Represents an individual line item on an invoice.
 * Can reference a service, lab test, medicine, etc.
 *
 * @property int $id
 * @property int $clinic_id
 * @property int $invoice_id
 * @property string $item_type 'consultation', 'lab', 'medicine', 'bed', 'service'
 * @property int|null $reference_id
 * @property string $description
 * @property int $quantity
 * @property string $unit_price
 * @property string $total_price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\Invoice $invoice
 */
class InvoiceItem extends BaseTenantModel
{
    use LogsActivity;
    /**
     * Get the invoice associated with the item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
    protected $casts = [
        'created_at' => 'date',
    ];
}
