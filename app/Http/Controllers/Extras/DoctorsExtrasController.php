<?php

namespace App\Http\Controllers\Extras;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Carbon\Carbon;

/**
 * Class DoctorsExtrasController
 *
 * Handles additional doctor-related functionalities such as assignment and schedule viewing.
 *
 * @package App\Http\Controllers\Extras
 */
class DoctorsExtrasController extends Controller
{
    /**
     * Display the doctor assignment page.
     *
     * Supports filtering by:
     * - Clinic ID
     * - Search (Doctor name, specialization, license)
     * - Status (deleted)
     * - Date Range (created_at)
     *
     * @return \Illuminate\View\View
     */
    public function assignment()
    {
        $user = auth()->user();

        if (!$user) {
            abort(403);
        }

        $query = Doctor::with(['user', 'clinics', 'department']);

        if ($user->hasRole('Super Admin') || $user->can('manage_doctor_clinic_assignments')) {
            $clinics = Clinic::orderBy('name')->get();
            if (request()->filled('clinic_id')) {
                $query->whereHas('clinics', function ($q) {
                    $q->where('clinics.id', request('clinic_id'));
                });
            }
        } else {
            $clinicId = $user->clinic_id;
            $clinics = Clinic::whereKey($clinicId)->get();

            $query->whereHas('department', function ($q) use ($clinicId) {
                $q->where('clinic_id', $clinicId);
            });
        }

        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%");
                })
                    ->orWhere('specialization', 'like', "%{$search}%")
                    ->orWhere('license_number', 'like', "%{$search}%");
            });
        }

        if (request()->filled('status')) {
            if (request('status') === 'deleted') {
                $query->onlyTrashed();
            }
        }

        if (request()->filled('from')) {
            $query->whereDate('created_at', '>=', request('from'));
        }

        if (request()->filled('to')) {
            $query->whereDate('created_at', '<=', request('to'));
        }

        $doctors = $query->latest()->paginate(20)->withQueryString();

        return view('doctors.assignment', compact('clinics', 'doctors'));
    }

    /**
     * Display the doctor schedules page.
     *
     * @return \Illuminate\View\View
     */
    public function schedules()
    {
        $clinicId = auth()->user()->clinic_id;
        $doctors = Doctor::with(['user', 'schedules'])
            ->whereHas('clinics', function ($query) use ($clinicId) {
                $query->where('clinics.id', $clinicId);
            });

        if (request()->filled('search')) {
            $search = request('search');
            $doctors->where(function ($q) use ($search) {
                $q->whereHas('user', function ($sub) use ($search) {
                    $sub->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                })->orWhere('specialization', 'like', '%' . $search . '%');
            });
        }

        if (request()->filled('from')) {
            $doctors->whereDate('created_at', '>=', request('from'));
        }

        if (request()->filled('to')) {
            $doctors->whereDate('created_at', '<=', request('to'));
        }

        $doctors = $doctors
            ->latest()
            ->paginate(20);
        return view('doctors.schedules', compact('doctors'));
    }

    /**
     * Get doctor schedule events for the calendar view.
     *
     * Returns JSON data for doctor availability based on weekly schedules and exceptions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
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
