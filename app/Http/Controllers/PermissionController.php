<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

/**
 * PermissionController
 *
 * Manages system permissions and their assignment to roles.
 * Allows viewing permissions grouped by module.
 */
class PermissionController extends Controller
{

    /**
     * Display a listing of permissions and role assignments.
     */
    public function index(Request $request)
    {
        if (!auth()->user()->hasRole('Super Admin')) {
            abort(403, 'Unauthorized action.');
        }

        $roles = Role::latest()->get();
        $selectedRoleId = $request->query('role');
        $role = $selectedRoleId ? Role::findOrFail($selectedRoleId) : $roles->first();

        // Fetch all permissions
        $permissionsRaw = Permission::all();

        // Prepare module-wise grouping
        $permissions = [];
        foreach ($permissionsRaw as $perm) {
            // split name into action + entity/table
            $parts = explode('_', $perm->name, 2);
            if (count($parts) < 2) continue;
            [$action, $entity] = $parts;

            $permissions[$entity][$action] = $perm;
        }

        // Permissions already assigned to this role
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('admin.permissions.index', compact('roles', 'role', 'permissions', 'rolePermissions'));
    }


    /**
     * Show the form for editing the specified permission.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\View\View
     */
    public function edit(Permission $permission)
    {
        if (!auth()->user()->hasRole('Super Admin')) {
            abort(403, 'Unauthorized action.');
        }

        return view('admin.permissions.edit', compact('permission'));
    }

    /**
     * Update the specified permission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Permission $permission)
    {
        if (!auth()->user()->hasRole('Super Admin')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
        ]);

        $permission->update([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.permissions.index')->with('success', 'Permission updated successfully.');
    }
    /**
     * Update permissions for a specific role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateRolePermissions(Request $request, Role $role)
    {
        $role->permissions()->sync($request->permissions ?? []);
        return redirect()->back()->with('success', 'Permissions updated successfully.');
    }
}
