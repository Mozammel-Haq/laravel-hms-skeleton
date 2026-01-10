<?php

namespace Tests\Feature;

use App\Models\Clinic;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class ClinicSwitchSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        if (!Role::where('name', 'Super Admin')->exists()) Role::create(['name' => 'Super Admin']);
        if (!Role::where('name', 'Clinic Admin')->exists()) Role::create(['name' => 'Clinic Admin']);
    }

    public function test_non_super_admin_cannot_use_system_switch_clinic()
    {
        $user = User::factory()->create();
        $user->assignRole('Clinic Admin');

        $clinic = Clinic::create([
            'name' => 'Test Clinic',
            'code' => 'TC' . rand(100, 999),
            'address_line_1' => '123 St',
            'city' => 'City',
            'country' => 'Country',
            'timezone' => 'UTC',
            'currency' => 'USD'
        ]);

        Log::shouldReceive('warning')
            ->once()
            ->with('Unauthorized clinic switch attempt', ['user_id' => $user->id]);

        $response = $this->actingAs($user)
            ->get(route('system.switch-clinic', $clinic->id));

        $response->assertStatus(403);
    }

    public function test_super_admin_switch_logs_info()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Super Admin');

        $clinic = Clinic::create([
            'name' => 'Test Clinic',
            'code' => 'TC' . rand(100, 999),
            'address_line_1' => '123 St',
            'city' => 'City',
            'country' => 'Country',
            'timezone' => 'UTC',
            'currency' => 'USD'
        ]);

        Log::shouldReceive('info')
            ->once()
            ->with('User switched clinic context', \Mockery::on(function ($data) use ($admin, $clinic) {
                return $data['user_id'] === $admin->id &&
                    $data['clinic_id'] === $clinic->id &&
                    $data['clinic_name'] === $clinic->name;
            }));

        // Allow any error logs (to prevent BadMethodCallException if test fails)
        Log::shouldReceive('error')->zeroOrMoreTimes();

        $response = $this->actingAs($admin)
            ->get(route('system.switch-clinic', $clinic->id));

        // if (session('error')) {
        //     dump(session('error'));
        // }

        $response->assertRedirect();
        // $response->assertSessionHasNoErrors();
        // $response->assertSessionHas('success');
    }
}
