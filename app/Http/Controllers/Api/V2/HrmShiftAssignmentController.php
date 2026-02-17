<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\HrmShift;
use App\Models\HrmShiftAssignment;
use App\Models\User;
use Illuminate\Http\Request;

class HrmShiftAssignmentController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $query = HrmShiftAssignment::with(['user', 'shift'])
            ->where('clinic_id', $user->clinic_id)
            ->orderByDesc('effective_from');

        if ($status = $request->string('status')->toString()) {
            $query->where('status', $status);
        }

        if ($userId = $request->integer('user_id')) {
            $query->where('user_id', $userId);
        }

        $perPage = (int) $request->input('per_page', 20);
        $assignments = $query->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $assignments,
        ]);
    }

    public function store(Request $request)
    {
        $actor = $request->user();

        if (! $actor->can('create_staff')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'shift_id' => 'required|integer|exists:hrm_shifts,id',
            'effective_from' => 'required|date',
            'effective_to' => 'nullable|date|after_or_equal:effective_from',
            'is_primary' => 'nullable|boolean',
            'status' => 'nullable|in:active,inactive',
        ]);

        $targetUser = User::where('id', $validated['user_id'])
            ->where('clinic_id', $actor->clinic_id)
            ->firstOrFail();

        $shift = HrmShift::where('id', $validated['shift_id'])
            ->where('clinic_id', $actor->clinic_id)
            ->firstOrFail();

        $assignment = HrmShiftAssignment::create([
            'clinic_id' => $actor->clinic_id,
            'user_id' => $targetUser->id,
            'shift_id' => $shift->id,
            'effective_from' => $validated['effective_from'],
            'effective_to' => $validated['effective_to'] ?? null,
            'is_primary' => $validated['is_primary'] ?? true,
            'status' => $validated['status'] ?? 'active',
        ]);

        $assignment->load(['user', 'shift']);

        return response()->json([
            'status' => 'success',
            'data' => $assignment,
        ], 201);
    }

    public function update(Request $request, HrmShiftAssignment $assignment)
    {
        $actor = $request->user();

        if ($assignment->clinic_id !== $actor->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $actor->can('create_staff')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'effective_from' => 'sometimes|required|date',
            'effective_to' => 'nullable|date|after_or_equal:effective_from',
            'is_primary' => 'nullable|boolean',
            'status' => 'nullable|in:active,inactive',
        ]);

        $assignment->update($validated);
        $assignment->load(['user', 'shift']);

        return response()->json([
            'status' => 'success',
            'data' => $assignment,
        ]);
    }

    public function destroy(Request $request, HrmShiftAssignment $assignment)
    {
        $actor = $request->user();

        if ($assignment->clinic_id !== $actor->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $actor->can('create_staff')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $assignment->status = 'inactive';
        $assignment->save();

        return response()->json([
            'status' => 'success',
        ]);
    }
}
