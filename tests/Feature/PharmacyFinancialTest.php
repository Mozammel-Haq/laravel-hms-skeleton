<?php

namespace Tests\Feature;

use App\Models\Clinic;
use App\Models\Expense;
use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Models\Patient;
use App\Models\User;
use App\Models\Role;
use App\Models\Prescription;
use App\Models\Consultation;
use App\Models\Visit;
use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\PharmacySale;
use App\Models\PharmacySaleItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\RolePermissionSeeder;

class PharmacyFinancialTest extends TestCase
{
    use RefreshDatabase;

    protected $clinic;
    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles
        $this->seed(RolePermissionSeeder::class);

        // Setup Clinic and Admin
        $this->clinic = Clinic::create([
            'name' => 'Test Clinic',
            'code' => 'TEST-CLINIC',
            'address_line_1' => '123 Test St',
            'city' => 'Test City',
            'country' => 'Test Country',
            'status' => 'active',
            'timezone' => 'UTC',
            'currency' => 'USD',
        ]);
        $this->admin = User::factory()->create(['clinic_id' => $this->clinic->id]);
        $this->admin->assignRole('Clinic Admin');
    }

    public function test_medicine_purchase_creates_expense()
    {
        $this->actingAs($this->admin);

        $medicine = Medicine::create([
            'name' => 'Test Medicine',
            'generic_name' => 'Generic Test',
            'form' => 'Tablet',
            'strength' => '500mg',
            'price' => 20.00,
            'manufacturer' => 'Test Pharma',
            'status' => 'active',
        ]);

        $response = $this->post(route('pharmacy.inventory.store'), [
            'medicine_id' => $medicine->id,
            'batch_number' => 'BATCH-001',
            'expiry_date' => now()->addYear()->format('Y-m-d'),
            'quantity_in_stock' => 100,
            'purchase_price' => 10.50, // Cost per unit
        ]);

        $response->assertRedirect(route('pharmacy.inventory.index'));
        $response->assertSessionHas('success');

        // Check Batch
        $this->assertDatabaseHas('medicine_batches', [
            'batch_number' => 'BATCH-001',
            'quantity_in_stock' => 100,
            'purchase_price' => 10.50,
            'clinic_id' => $this->clinic->id,
        ]);

        // Check Expense
        // Total Expense = 100 * 10.50 = 1050.00
        $this->assertDatabaseHas('expenses', [
            'clinic_id' => $this->clinic->id,
            'amount' => 1050.00,
            'category' => 'medicine_purchase',
            'reference_type' => MedicineBatch::class,
        ]);
    }

    public function test_pharmacy_sale_generates_revenue_and_profit_logic()
    {
        $this->actingAs($this->admin);

        // 1. Create Medicine and Batch (Purchase)
        $medicine = Medicine::create([
            'name' => 'Test Medicine',
            'generic_name' => 'Generic Test',
            'form' => 'Tablet',
            'strength' => '500mg',
            'price' => 20.00,
            'manufacturer' => 'Test Pharma',
            'status' => 'active',
        ]);

        $purchasePrice = 10.00;

        $batch = MedicineBatch::create([
            'clinic_id' => $this->clinic->id,
            'medicine_id' => $medicine->id,
            'batch_number' => 'BATCH-TEST-1',
            'expiry_date' => now()->addYear(),
            'quantity_in_stock' => 50,
            'purchase_price' => $purchasePrice,
        ]);

        // 2. Setup Patient and Prescription chain
        $patient = Patient::factory()->create(['clinic_id' => $this->clinic->id]);
        $doctor = User::factory()->create(['clinic_id' => $this->clinic->id]);
        $doctor->assignRole('Doctor');

        // Create Department
        $department = \App\Models\Department::factory()->create(['clinic_id' => $this->clinic->id]);

        // Create Doctor Model
        $doctorProfile = \App\Models\Doctor::create([
            'clinic_id' => $this->clinic->id,
            'user_id' => $doctor->id,
            'primary_department_id' => $department->id,
            'first_name' => 'Doctor',
            'last_name' => 'Test',
            'specialization' => 'General',
            'license_number' => 'DOC123',
            'phone' => '1234567890',
            'status' => 'active',
        ]);
        // Link doctor to clinic
        $doctorProfile->clinics()->attach($this->clinic->id);

        $appointment = Appointment::create([
            'clinic_id' => $this->clinic->id,
            'department_id' => $department->id,
            'patient_id' => $patient->id,
            'doctor_id' => $doctorProfile->id,
            'appointment_date' => now(),
            'start_time' => '10:00',
            'end_time' => '10:30',
            'status' => 'completed',
            'appointment_type' => 'in_person',
            'booking_source' => 'online',
        ]);

        $visit = Visit::create([
            'clinic_id' => $this->clinic->id,
            'appointment_id' => $appointment->id,
            'visit_status' => 'completed',
        ]);

        $consultation = Consultation::create([
            'visit_id' => $visit->id,
            'doctor_id' => $doctorProfile->id,
            'patient_id' => $patient->id,
            'clinic_id' => $this->clinic->id,
            'notes' => 'Test notes',
        ]);

        $prescription = Prescription::create([
            'consultation_id' => $consultation->id,
            'clinic_id' => $this->clinic->id,
            'issued_at' => now(),
        ]);

        // 3. Perform Sale
        // Selling 5 units
        // Cost = 5 * 10.00 = 50.00
        // Revenue = 5 * 20.00 = 100.00

        $response = $this->post(route('pharmacy.store'), [
            'patient_id' => $patient->id,
            'prescription_id' => $prescription->id,
            'items' => [
                [
                    'medicine_id' => $medicine->id,
                    'quantity' => 5
                ]
            ]
        ]);

        $response->assertSessionHas('success');

        // 4. Verify Invoice (Revenue)
        $invoice = Invoice::where('patient_id', $patient->id)->latest()->first();
        $this->assertNotNull($invoice);
        $this->assertEquals(100.00, $invoice->total_amount); // 5 * 20.00
        $this->assertEquals('pharmacy', $invoice->invoice_type);

        // 5. Verify Profit Logic (Unit Cost)
        $saleItem = PharmacySaleItem::where('medicine_id', $medicine->id)->first();
        $this->assertNotNull($saleItem);
        $this->assertEquals(10.00, $saleItem->unit_cost); // Should match batch purchase price

        // Profit Calculation Verification
        $revenue = $saleItem->quantity * $saleItem->unit_price; // 5 * 20 = 100
        $cost = $saleItem->quantity * $saleItem->unit_cost; // 5 * 10 = 50
        $profit = $revenue - $cost; // 50

        $this->assertEquals(50.00, $profit);
    }
}
