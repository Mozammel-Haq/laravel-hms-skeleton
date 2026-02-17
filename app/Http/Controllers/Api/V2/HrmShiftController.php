<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\HrmShift;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HrmShiftController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $query = HrmShift::query()
            ->where('clinic_id', $user->clinic_id)
            ->orderBy('name');

        if ($status = $request->string('status')->toString()) {
            $query->where('status', $status);
        }

        $perPage = (int) $request->input('per_page', 20);
        $shifts = $query->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $shifts,
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        if (! $user->can('create_staff')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:hrm_shifts,code',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'break_minutes' => 'nullable|integer|min:0|max:600',
            'status' => 'nullable|in:active,inactive',
        ]);

        $start = Carbon::createFromFormat('H:i', $validated['start_time']);
        $end = Carbon::createFromFormat('H:i', $validated['end_time']);
        if ($end->lessThanOrEqualTo($start)) {
            $end->addDay();
        }
        $durationMinutes = $start->diffInMinutes($end);
        if ($durationMinutes <= 0 || $durationMinutes > 24 * 60) {
            return response()->json(['message' => 'End time must be after start time within 24 hours'], 422);
        }

        $shift = HrmShift::create([
            'clinic_id' => $user->clinic_id,
            'name' => $validated['name'],
            'code' => $validated['code'] ?? null,
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'break_minutes' => $validated['break_minutes'] ?? 0,
            'status' => $validated['status'] ?? 'active',
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $shift,
        ], 201);
    }

    public function update(Request $request, HrmShift $shift)
    {
        $user = $request->user();

        if ($shift->clinic_id !== $user->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $user->can('create_staff')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'code' => 'nullable|string|max:50|unique:hrm_shifts,code,' . $shift->id,
            'start_time' => 'sometimes|required|date_format:H:i',
            'end_time' => 'sometimes|required|date_format:H:i',
            'break_minutes' => 'nullable|integer|min:0|max:600',
            'status' => 'nullable|in:active,inactive',
        ]);

        $startForCheck = $validated['start_time'] ?? $shift->start_time;
        $endForCheck = $validated['end_time'] ?? $shift->end_time;
        if ($startForCheck && $endForCheck) {
            $start = Carbon::createFromFormat('H:i', substr((string) $startForCheck, 0, 5));
            $end = Carbon::createFromFormat('H:i', substr((string) $endForCheck, 0, 5));
            if ($end->lessThanOrEqualTo($start)) {
                $end->addDay();
            }
            $durationMinutes = $start->diffInMinutes($end);
            if ($durationMinutes <= 0 || $durationMinutes > 24 * 60) {
                return response()->json(['message' => 'End time must be after start time within 24 hours'], 422);
            }
        }

        $shift->update($validated);

        return response()->json([
            'status' => 'success',
            'data' => $shift,
        ]);
    }

    public function destroy(Request $request, HrmShift $shift)
    {
        $user = $request->user();

        if ($shift->clinic_id !== $user->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $user->can('create_staff')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $shift->status = 'inactive';
        $shift->save();

        return response()->json([
            'status' => 'success',
        ]);
    }
}
