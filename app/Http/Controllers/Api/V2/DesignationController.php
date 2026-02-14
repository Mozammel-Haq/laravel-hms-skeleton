<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\Designation;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    public function index()
    {
        $data = Designation::latest()->paginate(10);
        return response()->json(['status' => 'success', 'data' => $data]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:designations,slug',
            'code' => 'nullable|string|max:50|unique:designations,code',
            'grade' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
        ]);
        $designation = Designation::create(array_merge($validated, ['clinic_id' => $request->user()->clinic_id]));
        return response()->json(['status' => 'success', 'data' => $designation], 201);
    }

    public function update(Request $request, Designation $designation)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'slug' => 'sometimes|required|string|max:255|unique:designations,slug,'.$designation->id,
            'code' => 'nullable|string|max:50|unique:designations,code,'.$designation->id,
            'grade' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
        ]);
        $designation->update($validated);
        return response()->json(['status' => 'success', 'data' => $designation]);
    }

    public function destroy(Designation $designation)
    {
        $designation->delete();
        return response()->json(['status' => 'success']);
    }
}
