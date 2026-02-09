<?php

namespace Tests\Feature;

use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\User;
use App\Models\Department;
use App\Models\Clinic;
use App\Services\AppointmentService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SlotGenerationTimeTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_filters_out_past_slots_for_current_day_respecting_timezone()
    {
        // Setup
        $clinic = Clinic::create([
            'name' => 'Test Clinic',
            'code' => 'TC01',
            'address_line_1' => '123 Test St',
            'city' => 'Test City',
            'country' => 'Test Country',
            'timezone' => 'Asia/Dhaka', // UTC+6
            'currency' => 'USD',
            'status' => 'active',
        ]);

        $department = Department::factory()->create([
            'clinic_id' => $clinic->id
        ]);

        $user = User::factory()->create();
        $doctor = Doctor::factory()->create([
            'user_id' => $user->id,
            'primary_department_id' => $department->id,
        ]);

        // Create a schedule for Monday
        // 9:00 AM to 5:00 PM
        DoctorSchedule::create([
            'clinic_id' => $clinic->id,
            'department_id' => $department->id,
            'doctor_id' => $doctor->id,
            'day_of_week' => 1, // Monday
            'start_time' => '09:00:00',
            'end_time' => '17:00:00',
            'slot_duration_minutes' => 30,
            'status' => 'active',
        ]);

        // Mock time: Monday, 12:00 PM in Asia/Dhaka
        // 12:00 PM Dhaka is 6:00 AM UTC
        $mockDate = Carbon::parse('2023-10-02 12:00:00', 'Asia/Dhaka');
        Carbon::setTestNow($mockDate);

        $service = new AppointmentService();
        // We pass the date string "2023-10-02"
        $slots = $service->getAvailableSlots($doctor, '2023-10-02', $clinic->id);

        $slotsCollection = collect($slots);

        // 1. Assert 9:00-9:30 is GONE (End 9:30 < 12:00)
        // Note: 9:30 Dhaka is 3:30 UTC.
        // If we didn't handle timezone, 9:30 UTC > 6:00 UTC (Now), so it would be visible.
        $pastSlot = $slotsCollection->firstWhere('start_time', '09:00');
        $this->assertNull($pastSlot, 'Past slot (9:00) should be filtered out respecting timezone.');

        // 2. Assert 12:00-12:30 is PRESENT (End 12:30 > 12:00)
        $currentSlot = $slotsCollection->firstWhere('start_time', '12:00');
        $this->assertNotNull($currentSlot, 'Current/Future slot (12:00) should be available.');
    }
}
