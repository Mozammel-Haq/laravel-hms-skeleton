<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Consultation;

class ConsultationPolicy extends BaseTenantPolicy
{
    public function view(User $user, Consultation $consultation): bool
    {
        return $this->sameClinic($user, $consultation);
    }

    public function create(User $user): bool
    {
        return !empty($user->clinic_id);
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
