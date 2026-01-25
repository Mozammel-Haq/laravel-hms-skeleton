<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Clinic;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Role;

class UniqueConstraintTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_cannot_create_doctor_with_duplicate_license_number()
    {
        $clinic = Clinic::first();
        $user = User::factory()->create(['clinic_id' => $clinic->id]);
        $user->assignRole('Super Admin');
        $this->actingAs($user);

        $department = Department::forceCreate(['name' => 'Cardiology', 'clinic_id' => $clinic->id, 'status' => 'active']);

        // Create first doctor
        $this->post(route('doctors.store'), [
            'name' => 'Dr. First',
            'email' => 'first@example.com',
            'password' => 'password',
            'primary_department_id' => $department->id,
            'specialization' => ['Cardiology'],
            'license_number' => 'LIC123',
            'registration_number' => 'REG123',
            'status' => 'active',
        ])->assertRedirect();

        // Try to create second doctor with same license
        $response = $this->post(route('doctors.store'), [
            'name' => 'Dr. Second',
            'email' => 'second@example.com',
            'password' => 'password',
            'primary_department_id' => $department->id,
            'specialization' => ['Cardiology'],
            'license_number' => 'LIC123', // Duplicate
            'registration_number' => 'REG456',
            'status' => 'active',
        ]);

        $response->assertSessionHasErrors(['license_number']);
    }

    public function test_cannot_create_doctor_with_duplicate_registration_number()
    {
        $clinic = Clinic::first();
        $user = User::factory()->create(['clinic_id' => $clinic->id]);
        $user->assignRole('Super Admin');
        $this->actingAs($user);

        $department = Department::forceCreate(['name' => 'Cardiology', 'clinic_id' => $clinic->id, 'status' => 'active']);

        // Create first doctor
        $this->post(route('doctors.store'), [
            'name' => 'Dr. First',
            'email' => 'first@example.com',
            'password' => 'password',
            'primary_department_id' => $department->id,
            'specialization' => ['Cardiology'],
            'license_number' => 'LIC123',
            'registration_number' => 'REG123',
            'status' => 'active',
        ])->assertRedirect();

        // Try to create second doctor with same registration number
        $response = $this->post(route('doctors.store'), [
            'name' => 'Dr. Second',
            'email' => 'second@example.com',
            'password' => 'password',
            'primary_department_id' => $department->id,
            'specialization' => ['Cardiology'],
            'license_number' => 'LIC456',
            'registration_number' => 'REG123', // Duplicate
            'status' => 'active',
        ]);

        $response->assertSessionHasErrors(['registration_number']);
    }

    public function test_cannot_create_patient_with_duplicate_nid()
    {
        $clinic = Clinic::first();
        $user = User::factory()->create(['clinic_id' => $clinic->id]);
        $user->assignRole('Super Admin');
        $this->actingAs($user);

        // Create first patient
        $this->post(route('patients.store'), [
            'name' => 'Patient One',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
            'phone' => '1111111111',
            'address' => 'Address 1',
            'nid_number' => '1234567890',
        ])->assertRedirect();

        // Try to create second patient with same NID
        $response = $this->post(route('patients.store'), [
            'name' => 'Patient Two',
            'date_of_birth' => '1992-01-01',
            'gender' => 'female',
            'phone' => '2222222222',
            'address' => 'Address 2',
            'nid_number' => '1234567890', // Duplicate
        ]);

        $response->assertSessionHasErrors(['nid_number']);
    }

    public function test_cannot_create_patient_with_duplicate_passport()
    {
        $clinic = Clinic::first();
        $user = User::factory()->create(['clinic_id' => $clinic->id]);
        $user->assignRole('Super Admin');
        $this->actingAs($user);

        // Create first patient
        $this->post(route('patients.store'), [
            'name' => 'Patient One',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
            'phone' => '1111111111',
            'address' => 'Address 1',
            'passport_number' => 'A1234567',
        ])->assertRedirect();

        // Try to create second patient with same Passport
        $response = $this->post(route('patients.store'), [
            'name' => 'Patient Two',
            'date_of_birth' => '1992-01-01',
            'gender' => 'female',
            'phone' => '2222222222',
            'address' => 'Address 2',
            'passport_number' => 'A1234567', // Duplicate
        ]);

        $response->assertSessionHasErrors(['passport_number']);
    }

    public function test_cannot_create_clinic_with_duplicate_registration_number()
    {
        $clinic = Clinic::first();
        $user = User::factory()->create(['clinic_id' => $clinic->id]);
        $user->assignRole('Super Admin');
        $this->actingAs($user);

        // Create first clinic
        $this->post(route('clinics.store'), [
            'name' => 'Clinic One',
            'code' => 'CLINIC001',
            'registration_number' => 'REG_CLINIC_123',
            'address_line_1' => 'Address 1',
            'city' => 'City',
            'country' => 'Country',
            'timezone' => 'UTC',
            'currency' => 'USD',
            'status' => 'active',
        ])->assertRedirect();

        // Try to create second clinic with same registration number
        $response = $this->post(route('clinics.store'), [
            'name' => 'Clinic Two',
            'code' => 'CLINIC002',
            'registration_number' => 'REG_CLINIC_123', // Duplicate
            'address_line_1' => 'Address 2',
            'city' => 'City',
            'country' => 'Country',
            'timezone' => 'UTC',
            'currency' => 'USD',
            'status' => 'active',
        ]);

        $response->assertSessionHasErrors(['registration_number']);
    }
}
