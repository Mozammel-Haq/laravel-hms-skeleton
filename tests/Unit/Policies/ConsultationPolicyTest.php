<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use App\Models\Role;
use App\Policies\ConsultationPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConsultationPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_doctor_can_create_consultation()
    {
        $policy = new ConsultationPolicy();

        // Create a clinic
        $clinic = \App\Models\Clinic::create([
            'name' => 'Test Clinic',
            'code' => 'TC',
            'address_line_1' => 'Test Address',
            'city' => 'Test City',
            'country' => 'Test Country',
            'currency' => 'USD',
            'timezone' => 'UTC',
        ]);

        // User with no role (but valid clinic_id)
        $userWithClinic = User::factory()->create(['clinic_id' => $clinic->id]);
        $this->assertFalse($policy->create($userWithClinic));

        // User with Doctor role
        $doctorRole = Role::create(['name' => 'Doctor', 'guard_name' => 'web']);
        $doctorUser = User::factory()->create(['clinic_id' => $clinic->id]);
        $doctorUser->roles()->attach($doctorRole);

        // Refresh to load roles
        $doctorUser->load('roles');

        $this->assertTrue($policy->create($doctorUser));

        // User with Receptionist role
        $receptionistRole = Role::create(['name' => 'Receptionist', 'guard_name' => 'web']);
        $receptionistUser = User::factory()->create(['clinic_id' => $clinic->id]);
        $receptionistUser->roles()->attach($receptionistRole);

        $receptionistUser->load('roles');

        $this->assertFalse($policy->create($receptionistUser));
    }
}
