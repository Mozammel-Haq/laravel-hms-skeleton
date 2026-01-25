<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\LabTest;
use App\Models\LabTestOrder;
use App\Models\Patient;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Clinic;
use App\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use Illuminate\Database\Eloquent\Model;

class LabOrderLogicTest extends TestCase
{
    use RefreshDatabase;

    protected $clinic;
    protected $department;

    protected function setUp(): void
    {
        parent::setUp();
        Model::unguard();

        // Create Permissions
        Permission::create(['name' => 'view_lab']);

        // Create Role
        if (!Role::where('name', 'Doctor')->exists()) {
            $role = Role::create(['name' => 'Doctor']);
            $role->givePermissionTo('view_lab');
        }

        // Create Clinic and Department
        $this->clinic = Clinic::create([
            'name' => 'Test Clinic',
            'code' => 'TC001',
            'address_line_1' => '123 Test St',
            'city' => 'Test City',
            'country' => 'Test Country',
            'timezone' => 'UTC',
            'currency' => 'USD',
            'phone' => '1234567890',
            'email' => 'clinic@test.com',
            'status' => 'active'
        ]);

        $this->department = Department::create([
            'name' => 'General',
            'clinic_id' => $this->clinic->id,
            'description' => 'General Department',
            'status' => 'active'
        ]);
    }

    public function test_can_search_eligible_patients()
    {
        $user = User::factory()->create(['clinic_id' => $this->clinic->id]);
        $user->assignRole('Doctor');

        $this->actingAs($user);

        $doctor = Doctor::create([
            'user_id' => $user->id,
            'primary_department_id' => $this->department->id,
            'specialization' => 'General',
            'status' => 'active',
        ]);

        $patientEligible = Patient::create([
            'name' => 'Eligible Patient',
            'clinic_id' => $this->clinic->id,
            'status' => 'active',
            'patient_code' => 'P1001',
            'date_of_birth' => '2000-01-01',
            'gender' => 'male',
            'phone' => '1234567890',
            'address' => 'Test Address',
        ]);

        Appointment::create([
            'patient_id' => $patientEligible->id,
            'clinic_id' => $this->clinic->id,
            'doctor_id' => $doctor->id,
            'department_id' => $this->department->id,
            'status' => 'completed',
            'appointment_date' => now(),
            'start_time' => '10:00:00',
            'end_time' => '10:30:00',
            'appointment_type' => 'Consultation',
            'booking_source' => 'online',
            'created_by' => $user->id,
        ]);

        $patientIneligible = Patient::create([
            'name' => 'Ineligible Patient',
            'clinic_id' => $this->clinic->id,
            'status' => 'active',
            'patient_code' => 'P1002',
            'date_of_birth' => '2000-01-01',
            'gender' => 'female',
            'phone' => '0987654321',
            'address' => 'Test Address',
        ]);

        $response = $this->getJson(route('patients.search', [
            'term' => 'Patient',
            'type' => 'lab_eligible'
        ]));

        $response->assertOk();
        $data = $response->json();

        // Should find Eligible
        $this->assertTrue(collect($data['results'])->contains('id', $patientEligible->id));

        // Should NOT find Ineligible
        $this->assertFalse(collect($data['results'])->contains('id', $patientIneligible->id));
    }

    public function test_doctor_can_create_lab_order_for_eligible_patient()
    {
        $this->withoutExceptionHandling();

        // Setup Doctor
        $user = User::create([
            'name' => 'Dr. Test',
            'email' => 'drtest@test.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'clinic_id' => $this->clinic->id,
            'status' => 'active',
        ]);
        $user->assignRole('Doctor');

        $this->actingAs($user);

        $doctor = Doctor::create([
            'user_id' => $user->id,
            'primary_department_id' => $this->department->id,
            'specialization' => 'General',
            'status' => 'active',
        ]);

        // Setup Eligible Patient
        $patient = Patient::create([
            'name' => 'Eligible Patient',
            'clinic_id' => $this->clinic->id,
            'status' => 'active',
            'patient_code' => 'P1003',
            'date_of_birth' => '2000-01-01',
            'gender' => 'male',
            'phone' => '1234567890',
            'address' => 'Test Address',
        ]);

        $appt = Appointment::create([
            'patient_id' => $patient->id,
            'clinic_id' => $this->clinic->id,
            'doctor_id' => $doctor->id,
            'department_id' => $this->department->id,
            'status' => 'completed',
            'appointment_date' => now(),
            'start_time' => '10:00:00',
            'end_time' => '10:30:00',
            'appointment_type' => 'Consultation',
            'booking_source' => 'online',
            'created_by' => $user->id,
        ]);

        $test = LabTest::create([
            'name' => 'Blood Test',
            'category' => 'Hematology',
            'price' => 100,
            'status' => 'active',
        ]);

        $response = $this->post(route('lab.store'), [
            'patient_id' => $patient->id,
            'lab_test_id' => $test->id,
            // doctor_id is auto-assigned
            // appointment_id is auto-detected
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('lab_test_orders', [
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'appointment_id' => $appt->id,
            'status' => 'pending'
        ]);
    }

    public function test_cannot_create_lab_order_for_ineligible_patient()
    {
        $user = User::create([
            'name' => 'Dr. Test 2',
            'email' => 'drtest2@test.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'clinic_id' => $this->clinic->id,
            'status' => 'active',
        ]);
        $user->assignRole('Doctor');

        $this->actingAs($user);

        $doctor = Doctor::create([
            'user_id' => $user->id,
            'primary_department_id' => $this->department->id,
            'specialization' => 'General',
            'status' => 'active',
        ]);

        $patient = Patient::create([
            'name' => 'Ineligible Patient',
            'clinic_id' => $this->clinic->id,
            'status' => 'active',
            'patient_code' => 'P1004',
            'date_of_birth' => '2000-01-01',
            'gender' => 'male',
            'phone' => '1112223333',
            'address' => 'Ineligible Address',
        ]);
        // No appointments

        $test = LabTest::create([
            'name' => 'Blood Test',
            'category' => 'Hematology',
            'price' => 100,
            'status' => 'active',
        ]);

        $response = $this->from(route('lab.create'))->post(route('lab.store'), [
            'patient_id' => $patient->id,
            'lab_test_id' => $test->id,
        ]);

        // dump(session()->all());
        if (!session()->has('error')) {
            dump('Session missing error. Session content:', session()->all());
            // Check if it was success?
            if (session()->has('success')) {
                dump('Got SUCCESS instead of ERROR:', session('success'));
            }
        }
        $this->assertDatabaseMissing('lab_test_orders', [
            'patient_id' => $patient->id,
        ]);
        $response->assertSessionHas('error');
    }
}
