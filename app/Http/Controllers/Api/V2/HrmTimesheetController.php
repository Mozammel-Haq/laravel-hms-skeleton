<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\HrmTimesheet;
use App\Models\User;
use Illuminate\Http\Request;

class HrmTimesheetController extends Controller
{
    public function index(Request $request)
    {
        $actor = $request->user();

        $query = HrmTimesheet::with('user')
            ->where('clinic_id', $actor->clinic_id);

        if (! $actor->can('view_staff')) {
            $query->where('user_id', $actor->id);
        } else {
            if ($userId = $request->integer('user_id')) {
                $query->where('user_id', $userId);
            }
        }

        if ($date = $request->date('date')) {
            $query->whereDate('date', $date);
        } else {
            if ($from = $request->date('from_date')) {
                $query->whereDate('date', '>=', $from);
            }
            if ($to = $request->date('to_date')) {
                $query->whereDate('date', '<=', $to);
            }
        }

        if ($status = $request->string('status')->toString()) {
            $query->where('status', $status);
        }

        $query->orderByDesc('date')->orderBy('user_id');

        $perPage = (int) $request->input('per_page', 50);
        $records = $query->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $records,
        ]);
    }

    public function store(Request $request)
    {
        $actor = $request->user();

        if (! $actor->can('view_staff')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'date' => 'required|date',
            'hours' => 'required|numeric|min:0|max:24',
            'project' => 'nullable|string|max:255',
            'task' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'nullable|in:draft,submitted,approved,rejected',
        ]);

        $targetUser = User::where('id', $validated['user_id'])
            ->where('clinic_id', $actor->clinic_id)
            ->firstOrFail();

        $timesheet = HrmTimesheet::create([
            'clinic_id' => $actor->clinic_id,
            'user_id' => $targetUser->id,
            'date' => $validated['date'],
            'hours' => $validated['hours'],
            'project' => $validated['project'] ?? null,
            'task' => $validated['task'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'status' => $validated['status'] ?? 'approved',
        ]);

        $timesheet->load('user');

        return response()->json([
            'status' => 'success',
            'data' => $timesheet,
        ], 201);
    }

    public function update(Request $request, HrmTimesheet $timesheet)
    {
        $actor = $request->user();

        if ($timesheet->clinic_id !== $actor->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $actor->can('view_staff')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'hours' => 'nullable|numeric|min:0|max:24',
            'project' => 'nullable|string|max:255',
            'task' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'nullable|in:draft,submitted,approved,rejected',
        ]);

        $timesheet->update($validated);
        $timesheet->load('user');

        return response()->json([
            'status' => 'success',
            'data' => $timesheet,
        ]);
    }

    public function destroy(Request $request, HrmTimesheet $timesheet)
    {
        $actor = $request->user();

        if ($timesheet->clinic_id !== $actor->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $actor->can('view_staff')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $timesheet->delete();

        return response()->json([
            'status' => 'success',
        ]);
    }
}

