<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcurementItem extends Model
{
    protected $fillable = [
        'procurement_order_id',
        'medicine_id',
        'item_name', // If not a standard medicine
        'quantity',
        'unit_price',
        'total_price',
        'received_quantity',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(ProcurementOrder::class);
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}
