<?php

namespace App\Policies;

use App\Models\User;
use App\Models\PharmacySale;

class PharmacySalePolicy extends BaseTenantPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view_pharmacy');
    }

    public function view(User $user, PharmacySale $pharmacySale): bool
    {
        return $this->sameClinic($user, $pharmacySale);
    }

    public function create(User $user): bool
    {
        return !empty($user->clinic_id);
    }

    public function update(User $user, PharmacySale $pharmacySale): bool
    {
        return $this->sameClinic($user, $pharmacySale);
    }

    public function delete(User $user, PharmacySale $pharmacySale): bool
    {
        return $this->sameClinic($user, $pharmacySale);
    }
}
