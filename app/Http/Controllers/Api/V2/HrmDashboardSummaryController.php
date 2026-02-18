<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\HrmAttendance;
use App\Models\HrmCompliancePolicy;
use App\Models\HrmJobPost;
use App\Models\HrmTrainingSession;
use App\Models\LeaveRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HrmDashboardSummaryController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (! $user->can('view_hrm_dashboard')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $clinicId = $user->clinic_id;
        $today = Carbon::today();

        $isManager = $user->can('view_staff')
            || $user->can('manage_leaves')
            || $user->can('view_reports')
            || $user->can('view_financial_reports');

        $managerSummary = null;

        if ($isManager && $clinicId) {
            $statsQuery = User::query()
                ->where('clinic_id', $clinicId);

            if (! $user->hasRole('Super Admin') && ! $user->hasRole('Clinic Admin')) {
                $statsQuery->whereDoesntHave('roles', function ($q) {
                    $q->whereIn('name', ['Super Admin', 'Clinic Admin']);
                });
            }

            $totalStaff = (clone $statsQuery)->count();

            $presentToday = HrmAttendance::query()
                ->where('clinic_id', $clinicId)
                ->whereDate('attendance_date', $today)
                ->where('status', 'present')
                ->count();

            $onLeaveToday = LeaveRequest::query()
                ->whereHas('user', function ($q) use ($clinicId) {
                    $q->where('clinic_id', $clinicId);
                })
                ->where('status', 'approved')
                ->whereDate('start_date', '<=', $today)
                ->whereDate('end_date', '>=', $today)
                ->count();

            $openPositions = HrmJobPost::query()
                ->where('clinic_id', $clinicId)
                ->where('status', 'open')
                ->count();

            $activeTrainings = HrmTrainingSession::query()
                ->where('clinic_id', $clinicId)
                ->whereIn('status', ['scheduled', 'ongoing'])
                ->whereDate('start_date', '>=', $today)
                ->count();

            $activePolicies = HrmCompliancePolicy::query()
                ->where('clinic_id', $clinicId)
                ->where('status', 'active')
                ->count();

            $managerSummary = [
                'total_staff' => $totalStaff,
                'present_today' => $presentToday,
                'on_leave_today' => $onLeaveToday,
                'open_positions' => $openPositions,
                'active_trainings' => $activeTrainings,
                'active_policies' => $activePolicies,
            ];
        }

        $staffSummary = null;
        $attendanceSeries = [];

        if ($clinicId) {
            $leaveQuery = LeaveRequest::query()
                ->where('user_id', $user->id)
                ->orderByDesc('created_at');

            $leaves = $leaveQuery->get();

            $pending = $leaves->where('status', 'pending')->count();

            $todayDate = $today->toDateString();

            $upcoming = $leaves->filter(function ($leave) use ($todayDate) {
                if (($leave->status ?? 'pending') !== 'approved') {
                    return false;
                }

                if (! $leave->start_date) {
                    return false;
                }

                return (string) $leave->start_date >= $todayDate;
            })->count();

            $lastStatusLabel = 'No requests';

            if ($leaves->count() > 0) {
                $latest = $leaves->first();
                $latestStatus = strtolower((string) ($latest->status ?? 'pending'));

                if ($latestStatus === 'approved') {
                    $lastStatusLabel = 'Approved';
                } elseif ($latestStatus === 'rejected') {
                    $lastStatusLabel = 'Rejected';
                } else {
                    $lastStatusLabel = 'Pending';
                }
            }

            $staffSummary = [
                'my_upcoming_leaves' => $upcoming,
                'my_pending_leaves' => $pending,
                'my_last_leave_status' => $lastStatusLabel,
            ];

            $fromDate = $today->copy()->subDays(6);

            $attendanceCounts = HrmAttendance::query()
                ->selectRaw('attendance_date, count(*) as present_count')
                ->where('clinic_id', $clinicId)
                ->whereBetween('attendance_date', [$fromDate->toDateString(), $today->toDateString()])
                ->where('status', 'present')
                ->groupBy('attendance_date')
                ->get()
                ->keyBy(function ($row) {
                    return Carbon::parse($row->attendance_date)->toDateString();
                });

            $totalStaffForSeries = 0;

            if ($isManager && isset($managerSummary['total_staff'])) {
                $totalStaffForSeries = (int) $managerSummary['total_staff'];
            } else {
                $totalStaffForSeries = User::query()
                    ->where('clinic_id', $clinicId)
                    ->count();
            }

            for ($i = 6; $i >= 0; $i--) {
                $date = $today->copy()->subDays($i)->toDateString();
                $presentCount = (int) optional($attendanceCounts->get($date))->present_count ?? 0;

                $attendanceSeries[] = [
                    'date' => $date,
                    'present' => $presentCount,
                    'total_staff' => $totalStaffForSeries,
                ];
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'role' => $isManager ? 'manager' : 'staff',
                'manager' => $managerSummary,
                'staff' => $staffSummary,
                'today' => $today->toDateString(),
                'attendance_timeseries' => $attendanceSeries,
            ],
        ]);
    }
}
