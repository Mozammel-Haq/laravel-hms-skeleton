<?php

namespace Tests\Feature;

use App\Models\Patient;
use App\Models\Clinic;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientAgeTest extends TestCase
{
    use RefreshDatabase;

    public function test_age_is_calculated_from_dob_if_age_column_is_null()
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
        $dob = Carbon::now()->subYears(25);

        // Manually create patient as factory might not exist
        $patient = new Patient();
        $patient->clinic_id = $clinic->id;
        $patient->name = 'Test Patient';
        $patient->patient_code = 'TEMP-' . uniqid();
        $patient->gender = 'Male';
        $patient->date_of_birth = $dob->format('Y-m-d');
        $patient->age = null;
        $patient->save();

        // Refresh model to ensure accessor is called
        $patient->refresh();

        $this->assertEquals(25, $patient->age);
    }

    public function test_age_column_takes_precedence_if_not_null()
    {
        $clinic = Clinic::create([
            'name' => 'Test Clinic 2',
            'code' => 'TC2',
            'address_line_1' => 'Test Address 2',
            'city' => 'Test City 2',
            'country' => 'Test Country 2',
            'timezone' => 'UTC',
            'currency' => 'USD',
        ]);
        $dob = Carbon::now()->subYears(25);

        $patient = new Patient();
        $patient->clinic_id = $clinic->id;
        $patient->name = 'Test Patient 2';
        $patient->patient_code = 'TEMP2-' . uniqid();
        $patient->gender = 'Male';
        $patient->date_of_birth = $dob->format('Y-m-d');
        $patient->age = 30; // Explicitly set wrong age
        $patient->save();

        $patient->refresh();

        $this->assertEquals(30, $patient->age);
    }
}
