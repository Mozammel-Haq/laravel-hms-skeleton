<?php

namespace Tests\Feature;

use App\Models\Clinic;
use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Models\Patient;
use App\Models\Permission;
use App\Models\PharmacySale;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PharmacyOtcTest extends TestCase
{
    use RefreshDatabase;

    protected $clinic;

    protected $patient;

    protected $pharmacist;

    protected $medicine;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Setup Clinic
        $this->clinic = Clinic::create([
            'name' => 'Test Clinic',
            'code' => 'TC',
            'city' => 'Test City',
            'country' => 'Test Country',
            'address_line_1' => '123 Main St',
            'timezone' => 'UTC',
            'currency' => 'USD',
            'status' => 'active',
        ]);

        // Set Tenant Context
        \App\Support\TenantContext::setClinicId($this->clinic->id);

        // 2. Setup Roles & Permissions
        $pharmRole = Role::firstOrCreate(['name' => 'Pharmacist']);
        $pharmPermissions = ['view_pharmacy', 'create_pharmacy_sales', 'view_medicines', 'view_invoices', 'create_invoices', 'process_payments'];
        foreach ($pharmPermissions as $perm) {
            $p = Permission::firstOrCreate(['name' => $perm]);
            if (! $pharmRole->permissions()->where('name', $perm)->exists()) {
                $pharmRole->permissions()->attach($p);
            }
        }

        // 3. Setup Users
        $this->pharmacist = User::factory()->create([
            'clinic_id' => $this->clinic->id,
            'name' => 'Pharmacist',
        ]);
        $this->pharmacist->assignRole($pharmRole);

        // 4. Setup Patient
        $this->patient = Patient::forceCreate([
            'clinic_id' => $this->clinic->id,
            'name' => 'Jane Doe',
            'patient_code' => 'P002',
            'date_of_birth' => '1992-01-01',
            'gender' => 'female',
            'phone' => '0987654321',
            'address' => '456 Test Ave',
        ]);

        // 5. Setup Medicine & Batch
        $this->medicine = Medicine::forceCreate([
            'name' => 'Paracetamol',
            'generic_name' => 'Paracetamol',
            'manufacturer' => 'GSK',
            'dosage_form' => 'Tablet',
            'strength' => '500mg',
            'price' => 10.00,
            'status' => 'active',
        ]);

        MedicineBatch::forceCreate([
            'clinic_id' => $this->clinic->id,
            'medicine_id' => $this->medicine->id,
            'batch_number' => 'BATCH001',
            'quantity_in_stock' => 100,
            'expiry_date' => now()->addYear(),
            'purchase_price' => 5.00,
        ]);
    }

    public function test_otc_sale_without_prescription()
    {
        // 1. Process Sale (Pharmacist) - No Prescription
        $response = $this->actingAs($this->pharmacist)->post(route('pharmacy.store'), [
            'patient_id' => $this->patient->id,
            'prescription_id' => null, // Optional
            'items' => [
                [
                    'medicine_id' => $this->medicine->id,
                    'quantity' => 5,
                ],
            ],
            'discount' => 0,
            'tax' => 0,
        ]);

        $response->assertRedirect();

        // Check session for errors
        if (session('error')) {
            $this->fail('Session has error: '.session('error'));
        }

        // 2. Verify Sale Created
        $this->assertDatabaseHas('pharmacy_sales', [
            'patient_id' => $this->patient->id,
            'clinic_id' => $this->clinic->id,
            'prescription_id' => null,
            'total_amount' => 50.00, // 5 * 10.00
        ]);

        $sale = PharmacySale::where('patient_id', $this->patient->id)->first();
        $this->assertNull($sale->prescription_id);
    }

    public function test_sale_with_immediate_payment()
    {
        // 1. Process Sale with Payment
        $response = $this->actingAs($this->pharmacist)->post(route('pharmacy.store'), [
            'patient_id' => $this->patient->id,
            'prescription_id' => null,
            'items' => [
                [
                    'medicine_id' => $this->medicine->id,
                    'quantity' => 5, // Total: 50
                ],
            ],
            'paid_amount' => 50.00,
            'payment_method' => 'cash',
        ]);

        $response->assertRedirect();

        // 2. Verify Invoice Created and Paid
        $this->assertDatabaseHas('invoices', [
            'patient_id' => $this->patient->id,
            'total_amount' => 50.00,
            'status' => 'paid', // Should be fully paid
        ]);

        // 3. Verify Payment Record
        $this->assertDatabaseHas('payments', [
            'amount' => 50.00,
            'payment_method' => 'cash',
        ]);
    }

    public function test_sale_with_partial_payment()
    {
        // 1. Process Sale with Partial Payment
        $response = $this->actingAs($this->pharmacist)->post(route('pharmacy.store'), [
            'patient_id' => $this->patient->id,
            'prescription_id' => null,
            'items' => [
                [
                    'medicine_id' => $this->medicine->id,
                    'quantity' => 10, // Total: 100
                ],
            ],
            'paid_amount' => 50.00,
            'payment_method' => 'cash',
        ]);

        $response->assertRedirect();

        // 2. Verify Invoice Created and Partial
        $this->assertDatabaseHas('invoices', [
            'patient_id' => $this->patient->id,
            'total_amount' => 100.00,
            'status' => 'partial',
        ]);

        // 3. Verify Payment Record
        $this->assertDatabaseHas('payments', [
            'amount' => 50.00,
        ]);
    }
}
