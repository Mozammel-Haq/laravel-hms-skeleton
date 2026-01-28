<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Patient;

class PatientPolicy extends BaseTenantPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view_patients');
    }

    public function view(User $user, Patient $patient): bool
    {
        return $this->hasAccess($user, $patient) && $user->hasPermission('view_patients');
    }

    public function create(User $user): bool
    {
        return !empty($user->clinic_id) && $user->hasPermission('create_patients');
    }

    public function update(User $user, Patient $patient): bool
    {
        return $this->hasAccess($user, $patient) && $user->hasPermission('edit_patients');
    }

    public function delete(User $user, Patient $patient): bool
    {
        return $this->hasAccess($user, $patient) && $user->hasPermission('delete_patients');
    }

    /**
     * Check if user has access to the patient via pivot or legacy clinic_id
     */
    protected function hasAccess(User $user, Patient $patient): bool
    {
        // If user is super admin, they might have access (logic depends on system, but usually yes)
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // Check if patient is linked to user's clinic
        return $patient->clinics()->where('clinics.id', $user->clinic_id)->exists()
            || $patient->clinic_id === $user->clinic_id;
    }
}
