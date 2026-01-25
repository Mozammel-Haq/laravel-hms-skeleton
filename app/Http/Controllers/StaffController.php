<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', User::class);
        $query = User::with('roles');

        if (request('status') === 'trashed') {
            $query->onlyTrashed()->latest();
        } else {
            $query->latest();
        }

        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        if (request()->filled('role')) {
            $query->whereHas('roles', function ($q) {
                $q->where('id', request('role'));
            });
        }

        if (request()->filled('from')) {
            $query->whereDate('created_at', '>=', request('from'));
        }

        if (request()->filled('to')) {
            $query->whereDate('created_at', '<=', request('to'));
        }

        $users = $query->paginate(20)->withQueryString();
        $roles = Role::all();
        return view('staff.index', compact('users', 'roles'));
    }

    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        Gate::authorize('delete', $user); // Use delete permission for restore
        $user->restore();
        $user->update(['status' => 'active']); // Restore status as well if needed
        return redirect()->route('staff.index')->with('success', 'Staff member restored successfully.');
    }

    public function create()
    {
        Gate::authorize('create', User::class);
        $roles = Role::all();
        return view('staff.create', compact('roles'));
    }

    public function store(Request $request)
    {
        Gate::authorize('create', User::class);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role_id' => 'required|exists:roles,id',
            'profile_photo' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'clinic_id' => auth()->user()->clinic_id,
        ]);

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $user->profile_photo_path = $path;
            $user->save();
        }

        $role = Role::find($request->role_id);
        $user->assignRole($role);

        return redirect()->route('staff.index')->with('success', 'Staff member created successfully.');
    }

    public function show(User $staff)
    {
        Gate::authorize('view', $staff);
        $staff->load('roles');
        return view('staff.show', compact('staff'));
    }

    public function edit(User $user)
    {
        Gate::authorize('update', $user);
        $roles = Role::all();
        return view('staff.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        Gate::authorize('update', $user);

        $request->validate([
            'name' => 'required|string|max:255',
            'role_id' => 'required|exists:roles,id',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        $user->update(['name' => $request->name]);

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile-photos', 'public');

            // Delete old photo if exists
            if ($user->profile_photo_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->profile_photo_path);
            }
            $user->profile_photo_path = $path;
            $user->save();
        }

        $user->roles()->sync([$request->role_id]);

        return redirect()->route('staff.index')->with('success', 'Staff member updated successfully.');
    }

    public function destroy(User $user)
    {
        Gate::authorize('delete', $user);
        $user->update(['status' => 'inactive']);
        $user->delete();
        return redirect()->route('staff.index')->with('success', 'Staff member deleted successfully.');
    }

    public function passwords()
    {
        Gate::authorize('viewAny', User::class);
        $staff = User::with('roles')
            ->whereHas('roles', function ($q) {
                $q->where('name', '!=', 'Super Admin');
            })
            ->orderBy('name')
            ->paginate(20);
        return view('staff.passwords', compact('staff'));
    }
}
