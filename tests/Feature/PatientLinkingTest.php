<?php

namespace Tests\Feature;

use App\Models\Patient;
use App\Models\User;
use App\Models\Clinic;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PatientLinkingTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_patient_linking_route_exists()
    {
        // Try to find any patient
        $patient = Patient::first();
        
        if (!$patient) {
            $this->markTestSkipped('No patient found in database to test.');
        }

        $response = $this->get("/global-patient-link/{$patient->id}");
        
        // If it's 404, it means route not found. 
        // If it's 302, it means redirect (likely to login), which means route EXISTS.
        $this->assertNotEquals(404, $response->getStatusCode(), "Route /global-patient-link/{$patient->id} returned 404!");
    }
}
