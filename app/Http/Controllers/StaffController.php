<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

/**
 * Manages staff members (users) and their roles.
 */
class StaffController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:view_staff')->only(['index', 'show', 'passwords']);
        $this->middleware('can:create_staff')->only(['create', 'store']);
        $this->middleware('can:edit_staff')->only(['edit', 'update']);
        $this->middleware('can:delete_staff')->only(['destroy', 'restore']);
    }

    /**
     * Display a listing of staff members.
     *
     * Supports filtering by:
     * - Status: 'active', 'inactive', 'trashed'
     * - Role: Filter by role ID
     * - Search: Name or Email
     * - Date Range: Creation date
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        Gate::authorize('viewAny', User::class);
        $currentUser = auth()->user();
        $query = User::with('roles')->where('clinic_id', $currentUser->clinic_id);

        if (! $currentUser->hasRole('Super Admin') && ! $currentUser->hasRole('Clinic Admin') && ! $currentUser->hasRole('Admin')) {
            $query->whereDoesntHave('roles', function ($q) {
                $q->whereIn('name', ['Super Admin', 'Clinic Admin', 'Admin']);
            });
        }

        if (request('status') === 'trashed') {
            $query->onlyTrashed()->latest();
        } else {
            $query->latest();
        }

        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
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

    /**
     * Restore the specified staff member.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $currentUser = auth()->user();
        if (! $currentUser->hasRole('Super Admin') && ! $currentUser->hasRole('Clinic Admin') && ! $currentUser->hasRole('Admin')) {
            if ($user->roles()->whereIn('name', ['Super Admin', 'Clinic Admin', 'Admin'])->exists()) {
                abort(403);
            }
        }
        Gate::authorize('delete', $user); // Use delete permission for restore
        $user->restore();
        $user->update(['status' => 'active']); // Restore status as well if needed

        return redirect()->route('staff.index')->with('success', 'Staff member restored successfully.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        Gate::authorize('create', User::class);
        $currentUser = auth()->user();
        $rolesQuery = Role::query();
        if (! $currentUser->hasRole('Super Admin') && ! $currentUser->hasRole('Clinic Admin') && ! $currentUser->hasRole('Admin')) {
            $rolesQuery->whereNotIn('name', ['Super Admin', 'Clinic Admin', 'Admin']);
        }
        $roles = $rolesQuery->get();

        return view('staff.create', compact('roles'));
    }

    /**
     * Store a newly created staff member in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
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

        $currentUser = auth()->user();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'clinic_id' => $currentUser->clinic_id,
        ]);

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $user->profile_photo_path = $path;
            $user->save();
        }

        $role = Role::find($request->role_id);
        if (! $currentUser->hasRole('Super Admin') && ! $currentUser->hasRole('Clinic Admin') && ! $currentUser->hasRole('Admin')) {
            if (in_array($role->name, ['Super Admin', 'Clinic Admin', 'Admin'], true)) {
                abort(403);
            }
        }
        $user->assignRole($role);

        return redirect()->route('staff.index')->with('success', 'Staff member created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\View\View
     */
    public function show(User $staff)
    {
        Gate::authorize('view', $staff);
        $staff->load('roles');

        return view('staff.show', compact('staff'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\View\View
     */
    public function edit(User $staff)
    {
        Gate::authorize('update', $staff);
        $currentUser = auth()->user();
        if (! $currentUser->hasRole('Super Admin') && ! $currentUser->hasRole('Clinic Admin') && ! $currentUser->hasRole('Admin')) {
            if ($staff->roles()->whereIn('name', ['Super Admin', 'Clinic Admin', 'Admin'])->exists()) {
                abort(403);
            }
        }

        $rolesQuery = Role::query();
        if (! $currentUser->hasRole('Super Admin') && ! $currentUser->hasRole('Clinic Admin') && ! $currentUser->hasRole('Admin')) {
            $rolesQuery->whereNotIn('name', ['Super Admin', 'Clinic Admin', 'Admin']);
        }
        $roles = $rolesQuery->get();

        return view('staff.edit', compact('staff', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $staff)
    {
        Gate::authorize('update', $staff);

        $request->validate([
            'name' => 'required|string|max:255',
            'role_id' => 'required|exists:roles,id',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        $currentUser = auth()->user();
        if (! $currentUser->hasRole('Super Admin') && ! $currentUser->hasRole('Clinic Admin') && ! $currentUser->hasRole('Admin')) {
            if ($staff->roles()->whereIn('name', ['Super Admin', 'Clinic Admin', 'Admin'])->exists()) {
                abort(403);
            }
        }

        $staff->update(['name' => $request->name]);

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile-photos', 'public');

            // Delete old photo if exists
            if ($staff->profile_photo_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($staff->profile_photo_path);
            }
            $staff->profile_photo_path = $path;
            $staff->save();
        }

        $role = Role::find($request->role_id);
        if (! $currentUser->hasRole('Super Admin') && ! $currentUser->hasRole('Clinic Admin') && ! $currentUser->hasRole('Admin')) {
            if (in_array($role->name, ['Super Admin', 'Clinic Admin', 'Admin'], true)) {
                abort(403);
            }
        }
        $staff->roles()->sync([$role->id]);

        return redirect()->route('staff.index')->with('success', 'Staff member updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $staff)
    {
        $currentUser = auth()->user();
        if (! $currentUser->hasRole('Super Admin') && ! $currentUser->hasRole('Clinic Admin') && ! $currentUser->hasRole('Admin')) {
            if ($staff->roles()->whereIn('name', ['Super Admin', 'Clinic Admin', 'Admin'])->exists()) {
                abort(403);
            }
        }
        Gate::authorize('delete', $staff);
        $staff->update(['status' => 'inactive']);
        $staff->delete();

        return redirect()->route('staff.index')->with('success', 'Staff member deleted successfully.');
    }

    /**
     * Display a listing of staff passwords (for management).
     *
     * @return \Illuminate\View\View
     */
    public function passwords()
    {
        Gate::authorize('viewAny', User::class);
        $currentUser = auth()->user();
        $staffQuery = User::with('roles')->orderBy('name');

        if (! $currentUser->hasRole('Super Admin') && ! $currentUser->hasRole('Clinic Admin') && ! $currentUser->hasRole('Admin')) {
            $staffQuery->whereDoesntHave('roles', function ($q) {
                $q->whereIn('name', ['Super Admin', 'Clinic Admin', 'Admin']);
            });
        }

        $staff = $staffQuery->paginate(20);

        return view('staff.passwords', compact('staff'));
    }
}
