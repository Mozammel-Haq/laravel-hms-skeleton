<?php

namespace App\Policies;

use App\Models\Clinic;
use App\Models\User;

class ClinicPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Super Admin');
    }

    public function view(User $user, Clinic $clinic): bool
    {
        return $user->hasRole('Super Admin');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Super Admin');
    }

    public function update(User $user, Clinic $clinic): bool
    {
        return $user->hasRole('Super Admin');
    }

    public function delete(User $user, Clinic $clinic): bool
    {
        return $user->hasRole('Super Admin');
    }
}
