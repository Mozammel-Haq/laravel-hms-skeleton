<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\HrmJobPost;
use Illuminate\Http\Request;

class HrmJobPostController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (! $user->can('view_staff') && ! $user->can('view_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $query = HrmJobPost::query()
            ->where('clinic_id', $user->clinic_id)
            ->orderByDesc('posted_at')
            ->orderByDesc('created_at');

        if ($status = $request->string('status')->toString()) {
            $query->where('status', $status);
        }

        if ($departmentId = $request->input('department_id')) {
            $query->where('department_id', $departmentId);
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
            'title' => 'required|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'employment_type' => 'nullable|in:full_time,part_time,contract,locum,internship',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
            'openings' => 'nullable|integer|min:1',
            'status' => 'nullable|in:draft,open,closed,archived',
            'posted_at' => 'nullable|date',
            'closes_at' => 'nullable|date',
        ]);

        $item = HrmJobPost::create([
            'clinic_id' => $user->clinic_id,
            'title' => $validated['title'],
            'department_id' => $validated['department_id'] ?? null,
            'employment_type' => $validated['employment_type'] ?? 'full_time',
            'location' => $validated['location'] ?? null,
            'description' => $validated['description'] ?? null,
            'requirements' => $validated['requirements'] ?? null,
            'openings' => $validated['openings'] ?? 1,
            'status' => $validated['status'] ?? 'draft',
            'posted_at' => $validated['posted_at'] ?? now(),
            'closes_at' => $validated['closes_at'] ?? null,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $item,
        ], 201);
    }

    public function update(Request $request, HrmJobPost $jobPost)
    {
        $user = $request->user();

        if ($jobPost->clinic_id !== $user->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $user->can('view_staff') && ! $user->can('view_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'employment_type' => 'nullable|in:full_time,part_time,contract,locum,internship',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
            'openings' => 'nullable|integer|min:1',
            'status' => 'nullable|in:draft,open,closed,archived',
            'posted_at' => 'nullable|date',
            'closes_at' => 'nullable|date',
        ]);

        $jobPost->update($validated);

        return response()->json([
            'status' => 'success',
            'data' => $jobPost,
        ]);
    }

    public function destroy(Request $request, HrmJobPost $jobPost)
    {
        $user = $request->user();

        if ($jobPost->clinic_id !== $user->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $user->can('view_staff') && ! $user->can('view_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $jobPost->status = 'archived';
        $jobPost->save();

        return response()->json([
            'status' => 'success',
        ]);
    }
}

