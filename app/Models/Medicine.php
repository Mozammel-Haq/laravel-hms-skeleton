<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $guarded = ['id'];
    public $timestamps = false;

    public function batches()
    {
        return $this->hasMany(MedicineBatch::class);
    }
    public function invoiceItem()
    {
        return $this->hasOne(InvoiceItem::class, 'reference_id')->where('item_type', $this->getTable());
    }
}
