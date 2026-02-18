<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\HrmPerformanceAppraisal;
use App\Models\HrmPerformanceReview;
use App\Models\User;
use Illuminate\Http\Request;

class HrmPerformanceAppraisalController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $query = HrmPerformanceAppraisal::query()
            ->where('clinic_id', $user->clinic_id)
            ->with(['user', 'review', 'promotionToDesignation'])
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
            'review_id' => 'nullable|integer|exists:hrm_performance_reviews,id',
            'effective_date' => 'nullable|date',
            'current_salary' => 'nullable|numeric',
            'new_salary' => 'nullable|numeric',
            'salary_change_amount' => 'nullable|numeric',
            'salary_change_percent' => 'nullable|numeric',
            'promotion_to_designation_id' => 'nullable|integer|exists:designations,id',
            'status' => 'nullable|in:draft,recommended,approved,rejected',
            'notes' => 'nullable|string',
        ]);

        $targetUserId = $validated['user_id'] ?? $user->id;

        $targetUser = User::whereKey($targetUserId)
            ->where('clinic_id', $user->clinic_id)
            ->first();

        if (! $targetUser) {
            return response()->json(['message' => 'Invalid user for this clinic'], 422);
        }

        $review = null;

        if (! empty($validated['review_id'])) {
            $review = HrmPerformanceReview::whereKey($validated['review_id'])
                ->where('clinic_id', $user->clinic_id)
                ->first();

            if (! $review) {
                return response()->json(['message' => 'Invalid review for this clinic'], 422);
            }
        }

        if (! $user->can('view_reports') && $targetUser->id !== $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $appraisal = HrmPerformanceAppraisal::create([
            'clinic_id' => $user->clinic_id,
            'user_id' => $targetUser->id,
            'review_id' => $review?->id,
            'effective_date' => $validated['effective_date'] ?? null,
            'current_salary' => $validated['current_salary'] ?? null,
            'new_salary' => $validated['new_salary'] ?? null,
            'salary_change_amount' => $validated['salary_change_amount'] ?? null,
            'salary_change_percent' => $validated['salary_change_percent'] ?? null,
            'promotion_to_designation_id' => $validated['promotion_to_designation_id'] ?? null,
            'status' => $validated['status'] ?? 'draft',
            'notes' => $validated['notes'] ?? null,
        ]);

        $appraisal->load(['user', 'review', 'promotionToDesignation']);

        return response()->json([
            'status' => 'success',
            'data' => $appraisal,
        ], 201);
    }

    public function update(Request $request, HrmPerformanceAppraisal $performanceAppraisal)
    {
        $user = $request->user();

        if ($performanceAppraisal->clinic_id !== $user->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $user->can('view_reports') && $performanceAppraisal->user_id !== $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'effective_date' => 'nullable|date',
            'current_salary' => 'nullable|numeric',
            'new_salary' => 'nullable|numeric',
            'salary_change_amount' => 'nullable|numeric',
            'salary_change_percent' => 'nullable|numeric',
            'promotion_to_designation_id' => 'nullable|integer|exists:designations,id',
            'status' => 'nullable|in:draft,recommended,approved,rejected',
            'notes' => 'nullable|string',
        ]);

        $performanceAppraisal->update($validated);

        $performanceAppraisal->load(['user', 'review', 'promotionToDesignation']);

        return response()->json([
            'status' => 'success',
            'data' => $performanceAppraisal,
        ]);
    }

    public function destroy(Request $request, HrmPerformanceAppraisal $performanceAppraisal)
    {
        $user = $request->user();

        if ($performanceAppraisal->clinic_id !== $user->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $user->can('view_reports') && $performanceAppraisal->user_id !== $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $performanceAppraisal->delete();

        return response()->json([
            'status' => 'success',
        ]);
    }
}
