<?php

namespace App\Http\Controllers;

use App\Models\DoctorSchedule;
use App\Models\DoctorScheduleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

/**
 * DoctorScheduleRequestAdminController
 *
 * Manages doctor schedule change requests for administrators.
 * Allows approving or rejecting proposed schedule changes.
 */
class DoctorScheduleRequestAdminController extends Controller
{
    /**
     * Display a listing of schedule change requests.
     *
     * @return \Illuminate\View\View
     */
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

    /**
     * Approve a doctor's schedule change request.
     * Replaces the doctor's existing schedule with the requested one.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DoctorScheduleRequest  $scheduleRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve(Request $request, DoctorScheduleRequest $scheduleRequest)
    {
        Gate::authorize('manage_doctor_schedule');

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

    /**
     * Reject a doctor's schedule change request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DoctorScheduleRequest  $scheduleRequest
     * @return \Illuminate\Http\RedirectResponse
     */
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
