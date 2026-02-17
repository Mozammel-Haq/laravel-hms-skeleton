<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\HrmCandidate;
use Illuminate\Http\Request;

class HrmCandidateController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (! $user->can('view_staff') && ! $user->can('view_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $query = HrmCandidate::query()
            ->where('clinic_id', $user->clinic_id)
            ->with('jobPost')
            ->orderByDesc('created_at');

        if ($status = $request->string('status')->toString()) {
            $query->where('status', $status);
        }

        if ($jobPostId = $request->input('job_post_id')) {
            $query->where('job_post_id', $jobPostId);
        }

        if ($search = $request->string('search')->toString()) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%')
                    ->orWhere('phone', 'like', '%'.$search.'%');
            });
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
            'job_post_id' => 'nullable|exists:hrm_job_posts,id',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'source' => 'nullable|string|max:100',
            'resume_url' => 'nullable|string|max:500',
            'notes' => 'nullable|string',
            'status' => 'nullable|in:new,screening,interview,offered,hired,rejected,archived',
        ]);

        $item = HrmCandidate::create([
            'clinic_id' => $user->clinic_id,
            'job_post_id' => $validated['job_post_id'] ?? null,
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'source' => $validated['source'] ?? null,
            'resume_url' => $validated['resume_url'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'status' => $validated['status'] ?? 'new',
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $item->fresh('jobPost'),
        ], 201);
    }

    public function update(Request $request, HrmCandidate $candidate)
    {
        $user = $request->user();

        if ($candidate->clinic_id !== $user->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $user->can('view_staff') && ! $user->can('view_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'job_post_id' => 'nullable|exists:hrm_job_posts,id',
            'name' => 'sometimes|required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'source' => 'nullable|string|max:100',
            'resume_url' => 'nullable|string|max:500',
            'notes' => 'nullable|string',
            'status' => 'nullable|in:new,screening,interview,offered,hired,rejected,archived',
        ]);

        $candidate->update($validated);

        return response()->json([
            'status' => 'success',
            'data' => $candidate->fresh('jobPost'),
        ]);
    }

    public function destroy(Request $request, HrmCandidate $candidate)
    {
        $user = $request->user();

        if ($candidate->clinic_id !== $user->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $user->can('view_staff') && ! $user->can('view_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $candidate->status = 'archived';
        $candidate->save();

        return response()->json([
            'status' => 'success',
        ]);
    }
}

