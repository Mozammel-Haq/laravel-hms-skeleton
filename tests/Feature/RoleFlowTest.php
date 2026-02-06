<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\Clinic;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleFlowTest extends TestCase
{
    use RefreshDatabase;

    protected $superAdmin;
    protected $clinic;

    protected function setUp(): void
    {
        parent::setUp();

        $this->clinic = Clinic::create([
            'name' => 'Test Clinic',
            'code' => 'TC001',
            'address_line_1' => '123 Main St',
            'city' => 'New York',
            'country' => 'USA',
            'timezone' => 'UTC',
            'currency' => 'USD',
        ]);

        $this->superAdmin = User::factory()->create(['clinic_id' => $this->clinic->id]);
        
        // Seed basic permissions
        $role = Role::create(['name' => 'Super Admin']);
        // Ensure the user has the Super Admin role
        $this->superAdmin->roles()->attach($role);
        
        // Create permissions needed for testing
        Permission::create(['name' => 'view_roles']);
        Permission::create(['name' => 'create_roles']);
        Permission::create(['name' => 'edit_roles']);
        Permission::create(['name' => 'delete_roles']);
    }

    public function test_super_admin_can_view_role_list()
    {
        $response = $this->actingAs($this->superAdmin)->get(route('admin.roles.index'));

        $response->assertStatus(200);
        $response->assertSee('Roles');
        $response->assertSee('Super Admin');
    }

    public function test_super_admin_can_create_role()
    {
        $response = $this->actingAs($this->superAdmin)->post(route('admin.roles.store'), [
            'name' => 'Nurse',
            'description' => 'Nurse role',
            'permissions' => [
                Permission::where('name', 'view_roles')->first()->id
            ]
        ]);

        $response->assertRedirect(route('admin.roles.index'));
        $this->assertDatabaseHas('roles', ['name' => 'Nurse']);
        $this->assertTrue(Role::where('name', 'Nurse')->first()->permissions->contains('name', 'view_roles'));
    }

    public function test_super_admin_can_update_role()
    {
        $role = Role::create(['name' => 'Receptionist']);

        $response = $this->actingAs($this->superAdmin)->put(route('admin.roles.update', $role), [
            'name' => 'Front Desk',
            'description' => 'Updated role',
            'permissions' => []
        ]);

        $response->assertRedirect(route('admin.roles.index'));
        $this->assertDatabaseHas('roles', ['name' => 'Front Desk']);
        $this->assertDatabaseMissing('roles', ['name' => 'Receptionist']);
    }

    public function test_super_admin_can_delete_role()
    {
        $role = Role::create(['name' => 'Temp Role']);

        $response = $this->actingAs($this->superAdmin)->delete(route('admin.roles.destroy', $role));

        $response->assertRedirect(route('admin.roles.index'));
        $this->assertDatabaseMissing('roles', ['name' => 'Temp Role']);
    }

    public function test_cannot_delete_role_assigned_to_users()
    {
        $role = Role::create(['name' => 'Active Role']);
        $user = User::factory()->create();
        $user->roles()->attach($role);

        $response = $this->actingAs($this->superAdmin)->delete(route('admin.roles.destroy', $role));

        $response->assertRedirect(); // Should redirect back
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('roles', ['name' => 'Active Role']);
    }

    public function test_unauthorized_user_cannot_access_role_management()
    {
        $user = User::factory()->create();
        // User has no roles

        $response = $this->actingAs($user)->get(route('admin.roles.index'));
        $response->assertStatus(403);

        $response = $this->actingAs($user)->post(route('admin.roles.store'), ['name' => 'Hacker']);
        $response->assertStatus(403);
    }
}
