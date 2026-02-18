<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\HrmPerformanceGoal;
use App\Models\User;
use Illuminate\Http\Request;

class HrmPerformanceGoalController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $query = HrmPerformanceGoal::query()
            ->where('clinic_id', $user->clinic_id)
            ->with(['kpi', 'owner', 'user'])
            ->orderByDesc('created_at');

        if (! $user->can('view_reports')) {
            $query->where('user_id', $user->id);
        }

        if ($status = $request->string('status')->toString()) {
            $query->where('status', $status);
        }

        $items = $query->paginate(20);

        return response()->json([
            'status' => 'success',
            'data' => $items,
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'user_id' => 'nullable|integer|exists:users,id',
            'kpi_id' => 'nullable|integer|exists:hrm_performance_kpis,id',
            'period_start' => 'nullable|date',
            'period_end' => 'nullable|date|after_or_equal:period_start',
            'title' => 'required|string|max:255',
            'unit' => 'nullable|string|max:50',
            'target_value' => 'nullable|numeric',
            'current_value' => 'nullable|numeric',
            'status' => 'nullable|in:draft,in_progress,completed,cancelled',
            'owner_user_id' => 'nullable|integer|exists:users,id',
            'notes' => 'nullable|string',
        ]);

        $targetUserId = $validated['user_id'] ?? $user->id;
        $ownerUserId = $validated['owner_user_id'] ?? $user->id;

        $targetUser = User::whereKey($targetUserId)
            ->where('clinic_id', $user->clinic_id)
            ->first();

        if (! $targetUser) {
            return response()->json(['message' => 'Invalid user for this clinic'], 422);
        }

        $ownerUser = User::whereKey($ownerUserId)
            ->where('clinic_id', $user->clinic_id)
            ->first();

        if (! $ownerUser) {
            return response()->json(['message' => 'Invalid owner for this clinic'], 422);
        }

        if (! $user->can('view_reports') && $targetUser->id !== $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $goal = HrmPerformanceGoal::create([
            'clinic_id' => $user->clinic_id,
            'user_id' => $targetUser->id,
            'kpi_id' => $validated['kpi_id'] ?? null,
            'period_start' => $validated['period_start'] ?? null,
            'period_end' => $validated['period_end'] ?? null,
            'title' => $validated['title'],
            'unit' => $validated['unit'] ?? null,
            'target_value' => $validated['target_value'] ?? null,
            'current_value' => $validated['current_value'] ?? null,
            'status' => $validated['status'] ?? 'draft',
            'owner_user_id' => $ownerUser->id,
            'notes' => $validated['notes'] ?? null,
        ]);

        $goal->load(['kpi', 'owner', 'user']);

        return response()->json([
            'status' => 'success',
            'data' => $goal,
        ], 201);
    }

    public function update(Request $request, HrmPerformanceGoal $performanceGoal)
    {
        $user = $request->user();

        if ($performanceGoal->clinic_id !== $user->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $user->can('view_reports') && $performanceGoal->user_id !== $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'period_start' => 'nullable|date',
            'period_end' => 'nullable|date|after_or_equal:period_start',
            'title' => 'sometimes|required|string|max:255',
            'unit' => 'nullable|string|max:50',
            'target_value' => 'nullable|numeric',
            'current_value' => 'nullable|numeric',
            'status' => 'nullable|in:draft,in_progress,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        $performanceGoal->update($validated);

        $performanceGoal->load(['kpi', 'owner', 'user']);

        return response()->json([
            'status' => 'success',
            'data' => $performanceGoal,
        ]);
    }

    public function destroy(Request $request, HrmPerformanceGoal $performanceGoal)
    {
        $user = $request->user();

        if ($performanceGoal->clinic_id !== $user->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $user->can('view_reports') && $performanceGoal->user_id !== $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $performanceGoal->delete();

        return response()->json([
            'status' => 'success',
        ]);
    }
}

