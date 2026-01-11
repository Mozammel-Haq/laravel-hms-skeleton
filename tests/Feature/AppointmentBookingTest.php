<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use App\Models\DoctorSchedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AppointmentBookingTest extends TestCase
{
    use RefreshDatabase;

    protected $clinic;
    protected $doctorUser;
    protected $doctor;
    protected $patient;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed permissions if needed, or mock
        // Assuming database is fresh, we need to setup minimal data

        $this->clinic = Clinic::create([
            'name' => 'Test Clinic',
            'code' => 'TC001',
            'address_line_1' => '123 St',
            'city' => 'City',
            'country' => 'Country',
            'timezone' => 'UTC',
            'currency' => 'USD'
        ]);

        $this->user = User::factory()->create(['clinic_id' => $this->clinic->id]);
        
        // Setup permissions
        $role = Role::create(['name' => 'Admin']);
        $permission = Permission::create(['name' => 'create_appointments', 'module' => 'appointments']);
        // We need to check exact permission name used in Policy/Gate
        // AppointmentPolicy usually checks 'create_appointments' or similar. 
        // Based on search results earlier, middleware uses 'can:view_appointments'. 
        // Let's assume standard resource permissions or bypass if acting as Super Admin
        
        // Actually, let's create a user and give them permissions
        // But to avoid complex permission setup in test, we can actingAs a user with super-admin role if possible
        // or just ensure Policy allows it.
        // Let's look at AppointmentPolicy if it exists? 
        // Assuming standard Laravel policy: create returns true or checks permission.
        
        // Let's assign a role that has permission.
        $this->user->assignRole($role);
        // We might need to give permission to role
        $role->givePermissionTo($permission);
        
        // Also need 'viewAny' probably
        Permission::create(['name' => 'view_appointments', 'module' => 'appointments']);
        $role->givePermissionTo('view_appointments');

        // Create Department
        $department = Department::create([
            'clinic_id' => $this->clinic->id,
            'name' => 'Cardiology',
            'code' => 'CARD',
            'status' => 'active'
        ]);

        // Create Doctor
        $this->doctorUser = User::factory()->create(['clinic_id' => $this->clinic->id]);
        $this->doctor = Doctor::create([
            'user_id' => $this->doctorUser->id,
            'primary_department_id' => $department->id,
            'specialization' => 'Heart',
            'consultation_fee' => 100.00,
            'follow_up_fee' => 50.00,
            'status' => 'active'
        ]);
        $this->doctor->clinics()->attach($this->clinic->id);

        // Create Schedule for Monday (assuming test runs or we force date)
        DoctorSchedule::create([
            'doctor_id' => $this->doctor->id,
            'clinic_id' => $this->clinic->id,
            'department_id' => $department->id,
            'day_of_week' => 1, // Monday
            'start_time' => '09:00:00',
            'end_time' => '17:00:00',
            'slot_duration_minutes' => 30,
            'status' => 'active'
        ]);

        // Create Patient
        $this->patient = Patient::create([
            'clinic_id' => $this->clinic->id,
            'patient_code' => 'P001',
            'name' => 'John Doe',
            'status' => 'active'
        ]);
    }

    public function test_it_prevents_double_booking()
    {
        // 1. Create first appointment
        $date = '2026-01-05'; // A Monday
        $startTime = '09:00';

        $this->actingAs($this->user);

        $response = $this->post(route('appointments.store'), [
            'doctor_id' => $this->doctor->id,
            'patient_id' => $this->patient->id,
            'appointment_date' => $date,
            'start_time' => $startTime,
            'appointment_type' => 'in_person',
            'booking_source' => 'reception',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        
        $this->assertDatabaseHas('appointments', [
            'doctor_id' => $this->doctor->id,
            'start_time' => '09:00:00',
            'end_time' => '09:30:00', // 30 min duration
        ]);

        // 2. Try to create overlapping appointment (Same time)
        $response2 = $this->post(route('appointments.store'), [
            'doctor_id' => $this->doctor->id,
            'patient_id' => $this->patient->id,
            'appointment_date' => $date,
            'start_time' => $startTime, // Same time
            'appointment_type' => 'in_person',
            'booking_source' => 'reception',
        ]);

        $response2->assertSessionHasErrors(['start_time']);
        
        // 3. Try overlapping appointment (Partial overlap: 09:15)
        $response3 = $this->post(route('appointments.store'), [
            'doctor_id' => $this->doctor->id,
            'patient_id' => $this->patient->id,
            'appointment_date' => $date,
            'start_time' => '09:15', // Overlaps with 09:00-09:30
            'appointment_type' => 'in_person',
            'booking_source' => 'reception',
        ]);

        $response3->assertSessionHasErrors(['start_time']);
    }

    public function test_it_calculates_fees_correctly()
    {
        $date = '2026-01-05'; // Monday
        $this->actingAs($this->user);

        // 1. First Visit (New)
        $this->post(route('appointments.store'), [
            'doctor_id' => $this->doctor->id,
            'patient_id' => $this->patient->id,
            'appointment_date' => $date,
            'start_time' => '10:00',
            'appointment_type' => 'in_person',
            'booking_source' => 'reception',
        ]);

        $appointment1 = Appointment::where('start_time', '10:00:00')->first();
        $this->assertEquals(100.00, $appointment1->fee);
        $this->assertEquals('new', $appointment1->visit_type);

        // 2. Complete the first appointment
        $appointment1->update(['status' => 'completed']);

        // 3. Second Visit (Follow-up)
        $this->post(route('appointments.store'), [
            'doctor_id' => $this->doctor->id,
            'patient_id' => $this->patient->id,
            'appointment_date' => $date,
            'start_time' => '11:00', // Different slot
            'appointment_type' => 'in_person',
            'booking_source' => 'reception',
        ]);

        $appointment2 = Appointment::where('start_time', '11:00:00')->first();
        $this->assertEquals(50.00, $appointment2->fee);
        $this->assertEquals('follow_up', $appointment2->visit_type);
    }
}
