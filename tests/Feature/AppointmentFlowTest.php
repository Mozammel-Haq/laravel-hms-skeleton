<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentFlowTest extends TestCase
{
    use RefreshDatabase;

    protected $clinic;

    protected $doctor;

    protected $patient;

    protected $receptionist;

    protected function setUp(): void
    {
        parent::setUp();

        $this->clinic = Clinic::create([
            'name' => 'Test Clinic',
            'code' => 'TC',
            'city' => 'Test City',
            'country' => 'Test Country',
            'address_line_1' => '123 Main St',
            'timezone' => 'UTC',
            'currency' => 'USD',
            'status' => 'active',
        ]);

        $department = Department::factory()->create([
            'clinic_id' => $this->clinic->id,
        ]);

        $doctorUser = User::factory()->create([
            'clinic_id' => $this->clinic->id,
            'name' => 'Dr. Test',
        ]);

        $this->doctor = Doctor::factory()->create([
            'user_id' => $doctorUser->id,
            'primary_department_id' => $department->id,
            'clinic_id' => $this->clinic->id,
            'status' => 'active',
        ]);

        $this->patient = Patient::factory()->create([
            'clinic_id' => $this->clinic->id,
        ]);

        $this->receptionist = User::factory()->create([
            'clinic_id' => $this->clinic->id,
        ]);

        // Setup permissions/roles if necessary (assuming policies check this)
        // For now acting as a super admin or just a user with permissions if RBAC is strict
        // But let's assume standard auth for now.
        $role = \App\Models\Role::firstOrCreate(['name' => 'Receptionist']);
        $perm = \App\Models\Permission::firstOrCreate(['name' => 'create_appointments']);
        $viewPerm = \App\Models\Permission::firstOrCreate(['name' => 'view_appointments']);
        $role->permissions()->syncWithoutDetaching([$perm->id, $viewPerm->id]);
        $this->receptionist->roles()->syncWithoutDetaching($role);
        $this->receptionist->refresh();
        $this->receptionist->load('roles.permissions');
    }

    public function test_doctor_double_booking_prevention()
    {
        $this->actingAs($this->receptionist);

        $date = now()->addDay()->format('Y-m-d');
        $time = '10:00';
        $timeDb = $time.':00';

        // 1. Create first appointment
        $response1 = $this->post(route('appointments.store'), [
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id,
            'appointment_date' => $date,
            'start_time' => $time,
            'type' => 'consultation',
            'reason' => 'First booking',
        ]);

        $response1->assertRedirect();

        $this->assertTrue(
            Appointment::where('doctor_id', $this->doctor->id)
                ->whereDate('appointment_date', $date)
                ->where('start_time', $timeDb)
                ->exists()
        );

        // 2. Try to create second appointment for SAME doctor, SAME time, DIFFERENT patient
        $patient2 = Patient::factory()->create(['clinic_id' => $this->clinic->id]);

        $response2 = $this->post(route('appointments.store'), [
            'patient_id' => $patient2->id, // Different patient
            'doctor_id' => $this->doctor->id, // Same doctor
            'appointment_date' => $date,
            'start_time' => $time, // Same time
            'type' => 'consultation',
            'reason' => 'Second booking (should fail)',
        ]);

        $response2->assertSessionHasErrors('start_time');

        $this->assertEquals(1, Appointment::where('doctor_id', $this->doctor->id)
            ->where('start_time', $timeDb)
            ->count(), 'Doctor should NOT be double booked!');
    }
}
