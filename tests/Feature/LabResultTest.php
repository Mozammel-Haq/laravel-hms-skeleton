<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\LabTest;
use App\Models\LabTestOrder;
use App\Models\LabTestResult;
use App\Models\Patient;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Clinic;
use App\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class LabResultTest extends TestCase
{
    use RefreshDatabase;

    protected $clinic;
    protected $department;
    protected $doctor;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        Model::unguard();

        // Create Permissions
        $p1 = Permission::create(['name' => 'view_lab']);
        $p2 = Permission::create(['name' => 'create_lab_order']);
        $p3 = Permission::create(['name' => 'view_lab_test_order']);
        $p4 = Permission::create(['name' => 'update_lab_test_order']);

        // Create Role
        if (!Role::where('name', 'Doctor')->exists()) {
            $role = Role::create(['name' => 'Doctor']);
            $role->givePermissionTo([$p1, $p2, $p3, $p4]);
        } else {
            $role = Role::where('name', 'Doctor')->first();
            $role->givePermissionTo([$p1, $p2, $p3, $p4]);
        }

        // Create Clinic and Department
        $this->clinic = Clinic::create([
            'name' => 'Test Clinic',
            'code' => 'TC001',
            'address_line_1' => '123 Test St',
            'city' => 'Test City',
            'country' => 'Test Country',
            'timezone' => 'UTC',
            'currency' => 'USD',
            'phone' => '1234567890',
            'email' => 'clinic@test.com',
            'status' => 'active'
        ]);

        $this->department = Department::create([
            'name' => 'General',
            'clinic_id' => $this->clinic->id,
            'description' => 'General Department',
            'status' => 'active'
        ]);

        $this->user = User::factory()->create(['clinic_id' => $this->clinic->id]);
        $this->user->assignRole('Doctor');

        $this->doctor = Doctor::create([
            'user_id' => $this->user->id,
            'primary_department_id' => $this->department->id,
            'specialization' => 'General',
            'status' => 'active',
        ]);
    }

    public function test_can_add_lab_result_to_paid_order()
    {
        $this->actingAs($this->user);

        $patient = Patient::create([
            'name' => 'Test Patient',
            'clinic_id' => $this->clinic->id,
            'status' => 'active',
            'patient_code' => 'P1001',
            'date_of_birth' => '2000-01-01',
            'gender' => 'male',
            'phone' => '1234567890',
            'address' => 'Test Address',
        ]);

        $test = LabTest::create([
            'name' => 'Blood Test',
            'category' => 'Hematology',
            'price' => 100,
            'status' => 'active',
        ]);

        $order = LabTestOrder::create([
            'clinic_id' => $this->clinic->id,
            'patient_id' => $patient->id,
            'doctor_id' => $this->doctor->id,
            'lab_test_id' => $test->id,
            'status' => 'pending',
            'order_date' => now(),
        ]);

        // Create Invoice
        $invoice = Invoice::create([
            'clinic_id' => $this->clinic->id,
            'patient_id' => $patient->id,
            'invoice_number' => 'INV-001',
            'subtotal' => 100,
            'total_amount' => 100,
            'status' => 'paid',
        ]);

        $order->update(['invoice_id' => $invoice->id]);

        $response = $this->post(route('lab.result.store', $order), [
            'result_value' => 'Normal',
            'notes' => 'Everything looks good',
        ]);

        $response->assertRedirect(route('lab.show', $order));
        $response->assertSessionHas('success', 'Result recorded successfully.');

        $this->assertDatabaseHas('lab_test_results', [
            'lab_test_order_id' => $order->id,
            'lab_test_id' => $test->id,
            'result_value' => 'Normal',
            'remarks' => 'Everything looks good',
        ]);

        $this->assertEquals('completed', $order->fresh()->status);
    }

    public function test_can_download_lab_result_pdf()
    {
        Storage::fake('public');
        $this->actingAs($this->user);

        $patient = Patient::create([
            'name' => 'Test Patient',
            'clinic_id' => $this->clinic->id,
            'status' => 'active',
            'patient_code' => 'P1002',
            'date_of_birth' => '2000-01-01',
            'gender' => 'male',
            'phone' => '1234567891',
            'address' => 'Test Address',
        ]);

        $test = LabTest::create([
            'name' => 'Blood Test',
            'category' => 'Hematology',
            'price' => 100,
            'status' => 'active',
        ]);

        $order = LabTestOrder::create([
            'clinic_id' => $this->clinic->id,
            'patient_id' => $patient->id,
            'doctor_id' => $this->doctor->id,
            'lab_test_id' => $test->id,
            'status' => 'completed',
            'order_date' => now(),
        ]);

        $pdfContent = 'dummy pdf content';
        $path = 'lab_results/test.pdf';
        Storage::disk('public')->put($path, $pdfContent);

        $result = LabTestResult::create([
            'clinic_id' => $this->clinic->id,
            'lab_test_order_id' => $order->id,
            'lab_test_id' => $test->id,
            'result_value' => 'Normal',
            'remarks' => 'Good',
            'reported_at' => now(),
            'reported_by' => $this->user->id,
            'pdf_path' => $path,
        ]);

        $response = $this->get(route('lab.results.download', $result));

        $response->assertStatus(200);
        $response->assertHeader('content-disposition');
    }

    public function test_can_preview_lab_result_pdf()
    {
        Storage::fake('public');
        $this->actingAs($this->user);

        $patient = Patient::create([
            'name' => 'Test Patient',
            'clinic_id' => $this->clinic->id,
            'status' => 'active',
            'patient_code' => 'P1003',
            'date_of_birth' => '2000-01-01',
            'gender' => 'male',
            'phone' => '1234567892',
            'address' => 'Test Address',
        ]);

        $test = LabTest::create([
            'name' => 'Blood Test',
            'category' => 'Hematology',
            'price' => 100,
            'status' => 'active',
        ]);

        $order = LabTestOrder::create([
            'clinic_id' => $this->clinic->id,
            'patient_id' => $patient->id,
            'doctor_id' => $this->doctor->id,
            'lab_test_id' => $test->id,
            'status' => 'completed',
            'order_date' => now(),
        ]);

        $pdfContent = '%PDF-1.4 dummy pdf content';
        $path = 'lab_results/preview_test.pdf';
        Storage::disk('public')->put($path, $pdfContent);

        $result = LabTestResult::create([
            'clinic_id' => $this->clinic->id,
            'lab_test_order_id' => $order->id,
            'lab_test_id' => $test->id,
            'result_value' => 'Normal',
            'remarks' => 'Good',
            'reported_at' => now(),
            'reported_by' => $this->user->id,
            'pdf_path' => $path,
        ]);

        $response = $this->get(route('lab.results.view', $result));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_lab_order_view_shows_correct_result_columns_and_actions()
    {
        Storage::fake('public');
        $this->actingAs($this->user);

        $patient = Patient::create([
            'name' => 'Test Patient',
            'clinic_id' => $this->clinic->id,
            'status' => 'active',
            'patient_code' => 'P1004',
            'date_of_birth' => '2000-01-01',
            'gender' => 'male',
            'phone' => '1234567893',
            'address' => 'Test Address',
        ]);

        $test = LabTest::create([
            'name' => 'Blood Test',
            'category' => 'Hematology',
            'price' => 100,
            'status' => 'active',
        ]);

        $order = LabTestOrder::create([
            'clinic_id' => $this->clinic->id,
            'patient_id' => $patient->id,
            'doctor_id' => $this->doctor->id,
            'lab_test_id' => $test->id,
            'status' => 'completed',
            'order_date' => now(),
        ]);

        $pdfContent = '%PDF-1.4 dummy pdf content';
        $path = 'lab_results/view_test.pdf';
        Storage::disk('public')->put($path, $pdfContent);

        $result = LabTestResult::create([
            'clinic_id' => $this->clinic->id,
            'lab_test_order_id' => $order->id,
            'lab_test_id' => $test->id,
            'result_value' => 'Normal',
            'remarks' => 'Everything looks fine',
            'reported_at' => now(),
            'reported_by' => $this->user->id,
            'pdf_path' => $path,
        ]);

        $response = $this->get(route('lab.show', $order));

        $response->assertStatus(200);
        $response->assertSee('Remarks');
        $response->assertSee('Actions');
        $response->assertSee('Everything looks fine');
        $response->assertSee('View Result');
        $response->assertSee('Preview Result');
        $response->assertSee('Download Result');
        $response->assertSee('showResultModal');
    }
}
