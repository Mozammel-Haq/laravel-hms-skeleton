<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\User;

class DepartmentPolicy extends BaseTenantPolicy
{
    public function viewAny(User $user): bool
    {
        return !empty($user->clinic_id);
    }

    public function view(User $user, Department $department): bool
    {
        return $this->sameClinic($user, $department);
    }

    public function create(User $user): bool
    {
        return !empty($user->clinic_id);
    }

    public function update(User $user, Department $department): bool
    {
        return $this->sameClinic($user, $department);
    }

    public function delete(User $user, Department $department): bool
    {
        return $this->sameClinic($user, $department);
    }
}

