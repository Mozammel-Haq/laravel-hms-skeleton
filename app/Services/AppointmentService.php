<?php

namespace App\Services;

use App\Models\Doctor;
use App\Models\Appointment;
use Carbon\Carbon;

class AppointmentService
{
    /**
     * Get available appointment slots for a doctor on a specific date.
     *
     * @param Doctor $doctor
     * @param string $date (Y-m-d)
     * @return array
     */
    public function getAvailableSlots(Doctor $doctor, string $date): array
    {
        $date = Carbon::parse($date);
        $dayOfWeek = $date->dayOfWeek; // 0 (Sunday) to 6 (Saturday)

        // 1. Check for Exceptions (Day Off) - Assuming relationship exists
        // If relationship doesn't exist yet, we skip or add it.
        // For now, let's assume it might not exist and wrap in try-catch or check method
        if (method_exists($doctor, 'exceptions')) {
            $exception = $doctor->exceptions()
                ->where('exception_date', $date->format('Y-m-d'))
                ->first();

            if ($exception && !$exception->is_available) {
                return []; // Doctor is off
            }
        }

        // 2. Get Schedule for the day
        $schedule = $doctor->schedules()
            ->where('day_of_week', $dayOfWeek)
            ->where('status', 'active')
            ->first();

        if (!$schedule) {
            return []; // No schedule for this day
        }

        // 3. Generate Slots
        $startTime = Carbon::parse($date->format('Y-m-d') . ' ' . $schedule->start_time);
        $endTime = Carbon::parse($date->format('Y-m-d') . ' ' . $schedule->end_time);
        $slotDuration = $schedule->slot_duration_minutes;

        $slots = [];
        $currentSlot = $startTime->copy();

        // 4. Get Booked Appointments
        // We only care about active appointments (not cancelled)
        $bookedAppointments = Appointment::where('doctor_id', $doctor->id)
            ->where('appointment_date', $date->format('Y-m-d'))
            ->whereIn('status', ['pending', 'confirmed'])
            ->get(['start_time', 'end_time']);

        while ($currentSlot->lt($endTime)) {
            $slotEnd = $currentSlot->copy()->addMinutes($slotDuration);
            
            if ($slotEnd->gt($endTime)) {
                break;
            }

            $currentStartStr = $currentSlot->format('H:i:00');
            $currentEndStr = $slotEnd->format('H:i:00');
            
            $isBooked = false;

            foreach ($bookedAppointments as $appointment) {
                // Check for overlap: (StartA < EndB) and (EndA > StartB)
                // Here: (ApptStart < SlotEnd) and (ApptEnd > SlotStart)
                if ($appointment->start_time < $currentEndStr && $appointment->end_time > $currentStartStr) {
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
}
