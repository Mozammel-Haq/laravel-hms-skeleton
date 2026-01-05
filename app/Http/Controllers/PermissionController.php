<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class PermissionController extends Controller
{

public function index(Request $request)
{
    if (!auth()->user()->hasRole('Super Admin')) {
        abort(403, 'Unauthorized action.');
    }

    // Get all roles
    $roles = Role::orderBy('name')->get();

    // Determine selected role via query string (?role=1)
    $selectedRoleId = $request->query('role');
    $role = $selectedRoleId ? Role::findOrFail($selectedRoleId) : $roles->first();

    // Get all permissions grouped by module
    $permissions = Permission::all()->groupBy('module');

    // Permissions currently assigned to the selected role
    $rolePermissions = $role->permissions->pluck('name')->toArray();

    return view('admin.permissions.index', compact('roles', 'role', 'permissions', 'rolePermissions'));
}





    public function create()
    {
        if (!auth()->user()->hasRole('Super Admin')) {
            abort(403, 'Unauthorized action.');
        }

        return view('admin.permissions.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasRole('Super Admin')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
        ]);

        Permission::create([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.permissions.index')->with('success', 'Permission created successfully.');
    }

    public function edit(Permission $permission)
    {
        if (!auth()->user()->hasRole('Super Admin')) {
            abort(403, 'Unauthorized action.');
        }

        return view('admin.permissions.edit', compact('permission'));
    }

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

    public function destroy(Permission $permission)
    {
        if (!auth()->user()->hasRole('Super Admin')) {
            abort(403, 'Unauthorized action.');
        }

        $permission->delete();

        return redirect()->route('admin.permissions.index')->with('success', 'Permission deleted successfully.');
    }
    public function updateRolePermissions(Request $request, Role $role)
{
    $role->permissions()->sync($request->permissions ?? []);
    return redirect()->back()->with('success', 'Permissions updated successfully.');
}

}
