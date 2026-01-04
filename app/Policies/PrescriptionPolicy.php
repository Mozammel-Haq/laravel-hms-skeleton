<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Prescription;

class PrescriptionPolicy extends BaseTenantPolicy
{
    public function view(User $user, Prescription $prescription): bool
    {
        return $this->sameClinic($user, $prescription);
    }

    public function create(User $user): bool
    {
        return !empty($user->clinic_id);
    }

    public function update(User $user, Prescription $prescription): bool
    {
        return $this->sameClinic($user, $prescription);
    }

    public function delete(User $user, Prescription $prescription): bool
    {
        return $this->sameClinic($user, $prescription);
    }
}
