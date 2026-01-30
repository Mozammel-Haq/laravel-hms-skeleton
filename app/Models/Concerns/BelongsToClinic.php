<?php

namespace App\Models\Concerns;

use App\Support\TenantContext;
use Illuminate\Database\Eloquent\Builder;

/**
 * Trait BelongsToClinic
 *
 * Automatically filters models by the current clinic (tenant) context.
 * Also sets the clinic_id when creating new models.
 */
trait BelongsToClinic
{
    protected static function bootBelongsToClinic()
    {
        static::addGlobalScope('clinic', function (Builder $builder) {

            if (!TenantContext::hasClinic()) {
                return;
            }

            $builder->where(
                $builder->getModel()->getTable() . '.clinic_id',
                TenantContext::getClinicId()
            );
        });

        static::creating(function ($model) {

            if (TenantContext::hasClinic() && empty($model->clinic_id)) {
                $model->clinic_id = TenantContext::getClinicId();
            }
        });
    }

    public function clinic()
    {
        return $this->belongsTo(\App\Models\Clinic::class);
    }
}
