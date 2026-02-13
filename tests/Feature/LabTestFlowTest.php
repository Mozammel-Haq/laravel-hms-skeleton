<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\LabTest;
use App\Models\LabTestOrder;
use App\Models\Patient;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LabTestFlowTest extends TestCase
{
    use RefreshDatabase;

    protected $clinic;

    protected $doctor;

    protected $patient;

    protected $receptionist;

    protected $labTechnician;

    protected $labTest;

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
        $recepRole = Role::firstOrCreate(['name' => 'Receptionist']);
        $recepPermissions = ['view_lab', 'create_lab_test_orders', 'view_lab_test_orders', 'view_invoices', 'create_invoices'];
        foreach ($recepPermissions as $perm) {
            $p = Permission::firstOrCreate(['name' => $perm]);
            // Check if role already has permission
            if (! $recepRole->permissions()->where('name', $perm)->exists()) {
                $recepRole->permissions()->attach($p);
            }
        }

        $techRole = Role::firstOrCreate(['name' => 'Lab Technician']);
        $techPermissions = ['view_lab', 'view_lab_test_orders', 'add_lab_test_results', 'view_lab_test_results'];
        foreach ($techPermissions as $perm) {
            $p = Permission::firstOrCreate(['name' => $perm]);
            if (! $techRole->permissions()->where('name', $perm)->exists()) {
                $techRole->permissions()->attach($p);
            }
        }

        // 3. Setup Users
        $this->receptionist = User::factory()->create([
            'clinic_id' => $this->clinic->id,
            'name' => 'Receptionist',
        ]);
        $this->receptionist->assignRole($recepRole);

        $this->labTechnician = User::factory()->create([
            'clinic_id' => $this->clinic->id,
            'name' => 'Lab Tech',
        ]);
        $this->labTechnician->assignRole($techRole);

        // 4. Setup Doctor & Department
        $department = Department::forceCreate([
            'clinic_id' => $this->clinic->id,
            'name' => 'Pathology',
            'status' => 'active',
        ]);

        $doctorUser = User::factory()->create([
            'clinic_id' => $this->clinic->id,
            'name' => 'Dr. Path',
        ]);

        $this->doctor = Doctor::forceCreate([
            'user_id' => $doctorUser->id,
            'clinic_id' => $this->clinic->id,
            'primary_department_id' => $department->id,
            'specialization' => 'Pathology',
            'license_number' => 'PATH123',
            'status' => 'active',
        ]);

        // 5. Setup Patient
        $this->patient = Patient::forceCreate([
            'clinic_id' => $this->clinic->id,
            'name' => 'John Doe',
            'patient_code' => 'P001',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
            'phone' => '1234567890',
            'address' => '123 Test St',
        ]);

        // 6. Setup Lab Test
        $this->labTest = LabTest::forceCreate([
            'name' => 'Complete Blood Count',
            'category' => 'Hematology',
            'price' => 50.00,
            'status' => 'active',
        ]);
    }

    public function test_complete_lab_flow_order_invoice_result()
    {
        Storage::fake('local');

        // 1. Create Appointment (Prerequisite for non-admitted patients)
        $appointment = Appointment::forceCreate([
            'clinic_id' => $this->clinic->id,
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id,
            'department_id' => $this->doctor->primary_department_id,
            'appointment_date' => now()->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '10:15:00',
            'status' => 'completed', // Must be completed for lab order
            'appointment_type' => 'in_person',
            'booking_source' => 'reception',
        ]);

        // 2. Create Lab Order (Receptionist)
        $response = $this->actingAs($this->receptionist)->post(route('lab.store'), [
            'patient_id' => $this->patient->id,
            'lab_test_id' => $this->labTest->id,
            'doctor_id' => $this->doctor->id,
            'appointment_id' => $appointment->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('lab_test_orders', [
            'patient_id' => $this->patient->id,
            'lab_test_id' => $this->labTest->id,
            'status' => 'pending',
        ]);

        $order = LabTestOrder::where('patient_id', $this->patient->id)->first();

        // 3. Generate Invoice (Receptionist)
        $response = $this->actingAs($this->receptionist)->post(route('lab.invoice.generate', $order));
        $response->assertRedirect();

        $order->refresh();
        $this->assertNotNull($order->invoice_id);
        $this->assertEquals('unpaid', $order->invoice->status);

        // 4. Try to add result BEFORE payment (Should fail/redirect)
        $response = $this->actingAs($this->labTechnician)->get(route('lab.result.add', $order));
        $response->assertRedirect();
        $response->assertSessionHas('error');

        // 5. Pay Invoice
        $order->invoice->update(['status' => 'paid']);

        // 6. Add Result (Lab Technician)
        $file = UploadedFile::fake()->create('report.pdf', 100);
        $response = $this->actingAs($this->labTechnician)->post(route('lab.result.store', $order), [
            'test_date' => now()->toDateString(),
            'result_value' => 'Normal',
            'notes' => 'All parameters within range',
            'attachment' => $file,
        ]);

        $response->assertRedirect();

        $order->refresh();
        $this->assertEquals('completed', $order->status);
        $this->assertDatabaseHas('lab_test_results', [
            'lab_test_order_id' => $order->id,
            'result_value' => 'Normal',
        ]);
    }
}
