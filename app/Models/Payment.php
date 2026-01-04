<?php

namespace App\Models;

use App\Models\Base\BaseTenantModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends BaseTenantModel
{
    use SoftDeletes;
    //
}
