<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\HrmJobOffer;
use Illuminate\Http\Request;

class HrmJobOfferController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (! $user->can('view_staff') && ! $user->can('view_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $query = HrmJobOffer::query()
            ->where('clinic_id', $user->clinic_id)
            ->with(['candidate', 'jobPost'])
            ->orderByDesc('created_at');

        if ($status = $request->string('status')->toString()) {
            $query->where('status', $status);
        }

        if ($jobPostId = $request->input('job_post_id')) {
            $query->where('job_post_id', $jobPostId);
        }

        if ($candidateId = $request->input('candidate_id')) {
            $query->where('candidate_id', $candidateId);
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
            'candidate_id' => 'required|exists:hrm_candidates,id',
            'job_post_id' => 'nullable|exists:hrm_job_posts,id',
            'offered_role' => 'required|string|max:255',
            'salary_offered' => 'nullable|numeric|min:0',
            'joining_date' => 'nullable|date',
            'status' => 'nullable|in:draft,sent,accepted,rejected,withdrawn',
            'notes' => 'nullable|string',
        ]);

        $item = HrmJobOffer::create([
            'clinic_id' => $user->clinic_id,
            'candidate_id' => $validated['candidate_id'],
            'job_post_id' => $validated['job_post_id'] ?? null,
            'offered_role' => $validated['offered_role'],
            'salary_offered' => $validated['salary_offered'] ?? 0,
            'joining_date' => $validated['joining_date'] ?? null,
            'status' => $validated['status'] ?? 'draft',
            'notes' => $validated['notes'] ?? null,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $item->fresh(['candidate', 'jobPost']),
        ], 201);
    }

    public function update(Request $request, HrmJobOffer $offer)
    {
        $user = $request->user();

        if ($offer->clinic_id !== $user->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $user->can('view_staff') && ! $user->can('view_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'candidate_id' => 'nullable|exists:hrm_candidates,id',
            'job_post_id' => 'nullable|exists:hrm_job_posts,id',
            'offered_role' => 'nullable|string|max:255',
            'salary_offered' => 'nullable|numeric|min:0',
            'joining_date' => 'nullable|date',
            'status' => 'nullable|in:draft,sent,accepted,rejected,withdrawn',
            'notes' => 'nullable|string',
        ]);

        $offer->update($validated);

        return response()->json([
            'status' => 'success',
            'data' => $offer->fresh(['candidate', 'jobPost']),
        ]);
    }

    public function destroy(Request $request, HrmJobOffer $offer)
    {
        $user = $request->user();

        if ($offer->clinic_id !== $user->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $user->can('view_staff') && ! $user->can('view_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $offer->delete();

        return response()->json([
            'status' => 'success',
        ]);
    }
}

