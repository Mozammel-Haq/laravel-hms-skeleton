<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

/**
 * DepartmentController
 *
 * Manages hospital departments (e.g., Cardiology, Neurology).
 * Supports CRUD operations and soft deletes.
 * Departments are scoped to the current tenant (clinic).
 */
class DepartmentController extends Controller
{
    /**
     * Display a listing of departments.
     *
     * Supports filtering by:
     * - Status: 'trashed' (optional)
     * - Search: Name, description
     * - Date Range: Creation date
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        Gate::authorize('viewAny', Department::class);
        $query = Department::query();

        if (request('status') === 'trashed') {
            $query->onlyTrashed();
        }

        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if (request()->filled('from')) {
            $query->whereDate('created_at', '>=', request('from'));
        }

        if (request()->filled('to')) {
            $query->whereDate('created_at', '<=', request('to'));
        }

        $departments = $query->latest()->paginate(20)->withQueryString();
        return view('departments.index', compact('departments'));
    }

    /**
     * Restore a soft-deleted department.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore($id)
    {
        $department = Department::withTrashed()->findOrFail($id);
        Gate::authorize('delete', $department);
        $department->restore();
        return back()->with('success', 'Department restored successfully.');
    }

    /**
     * Store a newly created department.
     *
     * Automatically assigns the department to the current user's clinic.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Department::class);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Department::create($request->all() + ['clinic_id' => auth()->user()->clinic_id]);

        return back()->with('success', 'Department created successfully.');
    }

    /**
     * Update the specified department.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Department $department)
    {
        Gate::authorize('update', $department);

        $request->validate(['name' => 'required|string|max:255']);
        $department->update($request->all());

        return back()->with('success', 'Department updated successfully.');
    }

    /**
     * Remove the specified department from storage.
     *
     * Performs a soft delete.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Department $department)
    {
        Gate::authorize('delete', $department);
        $department->delete();
        return back()->with('success', 'Department deleted successfully.');
    }
}
