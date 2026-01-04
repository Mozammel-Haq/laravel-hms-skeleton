<?php

namespace App\Policies;

use App\Models\MedicineBatch;
use App\Models\User;

class MedicineBatchPolicy extends BaseTenantPolicy
{
    public function viewAny(User $user): bool
    {
        return !empty($user->clinic_id);
    }

    public function view(User $user, MedicineBatch $batch): bool
    {
        return $this->sameClinic($user, $batch);
    }

    public function create(User $user): bool
    {
        return !empty($user->clinic_id);
    }

    public function update(User $user, MedicineBatch $batch): bool
    {
        return $this->sameClinic($user, $batch);
    }

    public function delete(User $user, MedicineBatch $batch): bool
    {
        return $this->sameClinic($user, $batch);
    }
}

