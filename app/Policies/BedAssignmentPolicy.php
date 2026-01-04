<?php

namespace App\Policies;

use App\Models\User;
use App\Models\BedAssignment;

class BedAssignmentPolicy extends BaseTenantPolicy
{
    public function view(User $user, BedAssignment $bedAssignment): bool
    {
        return $this->sameClinic($user, $bedAssignment);
    }

    public function create(User $user): bool
    {
        return !empty($user->clinic_id);
    }

    public function update(User $user, BedAssignment $bedAssignment): bool
    {
        return $this->sameClinic($user, $bedAssignment);
    }

    public function delete(User $user, BedAssignment $bedAssignment): bool
    {
        return $this->sameClinic($user, $bedAssignment);
    }
}
