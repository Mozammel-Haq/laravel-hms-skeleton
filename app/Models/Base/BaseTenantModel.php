<?php

namespace App\Models\Base;

use App\Models\Concerns\BelongsToClinic;
use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Model;


/**
 * BaseTenantModel
 *
 * Base class for all models that belong to a specific clinic (tenant).
 * Automatically applies the clinic scope and handles activity logging.
 *
 * @method static \Illuminate\Database\Eloquent\Builder|static withoutTenant()
 */
abstract class BaseTenantModel extends Model
{
    use BelongsToClinic, LogsActivity;
    protected $guarded = ['id', 'clinic_id'];

    public static function withoutTenant()
    {
        return static::withoutGlobalScope('clinic');
    }
}
