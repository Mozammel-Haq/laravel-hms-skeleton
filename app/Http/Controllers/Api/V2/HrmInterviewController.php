<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\HrmInterview;
use Illuminate\Http\Request;

class HrmInterviewController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (! $user->can('view_staff') && ! $user->can('view_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $query = HrmInterview::query()
            ->where('clinic_id', $user->clinic_id)
            ->with(['candidate', 'jobPost'])
            ->orderByDesc('scheduled_at');

        if ($status = $request->string('result')->toString()) {
            $query->where('result', $status);
        }

        if ($jobPostId = $request->input('job_post_id')) {
            $query->where('job_post_id', $jobPostId);
        }

        if ($candidateId = $request->input('candidate_id')) {
            $query->where('candidate_id', $candidateId);
        }

        if ($from = $request->date('from_date')) {
            $query->whereDate('scheduled_at', '>=', $from);
        }

        if ($to = $request->date('to_date')) {
            $query->whereDate('scheduled_at', '<=', $to);
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
            'scheduled_at' => 'required|date',
            'mode' => 'nullable|in:in_person,video,phone',
            'location' => 'nullable|string|max:255',
            'interviewer_name' => 'nullable|string|max:255',
            'interviewer_user_id' => 'nullable|exists:users,id',
            'result' => 'nullable|in:pending,shortlisted,rejected,on_hold',
            'notes' => 'nullable|string',
        ]);

        $item = HrmInterview::create([
            'clinic_id' => $user->clinic_id,
            'candidate_id' => $validated['candidate_id'],
            'job_post_id' => $validated['job_post_id'] ?? null,
            'scheduled_at' => $validated['scheduled_at'],
            'mode' => $validated['mode'] ?? 'in_person',
            'location' => $validated['location'] ?? null,
            'interviewer_name' => $validated['interviewer_name'] ?? null,
            'interviewer_user_id' => $validated['interviewer_user_id'] ?? null,
            'result' => $validated['result'] ?? 'pending',
            'notes' => $validated['notes'] ?? null,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $item->fresh(['candidate', 'jobPost']),
        ], 201);
    }

    public function update(Request $request, HrmInterview $interview)
    {
        $user = $request->user();

        if ($interview->clinic_id !== $user->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $user->can('view_staff') && ! $user->can('view_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'candidate_id' => 'nullable|exists:hrm_candidates,id',
            'job_post_id' => 'nullable|exists:hrm_job_posts,id',
            'scheduled_at' => 'nullable|date',
            'mode' => 'nullable|in:in_person,video,phone',
            'location' => 'nullable|string|max:255',
            'interviewer_name' => 'nullable|string|max:255',
            'interviewer_user_id' => 'nullable|exists:users,id',
            'result' => 'nullable|in:pending,shortlisted,rejected,on_hold',
            'notes' => 'nullable|string',
        ]);

        $interview->update($validated);

        return response()->json([
            'status' => 'success',
            'data' => $interview->fresh(['candidate', 'jobPost']),
        ]);
    }

    public function destroy(Request $request, HrmInterview $interview)
    {
        $user = $request->user();

        if ($interview->clinic_id !== $user->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $user->can('view_staff') && ! $user->can('view_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $interview->delete();

        return response()->json([
            'status' => 'success',
        ]);
    }
}

