<?php

namespace Tests\Feature;

use App\Models\Clinic;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class GlobalPatientTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Run the specific migration for this feature if needed,
        // but RefreshDatabase should handle it if migration is in standard path.
    }

    public function test_patient_creation_and_linking_across_clinics()
    {
        // 1. Setup Clinics
        $clinic1 = Clinic::create([
            'name' => 'Clinic One',
            'code' => 'C1',
            'address_line_1' => 'Address 1',
            'city' => 'City 1',
            'country' => 'Country 1',
            'timezone' => 'UTC',
            'currency' => 'USD',
        ]);

        $clinic2 = Clinic::create([
            'name' => 'Clinic Two',
            'code' => 'C2',
            'address_line_1' => 'Address 2',
            'city' => 'City 2',
            'country' => 'Country 2',
            'timezone' => 'UTC',
            'currency' => 'USD',
        ]);

        // 2. Setup Users
        // Create Role and Permissions
        $role = \App\Models\Role::create(['name' => 'Test Doctor', 'guard_name' => 'web']);
        $p1 = \App\Models\Permission::firstOrCreate(['name' => 'create_patients', 'guard_name' => 'web']);
        $p2 = \App\Models\Permission::firstOrCreate(['name' => 'view_patients', 'guard_name' => 'web']);

        // Attach permissions to role (assuming relationship exists)
        $role->permissions()->attach([$p1->id, $p2->id]);

        $user1 = User::factory()->create(['clinic_id' => $clinic1->id]);
        $user1->roles()->attach($role->id);

        $user2 = User::factory()->create(['clinic_id' => $clinic2->id]);
        $user2->roles()->attach($role->id);

        // 3. Create Patient in Clinic 1
        $this->actingAs($user1);

        $patientData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
            'nid_number' => '1234567890',
            'address' => '123 Main St',
        ];

        $response = $this->post(route('patients.store'), $patientData);
        $response->assertRedirect();

        // Assert Patient Exists
        $patient = Patient::where('email', 'john@example.com')->first();
        $this->assertNotNull($patient);

        // Assert Linked to Clinic 1
        $this->assertTrue($patient->clinics->contains($clinic1->id));
        $this->assertFalse($patient->clinics->contains($clinic2->id));

        // 4. Try to "Create" same patient in Clinic 2
        $this->actingAs($user2);

        // We use the same identifying data (e.g. NID or Email)
        $response2 = $this->post(route('patients.store'), $patientData);
        $response2->assertRedirect(); // Should redirect to show page or similar

        // Assert Patient Count is still 1 (No duplicate)
        $this->assertEquals(1, Patient::where('email', 'john@example.com')->count());

        // Refresh patient relation
        $patient->refresh();

        // Assert Linked to Clinic 2 now
        $this->assertTrue($patient->clinics->contains($clinic2->id));

        // 5. Verify Scope/Isolation

        // Clinic 1 can see patient
        $this->actingAs($user1);
        $this->assertNotNull(Patient::find($patient->id));

        // Clinic 2 can see patient
        $this->actingAs($user2);
        $this->assertNotNull(Patient::find($patient->id));

        // Clinic 3 (Unrelated)
        $clinic3 = Clinic::create([
            'name' => 'Clinic Three',
            'code' => 'C3',
            'address_line_1' => 'Address 3',
            'city' => 'City 3',
            'country' => 'Country 3',
            'timezone' => 'UTC',
            'currency' => 'USD',
        ]);
        $user3 = User::factory()->create(['clinic_id' => $clinic3->id]);

        $this->actingAs($user3);
        $this->assertNull(Patient::find($patient->id)); // Should be filtered out by global scope

        // 6. Verify Ambiguity Fix (Clinic -> Patients relationship query)
        // This mimics the query that caused the "ambiguous column" error
        $this->actingAs($user1);
        try {
            $count = $clinic1->patients()->count();
            $this->assertEquals(1, $count);

            // Also test simple retrieval
            $patients = $clinic1->patients()->get();
            $this->assertTrue($patients->contains($patient->id));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->fail("Ambiguous column error detected: " . $e->getMessage());
        }
    }
}
