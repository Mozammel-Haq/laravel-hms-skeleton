<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy extends BaseTenantPolicy
{
    public function viewAny(User $user): bool
    {
        return !empty($user->clinic_id);
    }

    public function view(User $user, User $subject): bool
    {
        return $user->clinic_id === $subject->clinic_id;
    }

    public function create(User $user): bool
    {
        return !empty($user->clinic_id);
    }

    public function update(User $user, User $subject): bool
    {
        return $user->clinic_id === $subject->clinic_id;
    }

    public function delete(User $user, User $subject): bool
    {
        return $user->clinic_id === $subject->clinic_id;
    }
}

