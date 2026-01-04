<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;

class LabTestResult extends BaseTenantModel
{
    public function test() { return $this->belongsTo(LabTest::class); }
}
