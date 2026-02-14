<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $data = Department::latest()->paginate(10);
        return response()->json(['status' => 'success', 'data' => $data]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'floor_number' => 'nullable|string|max:50',
            'phone_extension' => 'nullable|string|max:50',
            'status' => 'nullable|in:active,inactive',
        ]);
        $department = Department::create(array_merge($validated, ['clinic_id' => $request->user()->clinic_id]));
        return response()->json(['status' => 'success', 'data' => $department], 201);
    }

    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'floor_number' => 'nullable|string|max:50',
            'phone_extension' => 'nullable|string|max:50',
            'status' => 'nullable|in:active,inactive',
        ]);
        $department->update($validated);
        return response()->json(['status' => 'success', 'data' => $department]);
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return response()->json(['status' => 'success']);
    }
}
