<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\DoctorScheduleException;
use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveRequestController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $query = LeaveRequest::with('user')
            ->whereHas('user', function ($q) use ($user) {
                $q->where('clinic_id', $user->clinic_id);
            });

        if (! $user->can('manage_leaves')) {
            $query->where('user_id', $user->id);
        }

        $query->latest();

        if ($status = $request->string('status')->toString()) {
            $query->where('status', $status);
        }

        $perPage = (int) $request->input('per_page', 10);
        $requests = $query->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $requests,
        ]);
    }

    public function store(Request $request)
    {
        $actor = $request->user();

        $validated = $request->validate([
            'type' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
            'user_id' => 'nullable|integer|exists:users,id',
        ]);

        $targetUserId = $actor->id;

        if ($actor->can('manage_leaves') && ! empty($validated['user_id'])) {
            $targetUser = User::where('id', $validated['user_id'])
                ->where('clinic_id', $actor->clinic_id)
                ->firstOrFail();

            $targetUserId = $targetUser->id;
        }

        $leave = LeaveRequest::create([
            'user_id' => $targetUserId,
            'leave_type' => $validated['type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        $leave->load('user');

        return response()->json([
            'status' => 'success',
            'data' => $leave,
        ], 201);
    }

    public function show(Request $request, LeaveRequest $leave)
    {
        $user = $request->user();

        if ($leave->user && $leave->user->clinic_id !== $user->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $user->can('manage_leaves') && $leave->user_id !== $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $leave->load('user');

        return response()->json([
            'status' => 'success',
            'data' => $leave,
        ]);
    }

    public function update(Request $request, LeaveRequest $leave)
    {
        $actor = $request->user();

        if (! $actor->can('manage_leaves')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        if ($leave->user && $leave->user->clinic_id !== $actor->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $leave->status = $validated['status'];
        $leave->save();

        $leave->load('user');

        if ($leave->status === 'approved' && $leave->user && $leave->user->doctor) {
            $doctor = $leave->user->doctor;

            $existing = DoctorScheduleException::where('doctor_id', $doctor->id)
                ->where('clinic_id', $actor->clinic_id)
                ->where('start_date', $leave->start_date)
                ->where('end_date', $leave->end_date)
                ->where('reason', 'like', '%[LeaveRequest #'.$leave->id.']%')
                ->first();

            if (! $existing) {
                $baseReason = $leave->reason ?: 'Leave';
                $type = $leave->leave_type ?: 'general';

                DoctorScheduleException::create([
                    'doctor_id' => $doctor->id,
                    'clinic_id' => $actor->clinic_id,
                    'start_date' => $leave->start_date,
                    'end_date' => $leave->end_date,
                    'is_available' => false,
                    'start_time' => null,
                    'end_time' => null,
                    'reason' => 'Leave ('.$type.') via HRM: '.$baseReason.' [LeaveRequest #'.$leave->id.']',
                    'status' => 'approved',
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => $leave,
        ]);
    }
}
