<?php

namespace App\Http\Controllers\Extras;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DoctorsExtrasController extends Controller
{
    public function assignment()
    {
        // Permission requirement: Only Super Admin can access assignment view
        if (!auth()->user() || !auth()->user()->hasRole('Super Admin')) {
            abort(403);
        }
        $clinics = Clinic::orderBy('name')->get();
        $clinicId = auth()->user()->clinic_id;
        $doctors = Doctor::with(['user', 'clinics', 'department'])->orderByDesc('created_at')->get();
        // $doctors = Clinic::with(['doctors', "doctors.user", "doctors.schedules"])->find($clinicId);
        return view('doctors.assignment', compact('clinics', 'doctors'));
    }

    public function schedules()
    {
        $clinicId = auth()->user()->clinic_id;
        // $doctors = Doctor::with(['user', 'schedules'])->orderBy('user_id')->where('clinic_id','=',$clinicId)->get();

        $doctors = Clinic::with(['doctors', "doctors.user", "doctors.schedules"])->find($clinicId);
        // return $doctors;
        return view('doctors.schedules', compact('doctors'));
    }

    public function getCalendarEvents(Request $request)
    {
        $request->validate([
            'year' => 'required|integer',
            'month' => 'required|integer|min:1|max:12',
        ]);

        $year = $request->year;
        $month = $request->month;

        try {
            $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfDay();
            $endOfMonth = $startOfMonth->copy()->endOfMonth()->endOfDay();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid date'], 400);
        }

        // Fetch all doctors with their schedules and exceptions
        $doctors = Doctor::with(['user', 'schedules', 'exceptions'])->get();

        $calendarData = [];

        // Loop through each day of the month
        $currentDay = $startOfMonth->copy();
        while ($currentDay->lte($endOfMonth)) {
            $dateString = $currentDay->toDateString();
            $dayOfWeek = $currentDay->dayOfWeek; // 0 (Sunday) to 6 (Saturday)

            $availableDoctors = [];

            foreach ($doctors as $doctor) {
                // Check for exceptions first
                $isException = false;
                if ($doctor->exceptions) {
                    foreach ($doctor->exceptions as $exception) {
                        // Check if current day falls within exception range
                        // Assuming start_date and end_date fields based on previous tasks
                        $exStart = Carbon::parse($exception->start_date)->startOfDay();
                        $exEnd = Carbon::parse($exception->end_date ?? $exception->start_date)->endOfDay();

                        if ($currentDay->between($exStart, $exEnd)) {
                            $isException = true;
                            break;
                        }
                    }
                }
                if ($isException) continue;

                // Check specific date schedules
                $specificSchedule = $doctor->schedules->where('schedule_date', $dateString)->first();
                if ($specificSchedule) {
                    $availableDoctors[] = [
                        'id' => $doctor->id,
                        'name' => $doctor->user->name ?? 'Unknown Doctor',
                        'specialization' => $doctor->specialization,
                        'start_time' => $specificSchedule->start_time,
                        'end_time' => $specificSchedule->end_time,
                        'type' => 'specific'
                    ];
                    continue;
                }

                // Check weekly schedules
                // Note: Carbon returns 0 for Sunday, 6 for Saturday.
                // Assuming DB uses the same standard. If DB uses 1-7, we might need adjustment.
                // Standard Laravel/PHP usually aligns.
                $weeklySchedule = $doctor->schedules
                    ->where('day_of_week', (string)$dayOfWeek)
                    ->whereNull('schedule_date')
                    ->first();

                // Also check integer type match just in case
                if (!$weeklySchedule) {
                    $weeklySchedule = $doctor->schedules
                        ->where('day_of_week', $dayOfWeek)
                        ->whereNull('schedule_date')
                        ->first();
                }

                if ($weeklySchedule) {
                    $availableDoctors[] = [
                        'id' => $doctor->id,
                        'name' => $doctor->user->name ?? 'Unknown Doctor',
                        'specialization' => $doctor->specialization,
                        'start_time' => $weeklySchedule->start_time,
                        'end_time' => $weeklySchedule->end_time,
                        'type' => 'weekly'
                    ];
                }
            }

            if (!empty($availableDoctors)) {
                $calendarData[$dateString] = $availableDoctors;
            }

            $currentDay->addDay();
        }

        return response()->json($calendarData);
    }
}
