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
        $dayOfWeek = $date->isoWeekday(); // 1 (Monday) to 7 (Sunday)

        // 1. Check for Exceptions (Day Off)
        // We need to use the relationship defined in Doctor model: schedules() and exceptions might be on DoctorSchedule?
        // No, DoctorScheduleException is linked to Doctor directly usually.
        // Let's check Doctor model.
        // Doctor model: public function schedules() { return $this->hasMany(DoctorSchedule::class); }
        // Doctor model doesn't show exceptions() relationship in the previous Read output.
        // DoctorSchedule has exceptions(), but that seems wrong. Exceptions are usually per Doctor.
        // Let's check DoctorScheduleException migration: doctor_id, clinic_id.
        // So Doctor should have hasMany(DoctorScheduleException::class).
        
        $exception = $doctor->exceptions()
            ->where('exception_date', $date->format('Y-m-d'))
            ->first();

        if ($exception && !$exception->is_available) {
            return []; // Doctor is off
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
        $bookedAppointments = Appointment::where('doctor_id', $doctor->id)
            ->where('appointment_date', $date->format('Y-m-d'))
            ->whereIn('status', ['pending', 'confirmed'])
            ->get(['start_time', 'end_time']);

        while ($currentSlot->lt($endTime)) {
            $slotEnd = $currentSlot->copy()->addMinutes($slotDuration);
            
            if ($slotEnd->gt($endTime)) {
                break;
            }

            $startString = $currentSlot->format('H:i:00'); // Match DB time format
            $isBooked = false;

            foreach ($bookedAppointments as $appointment) {
                // Assuming exact match for fixed slots
                if ($appointment->start_time == $startString) {
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
