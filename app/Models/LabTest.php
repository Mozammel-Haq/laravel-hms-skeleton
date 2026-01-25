<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabTest extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'category', 'description', 'normal_range', 'price', 'status'];
    public function invoiceItem()
    {
        return $this->hasOne(InvoiceItem::class, 'reference_id')->where('item_type', $this->getTable());
    }
    protected $casts = [
        'created_at' => 'date',
    ];
}
