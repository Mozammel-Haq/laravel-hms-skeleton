<?php

namespace Tests\Feature\Api;

use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientAppointmentRequestApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_patient_can_create_reschedule_request_for_appointment_in_secondary_clinic(): void
    {
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
            'currency' => 'USD',
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
            'currency' => 'USD',
        ]);

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

        $patient->clinics()->attach($clinicB->id);

        $deptB = Department::forceCreate([
            'name' => 'General Dept B',
            'clinic_id' => $clinicB->id,
        ]);

        $doctorUser = User::create([
            'name' => 'Dr. B',
            'email' => 'drb@test.com',
            'password' => bcrypt('password'),
            'clinic_id' => $clinicB->id,
        ]);

        $doctor = Doctor::create([
            'user_id' => $doctorUser->id,
            'clinic_id' => $clinicB->id,
            'specialization' => '["General"]',
            'license_number' => '123',
            'phone' => '123',
            'status' => 'active',
            'primary_department_id' => $deptB->id,
        ]);

        $appointment = Appointment::forceCreate([
            'clinic_id' => $clinicB->id,
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'department_id' => $deptB->id,
            'appointment_date' => now()->addDays(2)->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '10:15:00',
            'status' => 'pending',
            'visit_type' => 'new',
            'booking_source' => 'online',
            'appointment_type' => 'online',
            'fee' => 100,
        ]);

        $token = $patient->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'X-Clinic-ID' => $clinicB->id,
            'Authorization' => 'Bearer '.$token,
        ])->postJson('/api/patient/appointment-requests', [
            'appointment_id' => $appointment->id,
            'type' => 'reschedule',
            'reason' => 'Need to change time',
            'desired_date' => now()->addDays(3)->toDateString(),
            'desired_time' => '11:30',
        ]);

        $response->assertStatus(201);
        $response->assertJsonFragment(['appointment_id' => $appointment->id]);
        $this->assertDatabaseHas('appointment_requests', [
            'appointment_id' => $appointment->id,
            'clinic_id' => $clinicB->id,
            'type' => 'reschedule',
            'status' => 'pending',
        ]);
    }
}
