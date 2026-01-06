<?php

namespace App\Policies;

use App\Models\Doctor;
use App\Models\User;
use App\Support\TenantContext;

class DoctorPolicy extends BaseTenantPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view_doctors');
    }

    public function view(User $user, Doctor $doctor): bool
    {
        return $user->hasPermission('view_doctors')
            && $doctor->clinics()->where('clinics.id', TenantContext::getClinicId())->exists();
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('create_doctors');
    }

    public function update(User $user, Doctor $doctor): bool
    {
        return $user->hasPermission('edit_doctors')
            && $doctor->clinics()->where('clinics.id', TenantContext::getClinicId())->exists();
    }

    public function delete(User $user, Doctor $doctor): bool
    {
        return $user->hasPermission('delete_doctors')
            && $doctor->clinics()->where('clinics.id', TenantContext::getClinicId())->exists();
    }
}
