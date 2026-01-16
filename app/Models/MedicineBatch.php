<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

class MedicineBatch extends BaseTenantModel
{
    protected $fillable = [
        'clinic_id',
        'medicine_id',
        'batch_number',
        'expiry_date',
        'quantity_in_stock',
        'purchase_price',
    ];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
    protected $casts = [
        'expiry_date' => 'date',
    ];
}
