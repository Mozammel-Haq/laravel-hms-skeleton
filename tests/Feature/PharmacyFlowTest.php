<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\Consultation;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Models\Patient;
use App\Models\Permission;
use App\Models\PharmacySale;
use App\Models\Prescription;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PharmacyFlowTest extends TestCase
{
    use RefreshDatabase;

    protected $clinic;

    protected $doctor;

    protected $patient;

    protected $pharmacist;

    protected $medicine;

    protected $prescription;

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
        $pharmPermissions = ['view_pharmacy', 'create_pharmacy_sales', 'view_medicines', 'view_invoices'];
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

        // 4. Setup Doctor & Department
        $department = Department::forceCreate([
            'clinic_id' => $this->clinic->id,
            'name' => 'General',
            'status' => 'active',
        ]);

        $doctorUser = User::factory()->create([
            'clinic_id' => $this->clinic->id,
            'name' => 'Dr. Pharm',
        ]);

        $this->doctor = Doctor::forceCreate([
            'user_id' => $doctorUser->id,
            'clinic_id' => $this->clinic->id,
            'primary_department_id' => $department->id,
            'specialization' => 'General',
            'license_number' => 'DOC123',
            'status' => 'active',
        ]);

        // 5. Setup Patient
        $this->patient = Patient::forceCreate([
            'clinic_id' => $this->clinic->id,
            'name' => 'Jane Doe',
            'patient_code' => 'P002',
            'date_of_birth' => '1992-01-01',
            'gender' => 'female',
            'phone' => '0987654321',
            'address' => '456 Test Ave',
        ]);

        // 6. Setup Medicine & Batch
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

        // 7. Setup Appointment, Consultation, Prescription
        $appointment = Appointment::forceCreate([
            'clinic_id' => $this->clinic->id,
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id,
            'department_id' => $department->id,
            'appointment_date' => now()->toDateString(),
            'start_time' => '09:00:00',
            'end_time' => '09:15:00',
            'status' => 'completed',
            'appointment_type' => 'in_person',
            'booking_source' => 'reception',
        ]);

        $visit = \App\Models\Visit::forceCreate([
            'clinic_id' => $this->clinic->id,
            'appointment_id' => $appointment->id,
            'visit_status' => 'completed',
            'check_in_time' => now()->subHour(),
            'check_out_time' => now(),
        ]);

        $consultation = Consultation::create([
            'clinic_id' => $this->clinic->id,
            'visit_id' => $visit->id,
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id,
            'status' => 'completed',
            'doctor_notes' => 'Test notes',
        ]);

        // Fix visit_id logic if needed, but for now just create prescription
        $this->prescription = Prescription::create([
            'clinic_id' => $this->clinic->id,
            'consultation_id' => $consultation->id,
            'issued_at' => now(),
            'notes' => 'Take rest',
        ]);
    }

    public function test_complete_pharmacy_flow_sale_invoice_stock()
    {
        // 1. Process Sale (Pharmacist)
        $response = $this->actingAs($this->pharmacist)->post(route('pharmacy.store'), [
            'patient_id' => $this->patient->id,
            'prescription_id' => $this->prescription->id,
            'items' => [
                [
                    'medicine_id' => $this->medicine->id,
                    'quantity' => 10,
                ],
            ],
            'discount' => 0,
            'tax' => 0,
        ]);

        $response->assertRedirect();

        // 2. Verify Sale Created
        $this->assertDatabaseHas('pharmacy_sales', [
            'patient_id' => $this->patient->id,
            'clinic_id' => $this->clinic->id,
            'total_amount' => 100.00, // 10 * 10.00
        ]);

        $sale = PharmacySale::where('patient_id', $this->patient->id)->first();
        $response->assertRedirect(route('pharmacy.show', $sale));

        // 3. Verify Stock Deducted
        $this->assertDatabaseHas('medicine_batches', [
            'medicine_id' => $this->medicine->id,
            'batch_number' => 'BATCH001',
            'quantity_in_stock' => 90, // 100 - 10
        ]);

        // 4. Verify Invoice Created
        $this->assertDatabaseHas('invoices', [
            'patient_id' => $this->patient->id,
            'clinic_id' => $this->clinic->id,
            'invoice_type' => 'pharmacy',
            'total_amount' => 100.00,
            'status' => 'unpaid',
        ]);
    }
}
