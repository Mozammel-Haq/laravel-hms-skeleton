<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Patient;

class PatientPolicy extends BaseTenantPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view_patients');
    }

    public function view(User $user, Patient $patient): bool
    {
        return $this->sameClinic($user, $patient) && $user->hasPermission('view_patients');
    }

    public function create(User $user): bool
    {
        return !empty($user->clinic_id) && $user->hasPermission('create_patients');
    }

    public function update(User $user, Patient $patient): bool
    {
        return $this->sameClinic($user, $patient) && $user->hasPermission('edit_patients');
    }

    public function delete(User $user, Patient $patient): bool
    {
        return $this->sameClinic($user, $patient) && $user->hasPermission('delete_patients');
    }
}
