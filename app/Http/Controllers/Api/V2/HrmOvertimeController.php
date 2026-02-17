<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\HrmAttendance;
use App\Models\HrmOvertime;
use App\Models\User;
use Illuminate\Http\Request;

class HrmOvertimeController extends Controller
{
    public function index(Request $request)
    {
        $actor = $request->user();

        $query = HrmOvertime::with('user')
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
            'multiplier' => 'nullable|numeric|min:1|max:5',
            'reason' => 'nullable|string|max:255',
            'status' => 'nullable|in:pending,approved,rejected',
        ]);

        $targetUser = User::where('id', $validated['user_id'])
            ->where('clinic_id', $actor->clinic_id)
            ->firstOrFail();

        $attendance = HrmAttendance::where('clinic_id', $actor->clinic_id)
            ->where('user_id', $targetUser->id)
            ->whereDate('attendance_date', $validated['date'])
            ->first();

        if (! $attendance) {
            return response()->json(['message' => 'Attendance record is required for this date before adding overtime'], 422);
        }

        if (($attendance->worked_hours ?? 0) <= 0) {
            return response()->json(['message' => 'Cannot log overtime when worked hours are zero for this date'], 422);
        }

        $existingOvertime = HrmOvertime::where('clinic_id', $actor->clinic_id)
            ->where('user_id', $targetUser->id)
            ->whereDate('date', $validated['date'])
            ->sum('hours');

        $newTotalOvertime = $existingOvertime + (float) $validated['hours'];

        if ($newTotalOvertime > 24) {
            return response()->json(['message' => 'Total overtime hours for this date cannot exceed 24'], 422);
        }

        $record = HrmOvertime::create([
            'clinic_id' => $actor->clinic_id,
            'user_id' => $targetUser->id,
            'date' => $validated['date'],
            'hours' => $validated['hours'],
            'multiplier' => $validated['multiplier'] ?? 1.5,
            'reason' => $validated['reason'] ?? null,
            'status' => $validated['status'] ?? 'approved',
        ]);

        $record->load('user');

        return response()->json([
            'status' => 'success',
            'data' => $record,
        ], 201);
    }

    public function update(Request $request, HrmOvertime $overtime)
    {
        $actor = $request->user();

        if ($overtime->clinic_id !== $actor->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $actor->can('view_staff')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'hours' => 'nullable|numeric|min:0|max:24',
            'multiplier' => 'nullable|numeric|min:1|max:5',
            'reason' => 'nullable|string|max:255',
            'status' => 'nullable|in:pending,approved,rejected',
        ]);

        if (array_key_exists('hours', $validated)) {
            $attendance = HrmAttendance::where('clinic_id', $actor->clinic_id)
                ->where('user_id', $overtime->user_id)
                ->whereDate('attendance_date', $overtime->date)
                ->first();

            if (! $attendance) {
                return response()->json(['message' => 'Attendance record is required for this date before updating overtime'], 422);
            }

            if (($attendance->worked_hours ?? 0) <= 0) {
                return response()->json(['message' => 'Cannot log overtime when worked hours are zero for this date'], 422);
            }

            $otherOvertime = HrmOvertime::where('clinic_id', $actor->clinic_id)
                ->where('user_id', $overtime->user_id)
                ->whereDate('date', $overtime->date)
                ->where('id', '!=', $overtime->id)
                ->sum('hours');

            $newTotalOvertime = $otherOvertime + (float) $validated['hours'];

            if ($newTotalOvertime > 24) {
                return response()->json(['message' => 'Total overtime hours for this date cannot exceed 24'], 422);
            }
        }

        $overtime->update($validated);
        $overtime->load('user');

        return response()->json([
            'status' => 'success',
            'data' => $overtime,
        ]);
    }

    public function destroy(Request $request, HrmOvertime $overtime)
    {
        $actor = $request->user();

        if ($overtime->clinic_id !== $actor->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $actor->can('view_staff')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $overtime->delete();

        return response()->json([
            'status' => 'success',
        ]);
    }
}
