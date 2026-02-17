<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\HrmTrainingEvaluation;
use App\Models\HrmTrainingSession;
use Illuminate\Http\Request;

class HrmTrainingEvaluationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (! $user->can('view_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $query = HrmTrainingEvaluation::query()
            ->where('clinic_id', $user->clinic_id)
            ->with('session.course')
            ->orderByDesc('completed_at')
            ->orderByDesc('created_at');

        if ($sessionId = $request->input('session_id')) {
            $query->where('session_id', $sessionId);
        }

        if ($userId = $request->input('user_id')) {
            $query->where('user_id', $userId);
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
            'session_id' => 'required|integer|exists:hrm_training_sessions,id',
            'user_id' => 'required|integer|exists:users,id',
            'rating' => 'nullable|integer|min:1|max:5',
            'feedback' => 'nullable|string',
            'completed_at' => 'nullable|date',
        ]);

        $session = HrmTrainingSession::where('id', $validated['session_id'])
            ->where('clinic_id', $user->clinic_id)
            ->first();

        if (! $session) {
            return response()->json(['message' => 'Invalid session'], 422);
        }

        $item = HrmTrainingEvaluation::updateOrCreate(
            [
                'clinic_id' => $user->clinic_id,
                'session_id' => $session->id,
                'user_id' => $validated['user_id'],
            ],
            [
                'rating' => $validated['rating'] ?? null,
                'feedback' => $validated['feedback'] ?? null,
                'completed_at' => $validated['completed_at'] ?? now(),
            ]
        );

        return response()->json([
            'status' => 'success',
            'data' => $item->fresh('session.course'),
        ], 201);
    }

    public function destroy(Request $request, HrmTrainingEvaluation $trainingEvaluation)
    {
        $user = $request->user();

        if ($trainingEvaluation->clinic_id !== $user->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $user->can('view_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $trainingEvaluation->delete();

        return response()->json([
            'status' => 'success',
        ]);
    }
}

