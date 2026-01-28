<?php

namespace Tests\Feature\Api;

use App\Models\Clinic;
use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PatientClinicsApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_patient_can_see_clinics_from_legacy_and_pivot_sources()
    {
        // 1. Setup Clinics
        $clinicLegacy = Clinic::create([
            'name' => 'Legacy Clinic',
            'code' => 'LEG',
            'address_line_1' => '123 Legacy St',
            'city' => 'Legacy City',
            'state' => 'Legacy State',
            'postal_code' => '12345',
            'country' => 'Country',
            'phone' => '1111111111',
            'email' => 'legacy@test.com',
            'timezone' => 'UTC',
            'currency' => 'USD'
        ]);

        $clinicPivot1 = Clinic::create([
            'name' => 'Pivot Clinic 1',
            'code' => 'PIV1',
            'address_line_1' => '456 Pivot St',
            'city' => 'Pivot City',
            'state' => 'Pivot State',
            'postal_code' => '67890',
            'country' => 'Country',
            'phone' => '2222222222',
            'email' => 'pivot1@test.com',
            'timezone' => 'UTC',
            'currency' => 'USD'
        ]);

        $clinicPivot2 = Clinic::create([
            'name' => 'Pivot Clinic 2',
            'code' => 'PIV2',
            'address_line_1' => '789 Pivot St',
            'city' => 'Pivot City',
            'state' => 'Pivot State',
            'postal_code' => '67890',
            'country' => 'Country',
            'phone' => '3333333333',
            'email' => 'pivot2@test.com',
            'timezone' => 'UTC',
            'currency' => 'USD'
        ]);

        // 2. Create Legacy Patient (linked via clinic_id)
        $patient = Patient::create([
            'clinic_id' => $clinicLegacy->id,
            'name' => 'Test Patient',
            'email' => 'patient@test.com',
            'password' => bcrypt('password'),
            'patient_code' => 'P-001',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
            'blood_group' => 'O+',
            'phone' => '1234567890',
        ]);

        // 3. Attach Patient to Pivot Clinics
        $patient->clinics()->attach([$clinicPivot1->id, $clinicPivot2->id]);

        // 4. Authenticate
        $token = $patient->createToken('test-token')->plainTextToken;

        // 5. Request Clinics List
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/patient/clinics');

        // 6. Assertions
        $response->assertStatus(200);
        $response->assertJsonCount(3, 'clinics'); // Legacy + 2 Pivots

        // Verify IDs are present
        $clinicIds = collect($response->json('clinics'))->pluck('id')->sort()->values();
        $expectedIds = collect([$clinicLegacy->id, $clinicPivot1->id, $clinicPivot2->id])->sort()->values();

        $this->assertEquals($expectedIds, $clinicIds);
    }

    public function test_deduplicates_clinics_if_linked_both_ways()
    {
         // 1. Setup Clinic
         $clinic = Clinic::create([
            'name' => 'Dual Link Clinic',
            'code' => 'DLC',
            'address_line_1' => '123 Main St',
            'city' => 'City',
            'state' => 'State',
            'postal_code' => '12345',
            'country' => 'Country',
            'phone' => '1111111111',
            'email' => 'dual@test.com',
            'timezone' => 'UTC',
            'currency' => 'USD'
        ]);

        // 2. Create Patient linked via legacy ID
        $patient = Patient::create([
            'clinic_id' => $clinic->id,
            'name' => 'Dual Patient',
            'email' => 'dual@test.com',
            'password' => bcrypt('password'),
            'patient_code' => 'P-002',
            'phone' => '9999999999',
        ]);

        // 3. Also link via Pivot
        $patient->clinics()->attach($clinic->id);

        // 4. Auth
        $token = $patient->createToken('test-token')->plainTextToken;

        // 5. Request
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/patient/clinics');

        // 6. Assertions
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'clinics'); // Should only be 1 unique clinic
        $response->assertJsonFragment(['id' => $clinic->id]);
    }
}
