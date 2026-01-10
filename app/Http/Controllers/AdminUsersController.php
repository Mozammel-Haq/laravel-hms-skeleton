<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Clinic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class AdminUsersController extends Controller
{
    /**
     * Display a listing of users (Super Admins or Clinic Admins)
     */
    public function index(Request $request)
    {
        Gate::authorize('manage_roles');

        if ($request->routeIs('admin.super-admin-users.*')) {
            $users = User::withoutTenant()
                ->with('roles')
                ->whereHas('roles', fn($q) => $q->where('name', 'Super Admin'))
                ->orderBy('name')
                ->paginate(50);

            return view('admin.users.super_admins.index', ['superAdmins' => $users]);
        }

        if ($request->routeIs('admin.clinic-admin-users.*')) {
            $users = User::withoutTenant()
                ->with(['roles', 'clinic'])
                ->whereHas('roles', fn($q) => $q->where('name', 'Clinic Admin'))
                ->orderBy('name')
                ->paginate(50);

            return view('admin.users.clinic_admins.index', ['clinicAdmins' => $users]);
        }

        abort(404);
    }

    /**
     * Show the form for creating a new user
     */
    public function create(Request $request)
    {
        Gate::authorize('manage_roles');

        if ($request->routeIs('admin.super-admin-users.*')) {
            return view('admin.users.super_admins.create');
        }

        // Clinic Admin creation requires clinics list
        $clinics = Clinic::withoutTenant()->orderBy('name')->get();
        return view('admin.users.clinic_admins.create', compact('clinics'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        Gate::authorize('manage_roles');
        $mainSuperAdmin = Auth::user()->hasRole('Super Admin');
        $isSuperAdmin = $request->routeIs('admin.super-admin-users.*');

        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|string|min:5',
            'phone'     => 'nullable|string|max:50',
            'status'    => 'required|in:active,inactive,blocked',
            'clinic_id' => $isSuperAdmin ? 'nullable' : 'required|exists:clinics,id',
        ]);

        $user = User::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'phone'     => $data['phone'] ?? null,
            'password'  => Hash::make($data['password']),
            'status'    => $data['status'],
            'clinic_id' => $isSuperAdmin ? $mainSuperAdmin->clinic_id : $data['clinic_id'],
        ]);

        $role = $isSuperAdmin ? 'Super Admin' : 'Clinic Admin';
        $user->assignRole($role);

        return redirect()->route(
            $isSuperAdmin
                ? 'admin.super-admin-users.index'
                : 'admin.clinic-admin-users.index'
        )->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing an existing user
     */
    public function edit(User $user)
    {
        Gate::authorize('manage_roles');

        if ($user->hasRole('Super Admin')) {
            return view('admin.users.super_admins.edit', compact('user'));
        }

        $clinics = Clinic::withoutTenant()->orderBy('name')->get();
        return view('admin.users.clinic_admins.edit', compact('user', 'clinics'));
    }

    /**
     * Update an existing user
     */
    public function update(Request $request, User $user)
    {
        Gate::authorize('manage_roles');

        $isSuperAdmin = $user->hasRole('Super Admin');

        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'password'  => 'nullable|string|min:5',
            'phone'     => 'nullable|string|max:50',
            'status'    => 'required|in:active,inactive,suspended',
            'clinic_id' => $isSuperAdmin ? 'nullable' : 'required|exists:clinics,id',
        ]);

        // Update fields
        $user->name      = $data['name'];
        $user->email     = $data['email'];
        $user->phone     = $data['phone'] ?? null;
        $user->status    = $data['status'];
        if (!$isSuperAdmin) {
            $user->clinic_id = $data['clinic_id'];
        }
         $user->clinic_id = $user->clinic_id ?? 1;

        // Optional password update
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return redirect()->route(
            $isSuperAdmin
                ? 'admin.super-admin-users.index'
                : 'admin.clinic-admin-users.index'
        )->with('success', 'User updated successfully.');
    }

    /**
     * Delete a user
     */
    public function destroy(User $user)
    {
        Gate::authorize('manage_roles');

        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully.');
    }
}
