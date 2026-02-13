<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\Consultation;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\Medicine;
use App\Models\Patient;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConsultationFlowTest extends TestCase
{
    use RefreshDatabase;

    protected $clinic;

    protected $doctor;

    protected $patient;

    protected $user;

    protected $appointment;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Setup Clinic
        $this->clinic = Clinic::create([
            'name' => 'Test Clinic',
            'code' => 'TC001',
            'address_line_1' => '123 Main St',
            'city' => 'Test City',
            'country' => 'Test Country',
            'timezone' => 'UTC',
            'currency' => 'USD',
        ]);

        // 2. Setup Doctor
        $doctorUser = User::factory()->create([
            'clinic_id' => $this->clinic->id,
            'name' => 'Dr. Smith',
            'email' => 'doctor@example.com',
        ]);
        $doctorRole = Role::firstOrCreate(['name' => 'Doctor']);
        $doctorUser->assignRole($doctorRole);

        // Give permissions to Role
        $permissions = ['view_consultations', 'create_consultations', 'view_prescriptions', 'create_prescriptions'];
        foreach ($permissions as $perm) {
            $p = Permission::firstOrCreate(['name' => $perm]);
            $doctorRole->givePermissionTo($p);
        }

        $department = Department::forceCreate([
            'clinic_id' => $this->clinic->id,
            'name' => 'General Medicine',
            'status' => 'active',
        ]);

        $this->doctor = Doctor::create([
            'user_id' => $doctorUser->id,
            'clinic_id' => $this->clinic->id,
            'primary_department_id' => $department->id,
            'specialization' => ['General'],
            'license_number' => 'DOC123',
            'status' => 'active',
        ]);

        $this->user = $doctorUser;

        // 3. Setup Patient
        $this->patient = Patient::factory()->create([
            'clinic_id' => $this->clinic->id,
        ]);

        // 4. Setup Appointment (Confirmed and Paid)
        $this->appointment = Appointment::forceCreate([
            'clinic_id' => $this->clinic->id,
            'doctor_id' => $this->doctor->id,
            'patient_id' => $this->patient->id,
            'department_id' => $department->id,
            'appointment_date' => now(), // Today
            'start_time' => '10:00:00',
            'end_time' => '10:30:00',
            'status' => 'confirmed',
            'appointment_type' => 'in_person',
            'booking_source' => 'reception',
            'created_by' => $this->user->id,
        ]);

        // 5. Create Paid Invoice for Consultation (Required by Controller)
        Invoice::forceCreate([
            'clinic_id' => $this->clinic->id,
            'appointment_id' => $this->appointment->id,
            'patient_id' => $this->patient->id,
            'invoice_type' => 'consultation',
            'subtotal' => 50.00,
            'total_amount' => 50.00,
            'invoice_number' => 'INV-TEST-001',
            'status' => 'paid',
            'issued_at' => now(),
            'created_by' => $this->user->id,
        ]);

        // 6. Create Medicine for Prescription
        Medicine::forceCreate([
            'name' => 'Paracetamol',
            'generic_name' => 'Paracetamol',
            'manufacturer' => 'Pharma Inc',
            'status' => 'active',
            'price' => 2.00,
        ]);
    }

    public function test_consultation_lifecycle_bugs()
    {
        // Step 1: Doctor starts consultation
        $response = $this->actingAs($this->user)->get(route('clinical.consultations.create', $this->appointment));
        $response->assertStatus(200);

        // Step 2: Store Consultation
        $consultationData = [
            'diagnosis' => 'Flu',
            'doctor_notes' => 'Patient has flu symptoms.',
            'symptoms' => ['Fever', 'Cough'],
            'follow_up_required' => true,
            'follow_up_date' => now()->addDays(7)->toDateString(),
        ];

        $response = $this->actingAs($this->user)->post(route('clinical.consultations.store', $this->appointment), $consultationData);

        // Assert redirect to prescription create
        $consultation = Consultation::latest()->first();
        $this->assertNotNull($consultation, 'Consultation was not created.');
        $response->assertRedirect(route('clinical.prescriptions.create.withConsultation', $consultation));

        // BUG VERIFICATION 1: Check clinic_id
        $this->assertEquals($this->clinic->id, $consultation->clinic_id, 'Consultation clinic_id is missing or incorrect.');

        // BUG VERIFICATION 2: Check status (should be in_progress)
        $this->assertEquals('in_progress', $consultation->status, 'Consultation status should be in_progress after creation.');

        // Step 3: Create Prescription (which should complete the consultation)
        $prescriptionData = [
            'notes' => 'Take rest',
            'items' => [
                [
                    'medicine_id' => Medicine::first()->id,
                    'dosage' => '500mg',
                    'frequency' => '1-0-1',
                    'duration_days' => 5,
                    'instructions' => 'After food',
                ],
            ],
        ];

        $response = $this->actingAs($this->user)->post(route('clinical.prescriptions.store', $consultation), $prescriptionData);
        $response->assertRedirect();

        // Refresh consultation
        $consultation->refresh();

        // BUG VERIFICATION 3: Check status (should be completed)
        $this->assertEquals('completed', $consultation->status, 'Consultation status should be completed after prescription.');
    }
}
