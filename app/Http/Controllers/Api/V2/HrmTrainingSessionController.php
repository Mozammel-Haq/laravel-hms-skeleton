<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\HrmTrainingCourse;
use App\Models\HrmTrainingSession;
use Illuminate\Http\Request;

class HrmTrainingSessionController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (! $user->can('view_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $query = HrmTrainingSession::query()
            ->where('clinic_id', $user->clinic_id)
            ->with('course')
            ->orderByDesc('start_date')
            ->orderByDesc('created_at');

        if ($status = $request->string('status')->toString()) {
            $query->where('status', $status);
        }

        if ($courseId = $request->input('course_id')) {
            $query->where('course_id', $courseId);
        }

        if ($from = $request->date('from_date')) {
            $query->whereDate('start_date', '>=', $from);
        }

        if ($to = $request->date('to_date')) {
            $query->whereDate('start_date', '<=', $to);
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
            'course_id' => 'required|integer|exists:hrm_training_courses,id',
            'facilitator_user_id' => 'nullable|integer|exists:users,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'status' => 'nullable|in:scheduled,ongoing,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        $course = HrmTrainingCourse::where('id', $validated['course_id'])
            ->where('clinic_id', $user->clinic_id)
            ->first();

        if (! $course) {
            return response()->json(['message' => 'Invalid course'], 422);
        }

        $item = HrmTrainingSession::create([
            'clinic_id' => $user->clinic_id,
            'course_id' => $course->id,
            'facilitator_user_id' => $validated['facilitator_user_id'] ?? null,
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'location' => $validated['location'] ?? null,
            'capacity' => $validated['capacity'] ?? null,
            'status' => $validated['status'] ?? 'scheduled',
            'notes' => $validated['notes'] ?? null,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $item->fresh('course'),
        ], 201);
    }

    public function update(Request $request, HrmTrainingSession $trainingSession)
    {
        $user = $request->user();

        if ($trainingSession->clinic_id !== $user->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $user->can('view_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'course_id' => 'nullable|integer|exists:hrm_training_courses,id',
            'facilitator_user_id' => 'nullable|integer|exists:users,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'status' => 'nullable|in:scheduled,ongoing,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        if (isset($validated['course_id'])) {
            $course = HrmTrainingCourse::where('id', $validated['course_id'])
                ->where('clinic_id', $user->clinic_id)
                ->first();

            if (! $course) {
                return response()->json(['message' => 'Invalid course'], 422);
            }
        }

        $trainingSession->update($validated);

        return response()->json([
            'status' => 'success',
            'data' => $trainingSession->fresh('course'),
        ]);
    }

    public function destroy(Request $request, HrmTrainingSession $trainingSession)
    {
        $user = $request->user();

        if ($trainingSession->clinic_id !== $user->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $user->can('view_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $trainingSession->delete();

        return response()->json([
            'status' => 'success',
        ]);
    }
}

