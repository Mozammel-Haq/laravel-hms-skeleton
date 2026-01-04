<?php

namespace App\Policies;

use App\Models\User;
use App\Models\LabTestOrder;

class LabTestOrderPolicy extends BaseTenantPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view_lab');
    }

    public function view(User $user, LabTestOrder $labTestOrder): bool
    {
        return $this->sameClinic($user, $labTestOrder);
    }

    public function create(User $user): bool
    {
        return !empty($user->clinic_id);
    }

    public function update(User $user, LabTestOrder $labTestOrder): bool
    {
        return $this->sameClinic($user, $labTestOrder);
    }

    public function delete(User $user, LabTestOrder $labTestOrder): bool
    {
        return $this->sameClinic($user, $labTestOrder);
    }
}
