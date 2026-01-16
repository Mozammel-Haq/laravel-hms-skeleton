<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DepartmentController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Department::class);
        $query = Department::query();

        if (request('status') === 'trashed') {
            $query->onlyTrashed();
        }

        $departments = $query->get();
        return view('departments.index', compact('departments'));
    }

    public function restore($id)
    {
        $department = Department::withTrashed()->findOrFail($id);
        Gate::authorize('delete', $department);
        $department->restore();
        return back()->with('success', 'Department restored successfully.');
    }

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

    public function update(Request $request, Department $department)
    {
        Gate::authorize('update', $department);
        
        $request->validate(['name' => 'required|string|max:255']);
        $department->update($request->all());

        return back()->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        Gate::authorize('delete', $department);
        $department->delete();
        return back()->with('success', 'Department deleted successfully.');
    }
}
