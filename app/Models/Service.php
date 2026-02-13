<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;
use App\Models\Concerns\LogsActivity;

class Service extends BaseTenantModel
{
    use LogsActivity;

    protected $guarded = ['id'];

    public function getActivityDescription($action)
    {
        return ucfirst($action)." service '{$this->name}'";
    }
}
