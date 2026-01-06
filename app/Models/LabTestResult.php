<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

class LabTestResult extends BaseTenantModel
{
    public function order() { return $this->belongsTo(LabTestOrder::class, 'lab_test_order_id'); }
}
