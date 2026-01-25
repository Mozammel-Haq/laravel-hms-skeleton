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
            $query = User::withoutTenant()
                ->with('roles')
                ->whereHas('roles', fn($q) => $q->where('name', 'Super Admin'));
            $view = 'admin.users.super_admins.index';
            $variableName = 'superAdmins';
        } elseif ($request->routeIs('admin.clinic-admin-users.*')) {
            $query = User::withoutTenant()
                ->with(['roles', 'clinic'])
                ->whereHas('roles', fn($q) => $q->where('name', 'Clinic Admin'));
            $view = 'admin.users.clinic_admins.index';
            $variableName = 'clinicAdmins';
        } else {
            abort(404);
        }

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            if ($request->status === 'trashed') {
                $query->onlyTrashed();
            } else {
                $query->where('status', $request->status);
            }
        } else {
            $query->withTrashed();
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $users = $query->latest()->paginate(50)->withQueryString();

        return view($view, [$variableName => $users]);
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
        $clinics = Clinic::orderBy('name')->get();
        return view('admin.users.clinic_admins.create', compact('clinics'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        Gate::authorize('manage_roles');
        $mainSuperAdmin = Auth::user();
        $isSuperAdmin = $request->routeIs('admin.super-admin-users.*');

        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|string|min:5',
            'phone'     => 'nullable|string|max:50',
            'status'    => 'required|in:active,inactive,blocked',
            'clinic_id' => $isSuperAdmin ? 'nullable' : 'required|exists:clinics,id',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        $user = User::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'phone'     => $data['phone'] ?? null,
            'password'  => Hash::make($data['password']),
            'status'    => $data['status'],
            'clinic_id' => $isSuperAdmin ? $mainSuperAdmin->clinic_id : $data['clinic_id'],
        ]);

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $user->profile_photo_path = $path;
            $user->save();
        }

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

        $clinics = Clinic::orderBy('name')->get();
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
            'profile_photo' => 'nullable|image|max:2048',
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

        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', str_replace(' ', '-', $file->getClientOriginalName()));
            $path = $file->storeAs('profile-photos', $filename, 'public');
            
            // Delete old photo if exists
            if ($user->profile_photo_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->profile_photo_path);
            }
            $user->profile_photo_path = $path;
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

        $user->update(['status' => 'inactive']);
        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully.');
    }
}
