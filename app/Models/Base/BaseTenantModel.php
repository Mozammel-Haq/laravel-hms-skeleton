<?php

namespace App\Models\Base;

use App\Models\Concerns\BelongsToClinic;
use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Model;


abstract class BaseTenantModel extends Model
{
    use BelongsToClinic, LogsActivity;
    protected $guarded = ['id', 'clinic_id'];
    public static function withoutTenant()
    {
        return static::withoutGlobalScope('clinic');
    }
}
