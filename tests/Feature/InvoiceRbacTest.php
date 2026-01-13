<?php

namespace Tests\Feature;

use App\Models\Clinic;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceRbacTest extends TestCase
{
    use RefreshDatabase;

    protected Clinic $clinic;

    protected function setUp(): void
    {
        parent::setUp();
        $this->clinic = Clinic::create([
            'name' => 'Finance Clinic',
            'code' => 'FC001',
            'address_line_1' => '123 Finance St',
            'city' => 'City',
            'country' => 'Country',
            'timezone' => 'UTC',
            'currency' => 'USD'
        ]);
    }

    public function test_receptionist_with_create_invoices_permission_cannot_access_create_due_to_policy()
    {
        $user = User::factory()->create(['clinic_id' => $this->clinic->id]);
        $role = Role::firstOrCreate(['name' => 'Receptionist'], ['description' => 'Front Desk']);
        $perm = Permission::firstOrCreate(['name' => 'create_invoices']);
        $role->permissions()->syncWithoutDetaching($perm);
        $user->assignRole($role);

        $response = $this->actingAs($user)->get(route('billing.create'));
        $response->assertStatus(403);
    }

    public function test_accountant_can_access_create_invoice()
    {
        $user = User::factory()->create(['clinic_id' => $this->clinic->id]);
        $role = Role::firstOrCreate(['name' => 'Accountant'], ['description' => 'Finance Staff']);
        $permViewBilling = Permission::firstOrCreate(['name' => 'view_billing']);
        $permCreateInvoices = Permission::firstOrCreate(['name' => 'create_invoices']);
        $role->permissions()->syncWithoutDetaching([$permViewBilling->id, $permCreateInvoices->id]);
        $user->assignRole($role);

        $response = $this->actingAs($user)->get(route('billing.create'));
        $response->assertStatus(200);
        $response->assertSee('Create Invoice');
    }
}
