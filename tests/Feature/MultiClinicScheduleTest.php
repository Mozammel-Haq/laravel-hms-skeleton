<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\DoctorScheduleException;
use App\Models\User;
use App\Services\AppointmentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class MultiClinicScheduleTest extends TestCase
{
    use RefreshDatabase;

    protected $clinicA;
    protected $clinicB;
    protected $doctor;
    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
        Model::unguard();

        // Create Clinics
        $this->clinicA = Clinic::create(['name' => 'Clinic A', 'code' => 'CA', 'address_line_1' => 'Addr A', 'city' => 'City', 'country' => 'C', 'currency' => 'USD', 'timezone' => 'UTC']);
        $this->clinicB = Clinic::create(['name' => 'Clinic B', 'code' => 'CB', 'address_line_1' => 'Addr B', 'city' => 'City', 'country' => 'C', 'currency' => 'USD', 'timezone' => 'UTC']);

        // Create Department
        $dept = Department::create(['clinic_id' => $this->clinicA->id, 'name' => 'General', 'status' => 'active']);

        // Create Doctor
        $user = User::factory()->create(['clinic_id' => $this->clinicA->id]);
        $this->doctor = Doctor::create([
            'user_id' => $user->id,
            'primary_department_id' => $dept->id,
            'specialization' => 'General',
            'status' => 'active'
        ]);

        // Attach to both clinics
        $this->doctor->clinics()->attach([$this->clinicA->id, $this->clinicB->id]);

        $this->service = new AppointmentService();
    }

    /** @test */
    public function schedules_are_isolated_by_clinic()
    {
        // Set Schedule for Clinic A: Monday 09:00 - 10:00 (1 hour, 4 slots of 15m)
        DoctorSchedule::create([
            'doctor_id' => $this->doctor->id,
            'clinic_id' => $this->clinicA->id,
            'department_id' => $this->doctor->primary_department_id,
            'day_of_week' => 1, // Monday
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
            'slot_duration_minutes' => 15,
            'status' => 'active'
        ]);

        // Find next Monday
        $date = Carbon::parse('next Monday')->format('Y-m-d');

        // Check slots for Clinic A
        $slotsA = $this->service->getAvailableSlots($this->doctor, $date, $this->clinicA->id);
        $this->assertCount(4, $slotsA);

        // Check slots for Clinic B (Should be empty as no schedule)
        $slotsB = $this->service->getAvailableSlots($this->doctor, $date, $this->clinicB->id);
        $this->assertEmpty($slotsB);

        // Now Set Schedule for Clinic B: Monday 10:00 - 11:00 (1 hour, 4 slots)
        DoctorSchedule::create([
            'doctor_id' => $this->doctor->id,
            'clinic_id' => $this->clinicB->id,
            'department_id' => $this->doctor->primary_department_id,
            'day_of_week' => 1, // Monday
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'slot_duration_minutes' => 15,
            'status' => 'active'
        ]);

        // Re-check
        $slotsA = $this->service->getAvailableSlots($this->doctor, $date, $this->clinicA->id);
        $this->assertCount(4, $slotsA);
        $this->assertEquals('09:00', $slotsA[0]['start_time']);

        $slotsB = $this->service->getAvailableSlots($this->doctor, $date, $this->clinicB->id);
        $this->assertCount(4, $slotsB);
        $this->assertEquals('10:00', $slotsB[0]['start_time']);
    }

    /** @test */
    public function exceptions_are_isolated_by_clinic()
    {
        // Schedule for Clinic A: Monday 09:00 - 17:00
        DoctorSchedule::create([
            'doctor_id' => $this->doctor->id,
            'clinic_id' => $this->clinicA->id,
            'department_id' => $this->doctor->primary_department_id,
            'day_of_week' => 1, // Monday
            'start_time' => '09:00:00',
            'end_time' => '17:00:00',
            'slot_duration_minutes' => 60,
            'status' => 'active'
        ]);

        // Schedule for Clinic B: Monday 09:00 - 17:00
        DoctorSchedule::create([
            'doctor_id' => $this->doctor->id,
            'clinic_id' => $this->clinicB->id,
            'department_id' => $this->doctor->primary_department_id,
            'day_of_week' => 1, // Monday
            'start_time' => '09:00:00',
            'end_time' => '17:00:00',
            'slot_duration_minutes' => 60,
            'status' => 'active'
        ]);

        $date = Carbon::parse('next Monday')->format('Y-m-d');

        // Verify initial state
        $this->assertNotEmpty($this->service->getAvailableSlots($this->doctor, $date, $this->clinicA->id));
        $this->assertNotEmpty($this->service->getAvailableSlots($this->doctor, $date, $this->clinicB->id));

        // Add Exception: Day Off for Clinic A ONLY
        DoctorScheduleException::create([
            'doctor_id' => $this->doctor->id,
            'clinic_id' => $this->clinicA->id,
            'exception_date' => $date,
            'is_available' => false,
            'reason' => 'Vacation A',
            'status' => 'approved'
        ]);

        // Check Clinic A: Should be empty
        $slotsA = $this->service->getAvailableSlots($this->doctor, $date, $this->clinicA->id);
        $this->assertEmpty($slotsA);

        // Check Clinic B: Should STILL have slots
        $slotsB = $this->service->getAvailableSlots($this->doctor, $date, $this->clinicB->id);
        $this->assertNotEmpty($slotsB);
    }
}
