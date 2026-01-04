<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardRoleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    }

    public function test_super_admin_sees_correct_dashboard_and_sidebar()
    {
        $clinic = \App\Models\Clinic::create([
            'name' => 'Test Clinic',
            'code' => 'TC001',
            'address_line_1' => '123 Main St',
            'city' => 'New York',
            'country' => 'USA',
            'timezone' => 'UTC',
            'currency' => 'USD',
        ]);
        $user = User::factory()->create(['clinic_id' => $clinic->id]);
        $user->assignRole('Super Admin');

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Super Admin Dashboard');
        $response->assertSee('Global Reports'); // Sidebar item
        $response->assertDontSee('My Appointments'); // Doctor item (assuming wording)
    }

    public function test_doctor_sees_correct_dashboard_and_sidebar()
    {
        $clinic = \App\Models\Clinic::create([
            'name' => 'Test Clinic 2',
            'code' => 'TC002',
            'address_line_1' => '123 Main St',
            'city' => 'New York',
            'country' => 'USA',
            'timezone' => 'UTC',
            'currency' => 'USD',
        ]);
        $user = User::factory()->create(['clinic_id' => $clinic->id]);
        $user->assignRole('Doctor');

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Doctor Dashboard');
        $response->assertSee('Clinical Workflow'); // Sidebar section
        $response->assertDontSee('System Settings'); // Admin item
    }

    public function test_receptionist_sees_correct_dashboard()
    {
        $clinic = \App\Models\Clinic::create([
            'name' => 'Test Clinic 3',
            'code' => 'TC003',
            'address_line_1' => '123 Main St',
            'city' => 'New York',
            'country' => 'USA',
            'timezone' => 'UTC',
            'currency' => 'USD',
        ]);
        $user = User::factory()->create(['clinic_id' => $clinic->id]);
        $user->assignRole('Receptionist');

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Receptionist Dashboard');
        $response->assertSee('Front Desk'); // Sidebar section
    }
}
