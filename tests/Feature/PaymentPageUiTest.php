<?php

namespace Tests\Feature;

use App\Models\Clinic;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentPageUiTest extends TestCase
{
    use RefreshDatabase;

    protected Clinic $clinic;
    protected User $user;
    protected Patient $patient;

    protected function setUp(): void
    {
        parent::setUp();
        Model::unguard();

        $this->clinic = Clinic::create([
            'name' => 'Test Clinic',
            'code' => 'TC001',
            'address_line_1' => '123 Test St',
            'city' => 'Test City',
            'country' => 'Test Country',
            'timezone' => 'UTC',
            'currency' => 'USD'
        ]);

        $this->user = User::factory()->create(['clinic_id' => $this->clinic->id]);

        // Setup permissions
        $role = Role::create(['name' => 'Accountant']);
        $p1 = Permission::firstOrCreate(['name' => 'process_payments']);
        $p2 = Permission::firstOrCreate(['name' => 'create_invoices']); // Required by controller Gate::authorize('create', Invoice::class)
        $p3 = Permission::firstOrCreate(['name' => 'view_billing']);

        $role->givePermissionTo([$p1, $p2, $p3]);
        $this->user->assignRole($role);

        $this->patient = Patient::create([
            'clinic_id' => $this->clinic->id,
            'name' => 'John Doe',
            'patient_code' => 'P1000',
            'gender' => 'male',
            'date_of_birth' => '1990-01-01',
            'phone' => '1234567890',
            'address' => '123 Street'
        ]);
    }

    public function test_payment_page_displays_correct_ui_elements()
    {
        $invoice = Invoice::create([
            'clinic_id' => $this->clinic->id,
            'patient_id' => $this->patient->id,
            'invoice_number' => 'INV-001',
            'status' => 'unpaid',
            'subtotal' => 1000,
            'total_amount' => 1000,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('billing.payment.add', $invoice));

        $response->assertStatus(200);

        // Check Header
        $response->assertSee('Record Payment');
        $response->assertSee('Back to Invoice');

        // Check Invoice Summary
        $response->assertSee('Invoice Summary');
        $response->assertSee('INV-001');
        $response->assertSee('John Doe');
        $response->assertSee('Total Amount');
        $response->assertSee('Remaining Due');

        // Check Payment Form
        $response->assertSee('New Payment');
        $response->assertSee('Process Payment');

        // Check History
        $response->assertSee('Payment History');
        $response->assertSee('No payments recorded yet');
    }

    public function test_payment_page_hides_form_when_fully_paid()
    {
        $invoice = Invoice::create([
            'clinic_id' => $this->clinic->id,
            'patient_id' => $this->patient->id,
            'invoice_number' => 'INV-002',
            'status' => 'paid',
            'subtotal' => 1000,
            'total_amount' => 1000,
        ]);

        // Add full payment
        $invoice->payments()->create([
            'amount' => 1000,
            'payment_method' => 'cash',
            'paid_at' => now(),
            'received_by' => $this->user->id
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('billing.payment.add', $invoice));

        $response->assertStatus(200);

        // Should show Fully Paid alert
        $response->assertSee('Fully Paid!');
        $response->assertDontSee('Process Payment'); // Button should be gone
    }
}
