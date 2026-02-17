<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\HrmLeaveBalance;
use App\Models\User;
use Illuminate\Http\Request;

class HrmLeaveBalanceController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $query = HrmLeaveBalance::with('user')
            ->where('clinic_id', $user->clinic_id);

        if (! $user->can('manage_leaves')) {
            $query->where('user_id', $user->id);
        } else {
            if ($userId = $request->integer('user_id')) {
                $query->where('user_id', $userId);
            }
        }

        if ($leaveType = $request->string('leave_type')->toString()) {
            $query->where('leave_type', $leaveType);
        }

        if ($year = $request->integer('year')) {
            $query->where('year', $year);
        }

        if ($status = $request->string('status')->toString()) {
            $query->where('status', $status);
        }

        $query->orderByDesc('year')->orderBy('leave_type');

        $perPage = (int) $request->input('per_page', 20);
        $balances = $query->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $balances,
        ]);
    }

    public function store(Request $request)
    {
        $actor = $request->user();

        if (! $actor->can('manage_leaves')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'leave_type' => 'required|string|max:100',
            'year' => 'required|integer|min:2000|max:2100',
            'opening_balance' => 'nullable|numeric|min:0|max:365',
            'accrued' => 'nullable|numeric|min:0|max:365',
            'used' => 'nullable|numeric|min:0|max:365',
            'closing_balance' => 'nullable|numeric|min:0|max:365',
            'status' => 'nullable|in:active,inactive',
        ]);

        $targetUser = User::where('id', $validated['user_id'])
            ->where('clinic_id', $actor->clinic_id)
            ->firstOrFail();

        $exists = HrmLeaveBalance::where('clinic_id', $actor->clinic_id)
            ->where('user_id', $targetUser->id)
            ->where('leave_type', $validated['leave_type'])
            ->where('year', $validated['year'])
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Balance already exists for this user, type, and year'], 422);
        }

        $opening = $validated['opening_balance'] ?? 0;
        $accrued = $validated['accrued'] ?? 0;
        $used = $validated['used'] ?? 0;
        $closing = $validated['closing_balance'] ?? ($opening + $accrued - $used);

        $balance = HrmLeaveBalance::create([
            'clinic_id' => $actor->clinic_id,
            'user_id' => $targetUser->id,
            'leave_type' => $validated['leave_type'],
            'year' => $validated['year'],
            'opening_balance' => $opening,
            'accrued' => $accrued,
            'used' => $used,
            'closing_balance' => $closing,
            'status' => $validated['status'] ?? 'active',
        ]);

        $balance->load('user');

        return response()->json([
            'status' => 'success',
            'data' => $balance,
        ], 201);
    }

    public function update(Request $request, HrmLeaveBalance $leaveBalance)
    {
        $actor = $request->user();

        if ($leaveBalance->clinic_id !== $actor->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $actor->can('manage_leaves')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'opening_balance' => 'nullable|numeric|min:0|max:365',
            'accrued' => 'nullable|numeric|min:0|max:365',
            'used' => 'nullable|numeric|min:0|max:365',
            'closing_balance' => 'nullable|numeric|min:0|max:365',
            'status' => 'nullable|in:active,inactive',
        ]);

        $opening = $validated['opening_balance'] ?? $leaveBalance->opening_balance;
        $accrued = $validated['accrued'] ?? $leaveBalance->accrued;
        $used = $validated['used'] ?? $leaveBalance->used;

        if (! array_key_exists('closing_balance', $validated)) {
            $validated['closing_balance'] = $opening + $accrued - $used;
        }

        $leaveBalance->update($validated);
        $leaveBalance->load('user');

        return response()->json([
            'status' => 'success',
            'data' => $leaveBalance,
        ]);
    }

    public function destroy(Request $request, HrmLeaveBalance $leaveBalance)
    {
        $actor = $request->user();

        if ($leaveBalance->clinic_id !== $actor->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $actor->can('manage_leaves')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $leaveBalance->status = 'inactive';
        $leaveBalance->save();

        return response()->json([
            'status' => 'success',
        ]);
    }
}

