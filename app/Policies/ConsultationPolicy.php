<?php

namespace App\Policies;

use App\Models\Consultation;
use App\Models\User;

class ConsultationPolicy extends BaseTenantPolicy
{
    public function view(User $user, Consultation $consultation): bool
    {
        return $this->sameClinic($user, $consultation);
    }

    public function create(User $user): bool
    {
        return ! empty($user->clinic_id) && $user->hasRole('Doctor');
    }

    public function update(User $user, Consultation $consultation): bool
    {
        return $this->sameClinic($user, $consultation);
    }

    public function delete(User $user, Consultation $consultation): bool
    {
        return $this->sameClinic($user, $consultation);
    }
}
