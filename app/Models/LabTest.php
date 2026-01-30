<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * LabTest Model
 *
 * Represents a laboratory test available in the system.
 * Stores price, normal ranges, and category.
 *
 * @property int $id
 * @property string $name
 * @property string $category
 * @property string|null $description
 * @property string|null $normal_range
 * @property string $price
 * @property string $status 'active', 'inactive'
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */

use App\Models\Concerns\LogsActivity;

class LabTest extends Model
{
    use LogsActivity;

    public function getActivityDescription($action)
    {
        return ucfirst($action) . " lab test {$this->name}";
    }

    public $timestamps = false;
    protected $fillable = ['name', 'category', 'description', 'normal_range', 'price', 'status'];
    /**
     * Get the invoice item associated with this lab test.
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
