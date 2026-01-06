<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;

class AdminUsersController extends Controller
{
    public function superAdmins()
    {
        Gate::authorize('manage_roles');
        $superAdmins = User::with('roles')
            ->whereHas('roles', fn($q) => $q->where('name', 'Super Admin'))
            ->orderBy('name')
            ->paginate(50);
        return view('admin.users.super_admins', compact('superAdmins'));
    }

    public function clinicAdmins()
    {
        Gate::authorize('manage_roles');
        $admins = User::with(['roles', 'clinic'])
            ->whereHas('roles', fn($q) => $q->where('name', 'Clinic Admin'))
            ->orderBy('name')
            ->paginate(50);
        return view('admin.users.clinic_admins', compact('admins'));
    }
}
