<?php

namespace App\Policies;

use App\Models\Doctor;
use App\Models\User;

class DoctorPolicy extends BaseTenantPolicy
{
    public function viewAny(User $user): bool
    {
        return !empty($user->clinic_id);
    }

    public function view(User $user, Doctor $doctor): bool
    {
        return $this->sameClinic($user, $doctor);
    }

    public function create(User $user): bool
    {
        return !empty($user->clinic_id);
    }

    public function update(User $user, Doctor $doctor): bool
    {
        return $this->sameClinic($user, $doctor);
    }

    public function delete(User $user, Doctor $doctor): bool
    {
        return $this->sameClinic($user, $doctor);
    }
}

