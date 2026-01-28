<?php

namespace Tests\Feature\Api;

use App\Models\Clinic;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_patient_can_access_appointments_in_secondary_clinic()
    {
        // 1. Setup Clinics
        $clinicA = Clinic::create([
            'name' => 'Clinic A',
            'code' => 'CLA',
            'address_line_1' => '123 Main St',
            'city' => 'City A',
            'state' => 'State A',
            'postal_code' => '12345',
            'country' => 'Country A',
            'phone' => '1111111111',
             'email' => 'clinicA@test.com',
             'timezone' => 'UTC',
             'currency' => 'USD'
         ]);
         $clinicB = Clinic::create([
             'name' => 'Clinic B',
             'code' => 'CLB',
             'address_line_1' => '456 Side St',
             'city' => 'City B',
             'state' => 'State B',
             'postal_code' => '67890',
             'country' => 'Country B',
             'phone' => '2222222222',
             'email' => 'clinicB@test.com',
             'timezone' => 'UTC',
             'currency' => 'USD'
         ]);

        // 2. Create Patient linked to Clinic A (legacy/primary way)
        $patient = Patient::create([
            'clinic_id' => $clinicA->id,
            'name' => 'Test Patient',
            'email' => 'patient@test.com',
            'password' => bcrypt('password'),
            'patient_code' => 'P-001',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
            'blood_group' => 'O+',
            'phone' => '1234567890',
        ]);

        // 3. Link Patient to Clinic B (Multi-clinic scenario)
        $patient->clinics()->attach($clinicB->id);

        // 4. Create an appointment in Clinic B for this patient
        // We need a doctor in Clinic B
        $deptB = Department::forceCreate([
            'name' => 'General Dept B',
            'clinic_id' => $clinicB->id
        ]);

        $doctorUser = User::create([
            'name' => 'Dr. B',
            'email' => 'drb@test.com',
            'password' => bcrypt('password'),
            'clinic_id' => $clinicB->id,
        ]);
        $doctor = Doctor::create([
            'user_id' => $doctorUser->id,
            'specialization' => '["General"]', // JSON field
            'license_number' => '123',
            'phone' => '123',
            'status' => 'active',
            'primary_department_id' => $deptB->id
        ]);

        $appointment = Appointment::forceCreate([
            'clinic_id' => $clinicB->id,
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'department_id' => $deptB->id,
            'appointment_date' => now()->addDay()->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '10:15:00',
            'status' => 'scheduled',
            'visit_type' => 'new',
            'booking_source' => 'online',
            'appointment_type' => 'online',
            'fee' => 100,
        ]);

        // 5. Authenticate as Patient
        // We need to create a token or use actingAs if Sanctum supports it for Patient model
        // Patient uses HasApiTokens
        $token = $patient->createToken('test-token')->plainTextToken;

        // 6. Request Appointments for Clinic B
        $response = $this->withHeaders([
            'X-Clinic-ID' => $clinicB->id,
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/patient/appointments');

        // 7. Assertions
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'appointments');
        $response->assertJsonFragment(['id' => $appointment->id]);
    }

    public function test_patient_can_access_appointments_in_primary_clinic()
    {
        // 1. Setup Clinics
        $clinicA = Clinic::create([
            'name' => 'Clinic A',
            'code' => 'CLA',
            'address_line_1' => '123 Main St',
            'city' => 'City A',
            'state' => 'State A',
            'postal_code' => '12345',
            'country' => 'Country A',
            'phone' => '1111111111',
             'email' => 'clinicA@test.com',
             'timezone' => 'UTC',
             'currency' => 'USD'
         ]);

        // 2. Create Patient linked to Clinic A
        $patient = Patient::create([
            'clinic_id' => $clinicA->id, // Primary
            'name' => 'Test Patient',
            'email' => 'patient@test.com',
            'password' => bcrypt('password'),
            'patient_code' => 'P-001',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
            'blood_group' => 'O+',
            'phone' => '1234567890',
        ]);

        // 3. Create appointment in Clinic A
        $deptA = Department::forceCreate([
            'name' => 'General Dept A',
            'clinic_id' => $clinicA->id
        ]);

        $doctorUser = User::create([
            'name' => 'Dr. A',
            'email' => 'dra@test.com',
            'password' => bcrypt('password'),
            'clinic_id' => $clinicA->id,
        ]);
        $doctor = Doctor::create([
            'user_id' => $doctorUser->id,
            'specialization' => '["General"]', // JSON field
            'license_number' => '123',
            'phone' => '123',
            'status' => 'active',
            'primary_department_id' => $deptA->id
        ]);

        $appointment = Appointment::forceCreate([
            'clinic_id' => $clinicA->id,
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'department_id' => $deptA->id,
            'appointment_date' => now()->addDay()->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '10:15:00',
            'status' => 'scheduled',
            'visit_type' => 'new',
            'booking_source' => 'online',
            'appointment_type' => 'online',
            'fee' => 100,
        ]);

        $token = $patient->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'X-Clinic-ID' => $clinicA->id,
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/patient/appointments');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'appointments');
    }
}
