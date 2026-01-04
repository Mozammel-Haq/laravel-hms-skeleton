<?php

namespace App\Models\Concerns;

use App\Support\TenantContext;
use Illuminate\Database\Eloquent\Builder;

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
}
