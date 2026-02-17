<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\HrmAttendance;
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

        $attendance = HrmAttendance::where('clinic_id', $actor->clinic_id)
            ->where('user_id', $targetUser->id)
            ->whereDate('attendance_date', $validated['date'])
            ->first();

        if (! $attendance) {
            return response()->json(['message' => 'Attendance record is required for this date before adding timesheet'], 422);
        }

        $existingHours = HrmTimesheet::where('clinic_id', $actor->clinic_id)
            ->where('user_id', $targetUser->id)
            ->whereDate('date', $validated['date'])
            ->sum('hours');

        $newTotalHours = $existingHours + (float) $validated['hours'];

        $maxHours = (float) ($attendance->worked_hours ?? 24);

        if ($maxHours <= 0) {
            return response()->json(['message' => 'Cannot log timesheet hours when worked hours are zero for this date'], 422);
        }

        if ($newTotalHours > $maxHours) {
            return response()->json(['message' => 'Total timesheet hours cannot exceed worked hours recorded in attendance'], 422);
        }

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

        if (array_key_exists('hours', $validated)) {
            $attendance = HrmAttendance::where('clinic_id', $actor->clinic_id)
                ->where('user_id', $timesheet->user_id)
                ->whereDate('attendance_date', $timesheet->date)
                ->first();

            if (! $attendance) {
                return response()->json(['message' => 'Attendance record is required for this date before updating timesheet'], 422);
            }

            $otherHours = HrmTimesheet::where('clinic_id', $actor->clinic_id)
                ->where('user_id', $timesheet->user_id)
                ->whereDate('date', $timesheet->date)
                ->where('id', '!=', $timesheet->id)
                ->sum('hours');

            $newTotalHours = $otherHours + (float) $validated['hours'];

            $maxHours = (float) ($attendance->worked_hours ?? 24);

            if ($maxHours <= 0) {
                return response()->json(['message' => 'Cannot log timesheet hours when worked hours are zero for this date'], 422);
            }

            if ($newTotalHours > $maxHours) {
                return response()->json(['message' => 'Total timesheet hours cannot exceed worked hours recorded in attendance'], 422);
            }
        }

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
