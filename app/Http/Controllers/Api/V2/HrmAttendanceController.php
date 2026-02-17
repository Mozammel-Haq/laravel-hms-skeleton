<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\HrmHoliday;
use App\Models\HrmAttendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HrmAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $actor = $request->user();

        $query = HrmAttendance::with('user')
            ->where('clinic_id', $actor->clinic_id);

        if (! $actor->can('view_staff')) {
            $query->where('user_id', $actor->id);
        } else {
            if ($userId = $request->integer('user_id')) {
                $query->where('user_id', $userId);
            }
        }

        if ($date = $request->date('date')) {
            $query->whereDate('attendance_date', $date);
        } else {
            if ($from = $request->date('from_date')) {
                $query->whereDate('attendance_date', '>=', $from);
            }
            if ($to = $request->date('to_date')) {
                $query->whereDate('attendance_date', '<=', $to);
            }
        }

        if ($status = $request->string('status')->toString()) {
            $query->where('status', $status);
        }

        $query->orderByDesc('attendance_date')->orderBy('user_id');

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
            'attendance_date' => 'required|date',
            'check_in_time' => 'nullable|date_format:H:i',
            'check_out_time' => 'nullable|date_format:H:i',
            'status' => 'nullable|string|max:50',
            'is_late' => 'nullable|boolean',
            'is_early_exit' => 'nullable|boolean',
            'source' => 'nullable|string|max:50',
        ]);

        $targetUser = User::where('id', $validated['user_id'])
            ->where('clinic_id', $actor->clinic_id)
            ->firstOrFail();

        $existing = HrmAttendance::where('clinic_id', $actor->clinic_id)
            ->where('user_id', $targetUser->id)
            ->whereDate('attendance_date', $validated['attendance_date'])
            ->first();

        if ($existing) {
            return response()->json(['message' => 'Attendance already exists for this date'], 422);
        }

        $holiday = HrmHoliday::where('clinic_id', $actor->clinic_id)
            ->whereDate('date', $validated['attendance_date'])
            ->where('status', 'active')
            ->where('is_full_day', true)
            ->first();

        $status = $validated['status'] ?? 'present';

        if ($holiday && $status === 'present') {
            return response()->json(['message' => 'Cannot mark present on a full-day clinic holiday; use holiday or leave status instead'], 422);
        }

        $workedHours = 0;
        if (! empty($validated['check_in_time']) && ! empty($validated['check_out_time'])) {
            $in = Carbon::createFromFormat('H:i', $validated['check_in_time']);
            $out = Carbon::createFromFormat('H:i', $validated['check_out_time']);
            if ($out->lessThanOrEqualTo($in)) {
                $out->addDay();
            }
            $workedHours = round($in->diffInMinutes($out) / 60, 2);

            if ($holiday) {
                return response()->json(['message' => 'Cannot record working hours on a full-day clinic holiday'], 422);
            }
        }

        $attendance = HrmAttendance::create([
            'clinic_id' => $actor->clinic_id,
            'user_id' => $targetUser->id,
            'attendance_date' => $validated['attendance_date'],
            'check_in_time' => $validated['check_in_time'] ?? null,
            'check_out_time' => $validated['check_out_time'] ?? null,
            'worked_hours' => $workedHours,
            'status' => $status,
            'is_late' => $validated['is_late'] ?? false,
            'is_early_exit' => $validated['is_early_exit'] ?? false,
            'source' => $validated['source'] ?? 'manual',
            'meta' => null,
        ]);

        $attendance->load('user');

        return response()->json([
            'status' => 'success',
            'data' => $attendance,
        ], 201);
    }

    public function update(Request $request, HrmAttendance $attendance)
    {
        $actor = $request->user();

        if ($attendance->clinic_id !== $actor->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $actor->can('view_staff')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'check_in_time' => 'nullable|date_format:H:i',
            'check_out_time' => 'nullable|date_format:H:i',
            'status' => 'nullable|string|max:50',
            'is_late' => 'nullable|boolean',
            'is_early_exit' => 'nullable|boolean',
        ]);

        $data = $validated;

        $checkIn = $data['check_in_time'] ?? $attendance->check_in_time;
        $checkOut = $data['check_out_time'] ?? $attendance->check_out_time;

        $holiday = HrmHoliday::where('clinic_id', $actor->clinic_id)
            ->whereDate('date', $attendance->attendance_date)
            ->where('status', 'active')
            ->where('is_full_day', true)
            ->first();

        $targetStatus = $data['status'] ?? $attendance->status;

        if ($holiday && $targetStatus === 'present') {
            return response()->json(['message' => 'Cannot mark present on a full-day clinic holiday; use holiday or leave status instead'], 422);
        }

        if ($checkIn && $checkOut) {
            $in = Carbon::createFromFormat('H:i', substr((string) $checkIn, 0, 5));
            $out = Carbon::createFromFormat('H:i', substr((string) $checkOut, 0, 5));
            if ($out->lessThanOrEqualTo($in)) {
                $out->addDay();
            }
            $data['worked_hours'] = round($in->diffInMinutes($out) / 60, 2);

            if ($holiday) {
                return response()->json(['message' => 'Cannot record working hours on a full-day clinic holiday'], 422);
            }
        }

        $attendance->update($data);
        $attendance->load('user');

        return response()->json([
            'status' => 'success',
            'data' => $attendance,
        ]);
    }

    public function destroy(Request $request, HrmAttendance $attendance)
    {
        $actor = $request->user();

        if ($attendance->clinic_id !== $actor->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $actor->can('view_staff')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $attendance->delete();

        return response()->json([
            'status' => 'success',
        ]);
    }
}
