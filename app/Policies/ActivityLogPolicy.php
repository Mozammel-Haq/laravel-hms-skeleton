<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ActivityLog;

class ActivityLogPolicy extends BaseTenantPolicy
{
    public function view(User $user, ActivityLog $activityLog): bool
    {
        return $this->sameClinic($user, $activityLog);
    }

    public function create(User $user): bool
    {
        return !empty($user->clinic_id);
    }

    public function update(User $user, ActivityLog $activityLog): bool
    {
        return $this->sameClinic($user, $activityLog);
    }

    public function delete(User $user, ActivityLog $activityLog): bool
    {
        return $this->sameClinic($user, $activityLog);
    }
}
