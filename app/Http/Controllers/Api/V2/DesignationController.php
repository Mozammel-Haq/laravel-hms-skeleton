<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\Designation;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $perPage = (int) $request->input('per_page', 10);
        $data = Designation::where('clinic_id', $user->clinic_id)
            ->latest()
            ->paginate($perPage);
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
        $user = $request->user();

        if ($designation->clinic_id !== $user->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

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
        $user = request()->user();

        if ($designation->clinic_id !== $user->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        $designation->delete();
        return response()->json(['status' => 'success']);
    }
}
