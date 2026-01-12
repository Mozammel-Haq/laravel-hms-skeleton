<?php

namespace Tests\Feature;

use App\Models\Clinic;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\DoctorScheduleException;
use App\Models\User;
use App\Services\AppointmentService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Model;
use Tests\TestCase;

class DoctorSchedulingEnhancementTest extends TestCase
{
    use RefreshDatabase;

    protected $clinic;
    protected $doctor;
    protected $service;
    protected $department;

    protected function setUp(): void
    {
        parent::setUp();
        Model::unguard();

        // Create Clinic
        $this->clinic = Clinic::create([
            'name' => 'Test Clinic',
            'code' => 'TC',
            'address_line_1' => 'Test Addr',
            'city' => 'Test City',
            'country' => 'Test Country',
            'currency' => 'USD',
            'timezone' => 'UTC'
        ]);

        // Create Department
        $this->department = Department::create([
            'clinic_id' => $this->clinic->id,
            'name' => 'General',
            'status' => 'active'
        ]);

        // Create Doctor
        $user = User::factory()->create(['clinic_id' => $this->clinic->id]);
        $this->doctor = Doctor::create([
            'user_id' => $user->id,
            'primary_department_id' => $this->department->id,
            'specialization' => 'General',
            'status' => 'active'
        ]);

        $this->doctor->clinics()->attach($this->clinic->id);
        $this->service = new AppointmentService();
    }

    /** @test */
    public function date_specific_schedule_overrides_weekly_schedule()
    {
        // 1. Create Weekly Schedule for Monday: 09:00 - 10:00
        DoctorSchedule::create([
            'doctor_id' => $this->doctor->id,
            'clinic_id' => $this->clinic->id,
            'department_id' => $this->department->id,
            'day_of_week' => 1, // Monday
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
            'slot_duration_minutes' => 60, // 1 slot
            'status' => 'active'
        ]);

        $nextMonday = Carbon::parse('next Monday');
        $dateStr = $nextMonday->format('Y-m-d');

        // Verify Weekly Schedule works
        $slots = $this->service->getAvailableSlots($this->doctor, $dateStr, $this->clinic->id);
        $this->assertCount(1, $slots);
        $this->assertEquals('09:00', $slots[0]['start_time']);

        // 2. Create Specific Schedule for THIS Monday: 13:00 - 14:00
        DoctorSchedule::create([
            'doctor_id' => $this->doctor->id,
            'clinic_id' => $this->clinic->id,
            'department_id' => $this->department->id,
            'schedule_date' => $dateStr,
            'start_time' => '13:00:00',
            'end_time' => '14:00:00',
            'slot_duration_minutes' => 60,
            'status' => 'active'
        ]);

        // Verify Specific Schedule overrides Weekly
        $slots = $this->service->getAvailableSlots($this->doctor, $dateStr, $this->clinic->id);
        $this->assertCount(1, $slots);
        $this->assertEquals('13:00', $slots[0]['start_time']);

        // Verify following Monday still uses Weekly
        $followingMonday = $nextMonday->copy()->addWeek()->format('Y-m-d');
        $slots = $this->service->getAvailableSlots($this->doctor, $followingMonday, $this->clinic->id);
        $this->assertCount(1, $slots);
        $this->assertEquals('09:00', $slots[0]['start_time']);
    }

    /** @test */
    public function range_based_exception_blocks_availability()
    {
        // Weekly Schedule: Monday 09:00 - 17:00
        DoctorSchedule::create([
            'doctor_id' => $this->doctor->id,
            'clinic_id' => $this->clinic->id,
            'department_id' => $this->department->id,
            'day_of_week' => 1, // Monday
            'start_time' => '09:00:00',
            'end_time' => '17:00:00',
            'slot_duration_minutes' => 60,
            'status' => 'active'
        ]);

        $nextMonday = Carbon::parse('next Monday');
        $dateStr = $nextMonday->format('Y-m-d');

        // Verify available initially
        $slots = $this->service->getAvailableSlots($this->doctor, $dateStr, $this->clinic->id);
        $this->assertNotEmpty($slots);

        // Create Exception blocking Sunday to Tuesday
        DoctorScheduleException::create([
            'doctor_id' => $this->doctor->id,
            'clinic_id' => $this->clinic->id,
            'start_date' => $nextMonday->copy()->subDay()->format('Y-m-d'), // Sunday
            'end_date' => $nextMonday->copy()->addDay()->format('Y-m-d'),   // Tuesday
            'is_available' => false,
            'reason' => 'Conference',
            'status' => 'approved'
        ]);

        // Verify Monday is blocked
        $slots = $this->service->getAvailableSlots($this->doctor, $dateStr, $this->clinic->id);
        $this->assertEmpty($slots);
    }

    /** @test */
    public function range_based_exception_can_modify_times()
    {
        // Weekly Schedule: Monday 09:00 - 17:00
        DoctorSchedule::create([
            'doctor_id' => $this->doctor->id,
            'clinic_id' => $this->clinic->id,
            'department_id' => $this->department->id,
            'day_of_week' => 1, // Monday
            'start_time' => '09:00:00',
            'end_time' => '17:00:00',
            'slot_duration_minutes' => 60,
            'status' => 'active'
        ]);

        $nextMonday = Carbon::parse('next Monday');
        $dateStr = $nextMonday->format('Y-m-d');

        // Create Exception for that week (Mon-Fri) changing hours to 10:00 - 12:00
        DoctorScheduleException::create([
            'doctor_id' => $this->doctor->id,
            'clinic_id' => $this->clinic->id,
            'start_date' => $nextMonday->format('Y-m-d'),
            'end_date' => $nextMonday->copy()->addDays(4)->format('Y-m-d'),
            'is_available' => true,
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'reason' => 'Short Hours',
            'status' => 'approved'
        ]);

        // Verify Monday has new hours
        $slots = $this->service->getAvailableSlots($this->doctor, $dateStr, $this->clinic->id);
        $this->assertCount(2, $slots); // 10-11, 11-12
        $this->assertEquals('10:00', $slots[0]['start_time']);
        $this->assertEquals('11:00', $slots[1]['start_time']);
    }
}
