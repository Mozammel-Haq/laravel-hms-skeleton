<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

class InvoiceItem extends BaseTenantModel
{
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
