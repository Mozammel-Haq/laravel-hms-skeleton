<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\HrmTrainingCourse;
use Illuminate\Http\Request;

class HrmTrainingCourseController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (! $user->can('view_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $query = HrmTrainingCourse::query()
            ->where('clinic_id', $user->clinic_id)
            ->orderByDesc('created_at');

        if ($status = $request->string('status')->toString()) {
            $query->where('status', $status);
        }

        if ($search = $request->string('search')->toString()) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%'.$search.'%')
                    ->orWhere('code', 'like', '%'.$search.'%')
                    ->orWhere('category', 'like', '%'.$search.'%');
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

        if (! $user->can('view_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'code' => 'nullable|string|max:100',
            'category' => 'nullable|string|max:100',
            'target_role' => 'nullable|string|max:100',
            'mode' => 'nullable|in:online,classroom,blended',
            'duration_hours' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'status' => 'nullable|in:draft,active,inactive,archived',
        ]);

        $item = HrmTrainingCourse::create([
            'clinic_id' => $user->clinic_id,
            'title' => $validated['title'],
            'code' => $validated['code'] ?? null,
            'category' => $validated['category'] ?? null,
            'target_role' => $validated['target_role'] ?? null,
            'mode' => $validated['mode'] ?? 'classroom',
            'duration_hours' => $validated['duration_hours'] ?? 0,
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'] ?? 'draft',
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $item,
        ], 201);
    }

    public function update(Request $request, HrmTrainingCourse $trainingCourse)
    {
        $user = $request->user();

        if ($trainingCourse->clinic_id !== $user->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $user->can('view_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'code' => 'nullable|string|max:100',
            'category' => 'nullable|string|max:100',
            'target_role' => 'nullable|string|max:100',
            'mode' => 'nullable|in:online,classroom,blended',
            'duration_hours' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'status' => 'nullable|in:draft,active,inactive,archived',
        ]);

        $trainingCourse->update($validated);

        return response()->json([
            'status' => 'success',
            'data' => $trainingCourse,
        ]);
    }

    public function destroy(Request $request, HrmTrainingCourse $trainingCourse)
    {
        $user = $request->user();

        if ($trainingCourse->clinic_id !== $user->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $user->can('view_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $trainingCourse->status = 'archived';
        $trainingCourse->save();

        return response()->json([
            'status' => 'success',
        ]);
    }
}

