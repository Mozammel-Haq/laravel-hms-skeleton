<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\HrmOnboarding;
use App\Models\User;
use Illuminate\Http\Request;

class HrmOnboardingController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (! $user->can('view_staff') && ! $user->can('view_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $query = HrmOnboarding::query()
            ->where('clinic_id', $user->clinic_id)
            ->with('candidate')
            ->orderByDesc('created_at');

        if ($status = $request->string('status')->toString()) {
            $query->where('status', $status);
        }

        $items = $query->get();

        return response()->json([
            'status' => 'success',
            'data' => $items,
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        if (! $user->can('view_staff') && ! $user->can('view_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'candidate_id' => 'nullable|exists:hrm_candidates,id',
            'user_id' => 'nullable|exists:users,id',
            'start_date' => 'nullable|date',
            'completion_date' => 'nullable|date',
            'status' => 'nullable|in:pending,in_progress,completed,cancelled',
            'checklist' => 'nullable|array',
            'notes' => 'nullable|string',
        ]);

        if (! empty($validated['user_id'])) {
            $linkedUser = User::whereKey($validated['user_id'])
                ->where('clinic_id', $user->clinic_id)
                ->first();

            if (! $linkedUser) {
                return response()->json(['message' => 'Invalid user for this clinic'], 422);
            }
        }

        $item = HrmOnboarding::create([
            'clinic_id' => $user->clinic_id,
            'candidate_id' => $validated['candidate_id'] ?? null,
            'user_id' => $validated['user_id'] ?? null,
            'start_date' => $validated['start_date'] ?? null,
            'completion_date' => $validated['completion_date'] ?? null,
            'status' => $validated['status'] ?? 'pending',
            'checklist' => $validated['checklist'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $item->fresh('candidate'),
        ], 201);
    }

    public function update(Request $request, HrmOnboarding $onboarding)
    {
        $user = $request->user();

        if ($onboarding->clinic_id !== $user->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $user->can('view_staff') && ! $user->can('view_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'candidate_id' => 'nullable|exists:hrm_candidates,id',
            'user_id' => 'nullable|exists:users,id',
            'start_date' => 'nullable|date',
            'completion_date' => 'nullable|date',
            'status' => 'nullable|in:pending,in_progress,completed,cancelled',
            'checklist' => 'nullable|array',
            'notes' => 'nullable|string',
        ]);

        if (array_key_exists('user_id', $validated) && ! empty($validated['user_id'])) {
            $linkedUser = User::whereKey($validated['user_id'])
                ->where('clinic_id', $user->clinic_id)
                ->first();

            if (! $linkedUser) {
                return response()->json(['message' => 'Invalid user for this clinic'], 422);
            }
        }

        $onboarding->update($validated);

        return response()->json([
            'status' => 'success',
            'data' => $onboarding->fresh('candidate'),
        ]);
    }

    public function destroy(Request $request, HrmOnboarding $onboarding)
    {
        $user = $request->user();

        if ($onboarding->clinic_id !== $user->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $user->can('view_staff') && ! $user->can('view_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $onboarding->delete();

        return response()->json([
            'status' => 'success',
        ]);
    }
}
