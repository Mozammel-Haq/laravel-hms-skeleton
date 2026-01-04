<?php

namespace App\Policies;

use App\Models\User;

abstract class BaseTenantPolicy
{
    protected function sameClinic(User $user, $model): bool
    {
        return isset($model->clinic_id)
            && $user->clinic_id === $model->clinic_id;
    }
}
