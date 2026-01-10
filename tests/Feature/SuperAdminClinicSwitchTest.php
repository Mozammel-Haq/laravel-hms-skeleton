<?php

namespace Tests\Feature;

use App\Models\Clinic;
use App\Models\User;
use App\Models\Role;
use App\Support\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuperAdminClinicSwitchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Reset TenantContext
        TenantContext::setClinicId(null);

        // Ensure roles exist
        if (!Role::where('name', 'Super Admin')->exists()) {
            Role::create(['name' => 'Super Admin']);
        }
    }

    private function createClinic(array $attributes = [])
    {
        return Clinic::create(array_merge([
            'name' => 'Test Clinic',
            'code' => 'TC' . rand(100, 999),
            'address_line_1' => '123 Test St',
            'city' => 'Test City',
            'country' => 'Test Country',
            'timezone' => 'UTC',
            'currency' => 'USD',
        ], $attributes));
    }

    private function createPatient($clinicId)
    {
        \App\Models\Patient::unguard();
        $patient = \App\Models\Patient::create([
            'clinic_id' => $clinicId,
            'patient_code' => 'P' . rand(1000, 9999),
            'name' => 'Test Patient',
        ]);
        \App\Models\Patient::reguard();
        return $patient;
    }

    public function test_super_admin_can_switch_clinic_context()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Super Admin');

        $clinic = $this->createClinic(['name' => 'Test Clinic A', 'code' => 'TCA']);

        $response = $this->actingAs($admin)
            ->get(route('system.switch-clinic', $clinic->id));

        $response->assertRedirect();
        $this->assertEquals($clinic->id, session('selected_clinic_id'));
    }

    public function test_super_admin_can_clear_clinic_context()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Super Admin');

        // Simulate existing selection
        session(['selected_clinic_id' => 999]);

        $response = $this->actingAs($admin)
            ->get(route('system.clear-clinic'));

        $response->assertRedirect();
        $this->assertNull(session('selected_clinic_id'));
    }

    public function test_middleware_sets_tenant_context()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Super Admin');
        $clinic = $this->createClinic(['name' => 'Clinic M', 'code' => 'CLM']);

        // Simulate session
        $this->withSession(['selected_clinic_id' => $clinic->id]);

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertStatus(200);
    }

    public function test_reports_are_scoped_when_clinic_selected()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Super Admin');

        $clinicA = $this->createClinic(['name' => 'Clinic A', 'code' => 'CLA']);
        $clinicB = $this->createClinic(['name' => 'Clinic B', 'code' => 'CLB']);

        $patientA = $this->createPatient($clinicA->id);
        $patientB = $this->createPatient($clinicB->id);

        // Create invoices for both
        // Note: Invoice likely requires more fields, checking migrations would be safe but trying minimal first
        // Assuming timestamps are handled or nullable
        \App\Models\Invoice::unguard();
        \App\Models\Invoice::create([
            'clinic_id' => $clinicA->id,
            'patient_id' => $patientA->id,
            'invoice_number' => 'INV-A-001',
            'subtotal' => 100,
            'total_amount' => 100,
            'status' => 'paid'
        ]);
        \App\Models\Invoice::create([
            'clinic_id' => $clinicB->id,
            'patient_id' => $patientB->id,
            'invoice_number' => 'INV-B-001',
            'subtotal' => 200,
            'total_amount' => 200,
            'status' => 'paid'
        ]);
        \App\Models\Invoice::reguard();

        // Switch to Clinic A
        $this->withSession(['selected_clinic_id' => $clinicA->id]);

        // Access Financial Report
        $response = $this->actingAs($admin)->get(route('reports.financial'));

        $response->assertStatus(200);
        $response->assertViewHas('revenue', 100); // Should only see Clinic A's revenue

        // Switch to Global
        $this->flushSession(); // Clear session

        $response = $this->actingAs($admin)->get(route('reports.financial'));
        $response->assertStatus(200);
        $response->assertViewHas('revenue', 300); // Should see Total revenue
    }
}
