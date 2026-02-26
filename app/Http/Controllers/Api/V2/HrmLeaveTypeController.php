<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\HrmLeaveType;
use Illuminate\Http\Request;

class HrmLeaveTypeController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (! $user->can('manage_leaves')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $query = HrmLeaveType::query()
            ->where('clinic_id', $user->clinic_id)
            ->orderBy('name');

        if ($status = $request->string('status')->toString()) {
            $query->where('status', $status);
        }

        $types = $query->get();

        return response()->json([
            'status' => 'success',
            'data' => $types,
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        if (! $user->can('manage_leaves')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'default_days' => 'nullable|numeric|min:0|max:365',
            'carry_forward' => 'nullable|boolean',
            'is_paid' => 'nullable|boolean',
            'pay_factor' => 'nullable|numeric|min:0|max:1',
            'status' => 'nullable|in:active,inactive',
        ]);

        $existsByName = HrmLeaveType::where('clinic_id', $user->clinic_id)
            ->where('name', $validated['name'])
            ->exists();

        if ($existsByName) {
            return response()->json(['message' => 'Leave type name already exists'], 422);
        }

        if (! empty($validated['code'])) {
            $existsByCode = HrmLeaveType::where('clinic_id', $user->clinic_id)
                ->where('code', $validated['code'])
                ->exists();
            if ($existsByCode) {
                return response()->json(['message' => 'Leave type code already exists'], 422);
            }
        }

        $type = HrmLeaveType::create([
            'clinic_id' => $user->clinic_id,
            'name' => $validated['name'],
            'code' => $validated['code'] ?? null,
            'default_days' => $validated['default_days'] ?? 0,
            'carry_forward' => $validated['carry_forward'] ?? false,
            'is_paid' => $validated['is_paid'] ?? true,
            'pay_factor' => $validated['pay_factor'] ?? 1.0,
            'status' => $validated['status'] ?? 'active',
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $type,
        ], 201);
    }

    public function update(Request $request, HrmLeaveType $leaveType)
    {
        $user = $request->user();

        if ($leaveType->clinic_id !== $user->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $user->can('manage_leaves')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'code' => 'nullable|string|max:50',
            'default_days' => 'nullable|numeric|min:0|max:365',
            'carry_forward' => 'nullable|boolean',
            'is_paid' => 'nullable|boolean',
            'pay_factor' => 'nullable|numeric|min:0|max:1',
            'status' => 'nullable|in:active,inactive',
        ]);

        if (isset($validated['name'])) {
            $existsByName = HrmLeaveType::where('clinic_id', $user->clinic_id)
                ->where('name', $validated['name'])
                ->where('id', '!=', $leaveType->id)
                ->exists();
            if ($existsByName) {
                return response()->json(['message' => 'Leave type name already exists'], 422);
            }
        }

        if (array_key_exists('code', $validated) && $validated['code'] !== null && $validated['code'] !== '') {
            $existsByCode = HrmLeaveType::where('clinic_id', $user->clinic_id)
                ->where('code', $validated['code'])
                ->where('id', '!=', $leaveType->id)
                ->exists();
            if ($existsByCode) {
                return response()->json(['message' => 'Leave type code already exists'], 422);
            }
        }

        $leaveType->update($validated);

        return response()->json([
            'status' => 'success',
            'data' => $leaveType,
        ]);
    }

    public function destroy(Request $request, HrmLeaveType $leaveType)
    {
        $user = $request->user();

        if ($leaveType->clinic_id !== $user->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $user->can('manage_leaves')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $leaveType->status = 'inactive';
        $leaveType->save();

        return response()->json([
            'status' => 'success',
        ]);
    }
}

