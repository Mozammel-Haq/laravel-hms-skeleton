<?php

namespace Tests\Feature;

use App\Models\Clinic;
use App\Models\Patient;
use App\Models\User;
use App\Models\PatientVital;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VitalsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed roles if necessary or create them manually
        Role::create(['name' => 'Doctor']);
    }

    public function test_doctor_can_record_vitals_with_new_fields()
    {
        $clinic = Clinic::create([
            'name' => 'Test Clinic',
            'code' => 'TC',
            'address_line_1' => 'Test Address',
            'city' => 'Test City',
            'country' => 'Test Country',
            'timezone' => 'UTC',
            'currency' => 'USD',
        ]);

        $doctor = User::factory()->create(['clinic_id' => $clinic->id]);
        $doctor->roles()->attach(Role::where('name', 'Doctor')->first());

        $patient = new Patient();
        $patient->clinic_id = $clinic->id;
        $patient->name = 'Test Patient';
        $patient->patient_code = 'TEMP-' . uniqid();
        $patient->gender = 'Male';
        $patient->date_of_birth = '2000-01-01';
        $patient->save();

        $this->actingAs($doctor);

        $response = $this->get(route('vitals.record'));
        $response->assertStatus(200);
        $response->assertSee('Weight (kg)');
        $response->assertSee('Height (cm)');
        $response->assertSee('BMI');
        $response->assertSee('SpO2 (%)');

        $data = [
            'patient_id' => $patient->id,
            'temperature' => 36.6,
            'heart_rate' => 72,
            'blood_pressure' => '120/80',
            'respiratory_rate' => 16,
            'weight' => 70.5,
            'height' => 175,
            'bmi' => 23.0,
            'spo2' => 98,
        ];

        $response = $this->post(route('vitals.store'), $data);

        $response->assertRedirect(route('vitals.history'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('patient_vitals', [
            'patient_id' => $patient->id,
            'weight' => 70.5,
            'height' => 175,
            'bmi' => 23.0,
            'spo2' => 98,
        ]);
    }
}
