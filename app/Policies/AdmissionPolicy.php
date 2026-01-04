<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Admission;

class AdmissionPolicy extends BaseTenantPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view_ipd');
    }

    public function view(User $user, Admission $admission): bool
    {
        return $this->sameClinic($user, $admission);
    }

    public function create(User $user): bool
    {
        return !empty($user->clinic_id);
    }

    public function update(User $user, Admission $admission): bool
    {
        return $this->sameClinic($user, $admission);
    }

    public function delete(User $user, Admission $admission): bool
    {
        return $this->sameClinic($user, $admission);
    }
}
