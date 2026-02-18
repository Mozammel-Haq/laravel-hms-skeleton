<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\HrmPerformanceReview;
use App\Models\User;
use Illuminate\Http\Request;

class HrmPerformanceReviewController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $query = HrmPerformanceReview::query()
            ->where('clinic_id', $user->clinic_id)
            ->with(['user', 'reviewer'])
            ->orderByDesc('created_at');

        if (! $user->can('view_reports')) {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhere('reviewer_user_id', $user->id);
            });
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
            'reviewer_user_id' => 'nullable|integer|exists:users,id',
            'period_start' => 'nullable|date',
            'period_end' => 'nullable|date|after_or_equal:period_start',
            'overall_rating' => 'nullable|integer|min:1|max:5',
            'summary' => 'nullable|string',
            'status' => 'nullable|in:draft,submitted,finalized',
        ]);

        $targetUserId = $validated['user_id'] ?? null;
        if (! $targetUserId) {
            $targetUserId = $user->id;
        }

        $targetUser = User::whereKey($targetUserId)
            ->where('clinic_id', $user->clinic_id)
            ->first();

        if (! $targetUser) {
            return response()->json(['message' => 'Invalid user for this clinic'], 422);
        }

        $reviewerId = $validated['reviewer_user_id'] ?? null;
        if (! $reviewerId) {
            $reviewerId = $user->id;
        }

        $reviewer = User::whereKey($reviewerId)
            ->where('clinic_id', $user->clinic_id)
            ->first();

        if (! $reviewer) {
            return response()->json(['message' => 'Invalid reviewer for this clinic'], 422);
        }

        if (! $user->can('view_reports') && $targetUser->id !== $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $review = HrmPerformanceReview::create([
            'clinic_id' => $user->clinic_id,
            'user_id' => $targetUser->id,
            'reviewer_user_id' => $reviewer->id,
            'period_start' => $validated['period_start'] ?? null,
            'period_end' => $validated['period_end'] ?? null,
            'overall_rating' => $validated['overall_rating'] ?? null,
            'summary' => $validated['summary'] ?? null,
            'status' => $validated['status'] ?? 'draft',
        ]);

        $review->load(['user', 'reviewer']);

        return response()->json([
            'status' => 'success',
            'data' => $review,
        ], 201);
    }

    public function update(Request $request, HrmPerformanceReview $performanceReview)
    {
        $user = $request->user();

        if ($performanceReview->clinic_id !== $user->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $user->can('view_reports') && $performanceReview->user_id !== $user->id && $performanceReview->reviewer_user_id !== $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'period_start' => 'nullable|date',
            'period_end' => 'nullable|date|after_or_equal:period_start',
            'overall_rating' => 'nullable|integer|min:1|max:5',
            'summary' => 'nullable|string',
            'status' => 'nullable|in:draft,submitted,finalized',
        ]);

        $performanceReview->update($validated);

        $performanceReview->load(['user', 'reviewer']);

        return response()->json([
            'status' => 'success',
            'data' => $performanceReview,
        ]);
    }

    public function destroy(Request $request, HrmPerformanceReview $performanceReview)
    {
        $user = $request->user();

        if ($performanceReview->clinic_id !== $user->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $user->can('view_reports') && $performanceReview->user_id !== $user->id && $performanceReview->reviewer_user_id !== $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $performanceReview->delete();

        return response()->json([
            'status' => 'success',
        ]);
    }
}
