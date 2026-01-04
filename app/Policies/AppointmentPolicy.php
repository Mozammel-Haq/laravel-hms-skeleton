<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Appointment;

class AppointmentPolicy extends BaseTenantPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view_appointments');
    }

    public function view(User $user, Appointment $appointment): bool
    {
        return $this->sameClinic($user, $appointment) && $user->hasPermission('view_appointments');
    }

    public function create(User $user): bool
    {
        return !empty($user->clinic_id) && $user->hasPermission('create_appointments');
    }

    public function update(User $user, Appointment $appointment): bool
    {
        return $this->sameClinic($user, $appointment) && $user->hasPermission('edit_appointments');
    }

    public function cancel(User $user, Appointment $appointment): bool
    {
        return $this->sameClinic($user, $appointment) && $user->hasPermission('cancel_appointments');
    }

    public function delete(User $user, Appointment $appointment): bool
    {
        return $this->sameClinic($user, $appointment) && $user->hasPermission('edit_appointments'); // Assuming delete is restricted or part of edit
    }
}
