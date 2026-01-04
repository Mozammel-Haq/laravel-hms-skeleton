<?php

namespace App\Policies;

use App\Models\Medicine;
use App\Models\User;

class MedicinePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view_pharmacy_inventory') || $user->hasPermission('manage_pharmacy_inventory');
    }

    public function view(User $user, Medicine $medicine): bool
    {
        return $user->hasPermission('view_pharmacy_inventory') || $user->hasPermission('manage_pharmacy_inventory');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('manage_pharmacy_inventory');
    }

    public function update(User $user, Medicine $medicine): bool
    {
        return $user->hasPermission('manage_pharmacy_inventory');
    }

    public function delete(User $user, Medicine $medicine): bool
    {
        return $user->hasPermission('manage_pharmacy_inventory');
    }
}
