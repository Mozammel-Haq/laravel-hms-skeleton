<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Doctor;
use Carbon\Carbon;

class AppointmentService
{
    /**
     * Get available appointment slots for a doctor on a specific date.
     *
     * @param  string  $date  (Y-m-d)
     */
    public function getAvailableSlots(Doctor $doctor, string $date, ?int $clinic_id = null): array
    {
        // Resolve Timezone
        $timezone = config('app.timezone');

        if ($clinic_id) {
            $clinic = \App\Models\Clinic::find($clinic_id);
            if ($clinic && $clinic->timezone) {
                $timezone = $clinic->timezone;
            }
        } elseif ($doctor->primaryDepartment && $doctor->primaryDepartment->clinic && $doctor->primaryDepartment->clinic->timezone) {
            $timezone = $doctor->primaryDepartment->clinic->timezone;
        }

        $dateObj = Carbon::parse($date, $timezone);
        $dayOfWeek = $dateObj->dayOfWeek; // 0 (Sunday) to 6 (Saturday)

        $startTime = null;
        $endTime = null;
        $slotDuration = 15; // Default

        $dateStr = $dateObj->format('Y-m-d');

        // 1. Check for Exceptions (Day Off or Time Change)
        // Range-based check: start_date <= date <= end_date
        $exceptionQuery = $doctor->exceptions()
            ->whereDate('start_date', '<=', $dateStr)
            ->whereDate('end_date', '>=', $dateStr)
            ->where('status', 'approved');

        if ($clinic_id) {
            $exceptionQuery->where('clinic_id', $clinic_id);
        }

        $exception = $exceptionQuery->first();

        if ($exception) {
            if (! $exception->is_available) {
                return []; // Doctor is completely off
            }
            // Use exception times if available
            if ($exception->start_time && $exception->end_time) {
                $startTimeStr = $exception->start_time instanceof \Carbon\Carbon ? $exception->start_time->format('H:i:s') : $exception->start_time;
                $endTimeStr = $exception->end_time instanceof \Carbon\Carbon ? $exception->end_time->format('H:i:s') : $exception->end_time;
                $startTime = Carbon::parse($dateStr.' '.$startTimeStr, $timezone);
                $endTime = Carbon::parse($dateStr.' '.$endTimeStr, $timezone);
            }
        }

        // 2. Get Schedule for the day
        // Priority: Specific Date > Weekly Pattern

        // A. Check for specific date schedule
        $scheduleQuery = $doctor->schedules()
            ->whereDate('schedule_date', $dateStr)
            ->where('status', 'active');

        if ($clinic_id) {
            $scheduleQuery->where('clinic_id', $clinic_id);
        }

        $schedule = $scheduleQuery->first();

        // B. If no specific date schedule, check weekly pattern
        if (! $schedule) {
            $weeklyQuery = $doctor->schedules()
                ->where('day_of_week', $dayOfWeek)
                ->where('status', 'active')
                ->whereNull('schedule_date'); // Ensure we don't pick up malformed records

            if ($clinic_id) {
                $weeklyQuery->where('clinic_id', $clinic_id);
            }

            $schedule = $weeklyQuery->first();
        }

        if (! $schedule && ! $startTime) {
            return []; // No regular schedule and no exception override
        }

        if ($schedule) {
            $slotDuration = $schedule->slot_duration_minutes;
            // If start/end not set by exception, use schedule
            if (! $startTime) {
                $startTimeStr = $schedule->start_time instanceof \Carbon\Carbon ? $schedule->start_time->format('H:i:s') : $schedule->start_time;
                $endTimeStr = $schedule->end_time instanceof \Carbon\Carbon ? $schedule->end_time->format('H:i:s') : $schedule->end_time;
                $startTime = Carbon::parse($dateStr.' '.$startTimeStr, $timezone);
                $endTime = Carbon::parse($dateStr.' '.$endTimeStr, $timezone);
            }
        } else {
            // Exception exists but no regular schedule (e.g., working on a weekend)
            // Use default slot duration if not available
            $slotDuration = 15;
        }

        // Safety check
        if (! $startTime || ! $endTime) {
            return [];
        }

        // 3. Generate Slots
        $slots = [];
        $currentSlot = $startTime->copy();

        // 4. Get Booked Appointments
        $bookedAppointments = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', $dateStr)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get(['appointment_date', 'start_time', 'end_time']);

        while ($currentSlot->lt($endTime)) {
            $slotEnd = $currentSlot->copy()->addMinutes($slotDuration);

            if ($slotEnd->gt($endTime)) {
                break;
            }

            // Filter out past slots if the date is today
            // User requirement: "like if only between 9.00-9.30 the appointment can be made not after 9.30"
            // So we allow booking until the slot ends.
            // Comparison using clinic timezone
            if ($dateObj->isToday() && $slotEnd->lte(Carbon::now($timezone))) {
                $currentSlot->addMinutes($slotDuration);

                continue;
            }

            $currentStartStr = $currentSlot->format('H:i:s');
            $currentEndStr = $slotEnd->format('H:i:s');

            $isBooked = false;

            foreach ($bookedAppointments as $appointment) {
                $bookingStart = $appointment->start_time instanceof Carbon
                    ? $appointment->start_time->format('H:i:s')
                    : (string) $appointment->start_time;
                $bookingEnd = $appointment->end_time instanceof Carbon
                    ? $appointment->end_time->format('H:i:s')
                    : (string) $appointment->end_time;

                if ($bookingStart < $currentEndStr && $bookingEnd > $currentStartStr) {
                    $isBooked = true;
                    break;
                }
            }

            $slots[] = [
                'start_time' => $currentSlot->format('H:i'),
                'end_time' => $slotEnd->format('H:i'),
                'is_booked' => $isBooked,
            ];

            $currentSlot->addMinutes($slotDuration);
        }

        return $slots;
    }

    /**
     * Calculate consultation fee based on patient history.
     */
    public function calculateFee(Doctor $doctor, $patientId): array
    {
        $hasPriorVisit = Appointment::where('doctor_id', $doctor->id)
            ->where('patient_id', $patientId)
            ->where('status', 'completed')
            ->exists();

        if ($hasPriorVisit) {
            return [
                'fee' => $doctor->follow_up_fee ?? $doctor->consultation_fee,
                'type' => 'follow_up',
                'is_discounted' => true,
            ];
        }

        return [
            'fee' => $doctor->consultation_fee,
            'type' => 'new',
            'is_discounted' => false,
        ];
    }
}
