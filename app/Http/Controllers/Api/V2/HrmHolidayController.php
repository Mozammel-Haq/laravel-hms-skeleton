<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\HrmHoliday;
use Illuminate\Http\Request;

class HrmHolidayController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (! $user->can('manage_leaves')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $query = HrmHoliday::query()
            ->where('clinic_id', $user->clinic_id)
            ->orderBy('date');

        if ($year = $request->integer('year')) {
            $query->whereYear('date', $year);
        }

        if ($month = $request->integer('month')) {
            $query->whereMonth('date', $month);
        }

        if ($status = $request->string('status')->toString()) {
            $query->where('status', $status);
        }

        $holidays = $query->get();

        return response()->json([
            'status' => 'success',
            'data' => $holidays,
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        if (! $user->can('manage_leaves')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'date' => 'required|date',
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|max:50',
            'is_full_day' => 'nullable|boolean',
            'status' => 'nullable|in:active,inactive',
        ]);

        $exists = HrmHoliday::where('clinic_id', $user->clinic_id)
            ->where('date', $validated['date'])
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Holiday already exists for this date'], 422);
        }

        $holiday = HrmHoliday::create([
            'clinic_id' => $user->clinic_id,
            'date' => $validated['date'],
            'name' => $validated['name'],
            'type' => $validated['type'] ?? 'public',
            'is_full_day' => $validated['is_full_day'] ?? true,
            'status' => $validated['status'] ?? 'active',
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $holiday,
        ], 201);
    }

    public function update(Request $request, HrmHoliday $holiday)
    {
        $user = $request->user();

        if ($holiday->clinic_id !== $user->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $user->can('manage_leaves')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'date' => 'sometimes|required|date',
            'name' => 'sometimes|required|string|max:255',
            'type' => 'nullable|string|max:50',
            'is_full_day' => 'nullable|boolean',
            'status' => 'nullable|in:active,inactive',
        ]);

        if (isset($validated['date']) && $validated['date'] !== $holiday->date->toDateString()) {
            $exists = HrmHoliday::where('clinic_id', $user->clinic_id)
                ->where('date', $validated['date'])
                ->where('id', '!=', $holiday->id)
                ->exists();
            if ($exists) {
                return response()->json(['message' => 'Holiday already exists for this date'], 422);
            }
        }

        $holiday->update($validated);

        return response()->json([
            'status' => 'success',
            'data' => $holiday,
        ]);
    }

    public function destroy(Request $request, HrmHoliday $holiday)
    {
        $user = $request->user();

        if ($holiday->clinic_id !== $user->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $user->can('manage_leaves')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $holiday->status = 'inactive';
        $holiday->save();

        return response()->json([
            'status' => 'success',
        ]);
    }
}

