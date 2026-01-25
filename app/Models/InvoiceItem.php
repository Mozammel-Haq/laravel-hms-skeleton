<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;
use App\Models\Concerns\LogsActivity;

class InvoiceItem extends BaseTenantModel
{
    use LogsActivity;
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
    protected $casts = [
        'created_at' => 'date',
    ];
}
