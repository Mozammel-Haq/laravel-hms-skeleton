<?php

namespace Tests\Feature;

use App\Models\Clinic;
use App\Models\Consultation;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\InvoiceItem;
use App\Models\LabTest;
use App\Models\LabTestOrder;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BillingLogicTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        \Illuminate\Database\Eloquent\Model::unguard();
    }

    public function test_lab_order_billing_flow()
    {
        $clinic = Clinic::create([
            'name' => 'Test Clinic',
            'code' => 'TC001',
            'address_line_1' => 'Test Address',
            'city' => 'Test City',
            'country' => 'Test Country',
            'timezone' => 'UTC',
            'currency' => 'USD',
        ]);

        $user = User::factory()->create(['clinic_id' => $clinic->id]);
        $patient = Patient::factory()->create(['clinic_id' => $clinic->id]);

        $labTest = LabTest::create([
            'name' => 'Blood Test',
            'category' => 'Hematology',
            'description' => 'Test Description',
            'normal_range' => 'Test Range',
            'price' => 50.00,
            'status' => 'active',
        ]);

        $order = LabTestOrder::create([
            'clinic_id' => $clinic->id,
            'patient_id' => $patient->id,
            'lab_test_id' => $labTest->id,
            'order_date' => now(),
            'status' => 'pending',
        ]);

        // 1. Verify it appears in pending items
        $response = $this->actingAs($user)->getJson(route('billing.patient-items', $patient));
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'lab_tests');
        $response->assertJsonFragment(['id' => $order->id, 'type' => 'lab_order']);

        // 2. Bill the item
        $billingData = [
            'patient_id' => $patient->id,
            'items' => [
                [
                    'reference_id' => $order->id,
                    'item_type' => 'lab_order',
                    'quantity' => 1,
                    'unit_price' => 50.00,
                ],
            ],
        ];

        // Need permission
        $role = \App\Models\Role::firstOrCreate(['name' => 'Accountant']);
        $perm = \App\Models\Permission::firstOrCreate(['name' => 'create_invoices']);
        $viewPerm = \App\Models\Permission::firstOrCreate(['name' => 'view_billing']);
        $role->permissions()->syncWithoutDetaching([$perm->id, $viewPerm->id]);
        $user->roles()->syncWithoutDetaching($role);
        $user->refresh();

        $response = $this->actingAs($user)->post(route('billing.store'), $billingData);
        $response->assertRedirect();

        // 3. Verify LabTestOrder is updated with invoice_id
        $order->refresh();
        $this->assertNotNull($order->invoice_id);

        // 4. Verify it creates an InvoiceItem with type 'lab'
        $invoiceItem = InvoiceItem::where('invoice_id', $order->invoice_id)->first();
        $this->assertNotNull($invoiceItem);
        $this->assertEquals('lab', $invoiceItem->item_type);

        // 5. Verify it NO LONGER appears in pending items
        $response = $this->actingAs($user)->getJson(route('billing.patient-items', $patient));
        $response->assertJsonCount(0, 'lab_tests');
    }

    public function test_consultation_double_billing_prevention()
    {
        $clinic = Clinic::create([
            'name' => 'Test Clinic',
            'code' => 'TC002',
            'address_line_1' => 'Address',
            'city' => 'City',
            'country' => 'Country',
            'timezone' => 'UTC',
            'currency' => 'USD',
        ]);

        $department = Department::create([
            'name' => 'General',
            'clinic_id' => $clinic->id,
            'description' => 'General',
        ]);

        $user = User::factory()->create(['clinic_id' => $clinic->id]);
        $doctor = Doctor::create([
            'user_id' => $user->id,
            'clinic_id' => $clinic->id,
            'primary_department_id' => $department->id,
            'specialization' => 'General',
            'consultation_fee' => 100.00,
        ]);

        $patient = Patient::factory()->create(['clinic_id' => $clinic->id]);

        $appointment = \App\Models\Appointment::create([
            'clinic_id' => $clinic->id,
            'doctor_id' => $doctor->id,
            'patient_id' => $patient->id,
            'department_id' => $department->id,
            'appointment_date' => now(),
            'start_time' => '09:00',
            'end_time' => '09:30',
            'status' => 'completed',
            'appointment_type' => 'in_person',
            'booking_source' => 'online',
        ]);

        $visit = \App\Models\Visit::create([
            'clinic_id' => $clinic->id,
            'appointment_id' => $appointment->id,
            'check_in_time' => now(),
            'visit_status' => 'completed',
        ]);

        $consultation = Consultation::create([
            'visit_id' => $visit->id,
            'clinic_id' => $clinic->id,
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'diagnosis' => 'Test',
            'status' => 'completed',
        ]);

        // Setup permissions
        $role = \App\Models\Role::firstOrCreate(['name' => 'Accountant']);
        $perm = \App\Models\Permission::firstOrCreate(['name' => 'create_invoices']);
        $viewPerm = \App\Models\Permission::firstOrCreate(['name' => 'view_billing']);
        $role->permissions()->syncWithoutDetaching([$perm->id, $viewPerm->id]);
        $user->roles()->syncWithoutDetaching($role);

        // 1. Bill the consultation
        $billingData = [
            'patient_id' => $patient->id,
            'items' => [
                [
                    'reference_id' => $consultation->id,
                    'item_type' => 'consultation',
                    'quantity' => 1,
                    'unit_price' => 100.00,
                ],
            ],
        ];

        $this->actingAs($user)->post(route('billing.store'), $billingData)->assertRedirect();

        // 2. Try to bill it AGAIN
        $response = $this->actingAs($user)->post(route('billing.store'), $billingData);

        // Should have validation error
        $response->assertSessionHasErrors('items');
    }
}
