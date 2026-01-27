<?php

namespace App\Http\Controllers;

use App\Models\DoctorSchedule;
use App\Models\DoctorScheduleRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class DoctorScheduleRequestAdminController extends Controller
{
    public function index()
    {
        Gate::authorize('manage_doctor_schedule');

        $requests = DoctorScheduleRequest::with('doctor.user');

        if (request()->filled('status')) {
            if (request('status') !== 'all') {
                $requests->where('status', request('status'));
            }
        } else {
            $requests->where('status', 'pending');
        }

        if (request()->filled('search')) {
            $search = request('search');
            $requests->where(function ($q) use ($search) {
                $q->whereHas('doctor.user', function ($sub) use ($search) {
                    $sub->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                });
            });
        }

        if (request()->filled('from')) {
            $requests->whereDate('created_at', '>=', request('from'));
        }

        if (request()->filled('to')) {
            $requests->whereDate('created_at', '<=', request('to'));
        }

        $requests = $requests
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.schedule.requests.index', compact('requests'));
    }

    public function approve(Request $request, DoctorScheduleRequest $scheduleRequest)
    {
        Gate::authorize('manage_doctor_schedule');

        $doctor = $scheduleRequest->doctor;
        $clinicId = $scheduleRequest->clinic_id;
        $schedules = $scheduleRequest->schedules ?? [];

        // Check for conflicts before approving
        foreach ($schedules as $item) {
            if (!isset($item['start_time']) || !isset($item['end_time']) || !isset($item['type'])) {
                continue;
            }

            $start = $item['start_time'];
            $end = $item['end_time'];
            $type = $item['type'];

            $conflicts = DoctorSchedule::withoutTenant()
                ->where('doctor_id', $doctor->id)
                ->where('clinic_id', '!=', $clinicId)
                ->where(function ($q) use ($start, $end) {
                    $q->where('start_time', '<', $end)
                        ->where('end_time', '>', $start);
                })
                ->with('clinic')
                ->get();

            foreach ($conflicts as $conflict) {
                $isConflict = false;

                if ($type === 'weekly') {
                    if (!isset($item['day_of_week'])) continue;
                    $myDay = $item['day_of_week'];

                    if ($conflict->schedule_date) {
                        if (Carbon::parse($conflict->schedule_date)->dayOfWeek == $myDay) {
                            $isConflict = true;
                        }
                    } else {
                        if ($conflict->day_of_week == $myDay) {
                            $isConflict = true;
                        }
                    }
                } else {
                    if (!isset($item['schedule_date'])) continue;
                    $myDate = $item['schedule_date'];

                    if ($conflict->schedule_date) {
                        if ($conflict->schedule_date == $myDate) {
                            $isConflict = true;
                        }
                    } else {
                        if (Carbon::parse($myDate)->dayOfWeek == $conflict->day_of_week) {
                            $isConflict = true;
                        }
                    }
                }

                if ($isConflict) {
                    $clinicName = $conflict->clinic->name ?? 'another clinic';
                    $conflictDesc = $conflict->schedule_date ? $conflict->schedule_date : "Weekly (Day {$conflict->day_of_week})";
                    return back()->with('error', "Cannot approve: Schedule overlaps with {$clinicName} ({$conflictDesc} {$conflict->start_time}-{$conflict->end_time}).");
                }
            }
        }

        DB::transaction(function () use ($scheduleRequest) {
            $doctor = $scheduleRequest->doctor;
            $clinicId = $scheduleRequest->clinic_id;
            $schedules = $scheduleRequest->schedules ?? [];

            DoctorSchedule::where('doctor_id', $doctor->id)
                ->where('clinic_id', $clinicId)
                ->delete();

            foreach ($schedules as $item) {
                $data = [
                    'clinic_id' => $clinicId,
                    'department_id' => $doctor->primary_department_id,
                    'start_time' => $item['start_time'],
                    'end_time' => $item['end_time'],
                    'slot_duration_minutes' => $item['slot_duration_minutes'],
                ];

                if (($item['type'] ?? null) === 'weekly') {
                    $data['day_of_week'] = $item['day_of_week'];
                    $data['schedule_date'] = null;
                } else {
                    $data['schedule_date'] = $item['schedule_date'];
                    $data['day_of_week'] = null;
                }

                $doctor->schedules()->create($data);
            }

            $scheduleRequest->update([
                'status' => 'approved',
                'processed_by' => auth()->id(),
                'processed_at' => now(),
            ]);
        });

        return back()->with('success', 'Schedule request approved and applied.');
    }

    public function reject(Request $request, DoctorScheduleRequest $scheduleRequest)
    {
        Gate::authorize('manage_doctor_schedule');

        $scheduleRequest->update([
            'status' => 'rejected',
            'processed_by' => auth()->id(),
            'processed_at' => now(),
        ]);

        return back()->with('success', 'Schedule request rejected.');
    }
}
