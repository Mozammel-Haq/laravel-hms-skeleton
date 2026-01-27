<?php

namespace App\Http\Controllers\Extras;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\DoctorScheduleRequest;
use App\Models\DoctorSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DoctorSelfScheduleController extends Controller
{
    public function index()
    {
        $doctor = optional(auth()->user())->doctor;

        if (!$doctor) {
            abort(403, 'User is not associated with a doctor profile.');
        }

        $clinicId = auth()->user()->clinic_id;

        $schedules = $doctor->schedules()
            ->where('clinic_id', $clinicId)
            ->whereNull('schedule_date')
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day_of_week');

        $appointmentsQuery = Appointment::where('doctor_id', $doctor->id)
            ->where('clinic_id', $clinicId)
            ->with(['patient']);

        if (request()->filled('search')) {
            $search = request('search');
            $appointmentsQuery->whereHas('patient', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('patient_code', 'like', "%{$search}%");
            });
        }

        if (request()->filled('status')) {
            if (request('status') !== 'all') {
                $appointmentsQuery->where('status', request('status'));
            }
        }

        if (request()->filled('from')) {
            $appointmentsQuery->whereDate('appointment_date', '>=', request('from'));
        }

        if (request()->filled('to')) {
            $appointmentsQuery->whereDate('appointment_date', '<=', request('to'));
        }

        $appointments = $appointmentsQuery->orderBy('appointment_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('doctor.schedule.index', compact('doctor', 'appointments', 'schedules'));
    }

    public function manage()
    {
        $doctor = optional(auth()->user())->doctor;

        if (!$doctor) {
            abort(403, 'User is not associated with a doctor profile.');
        }

        $clinicId = auth()->user()->clinic_id;

        $schedules = $doctor->schedules()
            ->where('clinic_id', $clinicId)
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        return view('doctors.schedule', [
            'doctor' => $doctor,
            'schedules' => $schedules,
            'mode' => 'self',
        ]);
    }

    public function requestUpdate(Request $request)
    {
        $doctor = optional(auth()->user())->doctor;

        if (!$doctor) {
            abort(403, 'User is not associated with a doctor profile.');
        }

        $clinic = auth()->user()->clinic;

        $validator = Validator::make($request->all(), [
            'schedules' => 'nullable|array',
            'schedules.*.type' => 'required|in:weekly,date',
            'schedules.*.day_of_week' => 'nullable|required_if:schedules.*.type,weekly|integer|between:0,6',
            'schedules.*.schedule_date' => 'nullable|required_if:schedules.*.type,date|date|after_or_equal:today',
            'schedules.*.start_time' => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) use ($clinic) {
                    if ($clinic && $clinic->opening_time) {
                        $start = Carbon::parse($value);
                        $clinicOpen = Carbon::parse($clinic->opening_time);
                        if ($start->lt($clinicOpen)) {
                            $fail('Schedule start time must be on or after clinic opening time (' . $clinic->opening_time . ').');
                        }
                    }
                },
            ],
            'schedules.*.end_time' => [
                'required',
                'date_format:H:i',
                'after:schedules.*.start_time',
                function ($attribute, $value, $fail) use ($clinic) {
                    if ($clinic && $clinic->closing_time) {
                        $end = Carbon::parse($value);
                        $clinicClose = Carbon::parse($clinic->closing_time);
                        if ($end->gt($clinicClose)) {
                            $fail('Schedule end time must be on or before clinic closing time (' . $clinic->closing_time . ').');
                        }
                    }
                },
            ],
            'schedules.*.slot_duration_minutes' => 'required|integer|min:5',
        ]);

        $validator->after(function ($validator) use ($request, $doctor) {
            $schedules = $request->input('schedules', []);
            $currentClinicId = auth()->user()->clinic_id;

            foreach ($schedules as $index => $schedule) {
                if (!isset($schedule['start_time']) || !isset($schedule['end_time']) || !isset($schedule['type'])) {
                    continue;
                }

                $start = $schedule['start_time'];
                $end = $schedule['end_time'];
                $type = $schedule['type'];

                // Query potential conflicts in other clinics
                $conflicts = DoctorSchedule::withoutTenant()
                    ->where('doctor_id', $doctor->id)
                    ->where('clinic_id', '!=', $currentClinicId)
                    ->where(function ($q) use ($start, $end) {
                        $q->where('start_time', '<', $end)
                            ->where('end_time', '>', $start);
                    })
                    ->with('clinic')
                    ->get();

                foreach ($conflicts as $conflict) {
                    $isConflict = false;

                    if ($type === 'weekly') {
                        if (!isset($schedule['day_of_week'])) continue;
                        $myDay = $schedule['day_of_week'];

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
                        if (!isset($schedule['schedule_date'])) continue;
                        $myDate = $schedule['schedule_date'];

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
                        $validator->errors()->add(
                            "schedules.{$index}.start_time",
                            "Schedule overlaps with {$clinicName} ({$conflictDesc} {$conflict->start_time}-{$conflict->end_time})."
                        );
                        break;
                    }
                }
            }
        });

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $schedules = $request->input('schedules', []);

        DoctorScheduleRequest::create([
            'doctor_id' => $doctor->id,
            'clinic_id' => auth()->user()->clinic_id,
            'schedules' => $schedules,
            'status' => 'pending',
            'requested_by' => auth()->id(),
        ]);

        return redirect()->route('doctor.schedule.index')->with('success', 'Schedule change request submitted for approval.');
    }
}
