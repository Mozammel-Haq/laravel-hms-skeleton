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

        $requestedUserId = $request->input('user_id');

        if ($requestedUserId) {
            if (! $user->can('view_reports')) {
                $requestedUserId = $user->id;
            }

            $query->where('user_id', $requestedUserId);
        } elseif (! $user->can('view_reports')) {
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

        $this->recalculateSalaryFields($validated);

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

        $this->applyAppraisalToUserSalary($appraisal, $user);

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

        $this->recalculateSalaryFields($validated);

        $performanceAppraisal->update($validated);

        $this->applyAppraisalToUserSalary($performanceAppraisal, $user);

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

    private function recalculateSalaryFields(array &$data): void
    {
        $hasCurrent = array_key_exists('current_salary', $data) && $data['current_salary'] !== null;
        $hasNew = array_key_exists('new_salary', $data) && $data['new_salary'] !== null;
        $hasAmount = array_key_exists('salary_change_amount', $data) && $data['salary_change_amount'] !== null;
        $hasPercent = array_key_exists('salary_change_percent', $data) && $data['salary_change_percent'] !== null;

        if (! $hasCurrent) {
            return;
        }

        $current = (float) $data['current_salary'];

        if ($current === 0.0) {
            if ($hasNew && ! $hasAmount) {
                $data['salary_change_amount'] = (float) $data['new_salary'];
            }

            return;
        }

        if ($hasCurrent && $hasNew) {
            $amount = (float) $data['new_salary'] - $current;
            $percent = $amount / $current * 100;

            $data['salary_change_amount'] = round($amount, 2);
            $data['salary_change_percent'] = round($percent, 2);

            return;
        }

        if ($hasCurrent && $hasAmount && ! $hasNew) {
            $amount = (float) $data['salary_change_amount'];
            $new = $current + $amount;
            $percent = $amount / $current * 100;

            $data['new_salary'] = round($new, 2);
            $data['salary_change_percent'] = round($percent, 2);

            return;
        }

        if ($hasCurrent && $hasPercent && ! $hasNew) {
            $percent = (float) $data['salary_change_percent'];
            $amount = $current * $percent / 100;
            $new = $current + $amount;

            $data['salary_change_amount'] = round($amount, 2);
            $data['new_salary'] = round($new, 2);
        }
    }

    private function applyAppraisalToUserSalary(HrmPerformanceAppraisal $appraisal, User $actor): void
    {
        if (! $actor->can('view_reports')) {
            return;
        }

        if ($appraisal->status !== 'approved') {
            return;
        }

        if ($appraisal->new_salary === null) {
            return;
        }

        $targetUser = $appraisal->user;

        if (! $targetUser || $targetUser->clinic_id !== $actor->clinic_id) {
            return;
        }

        $targetUser->basic_salary_override = $appraisal->new_salary;

        if ($appraisal->promotion_to_designation_id) {
            $targetUser->designation_id = $appraisal->promotion_to_designation_id;
        }

        $targetUser->save();
    }
}
