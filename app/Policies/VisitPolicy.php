<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Visit;

class VisitPolicy extends BaseTenantPolicy
{
    public function view(User $user, Visit $visit): bool
    {
        return $this->sameClinic($user, $visit);
    }

    public function create(User $user): bool
    {
        return !empty($user->clinic_id);
    }

    public function update(User $user, Visit $visit): bool
    {
        return $this->sameClinic($user, $visit);
    }

    public function delete(User $user, Visit $visit): bool
    {
        return $this->sameClinic($user, $visit);
    }
}
