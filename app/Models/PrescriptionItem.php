<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

class PrescriptionItem extends BaseTenantModel
{
    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}
