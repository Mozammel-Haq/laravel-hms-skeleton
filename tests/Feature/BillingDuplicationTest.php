<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\Consultation;
use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Patient;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\Department;

class BillingDuplicationTest extends TestCase
{
    use RefreshDatabase;

    public function test_consultation_is_excluded_from_pending_items_if_already_billed()
    {
        \Illuminate\Database\Eloquent\Model::unguard();

        // Setup
        $clinic = Clinic::create([
            'name' => 'Test Clinic',
            'code' => 'TC001',
            'address_line_1' => '123 Test St',
            'city' => 'Test City',
            'country' => 'Test Country',
            'timezone' => 'UTC',
            'currency' => 'USD',
        ]);

        $department = Department::create([
            'name' => 'General',
            'clinic_id' => $clinic->id,
            'description' => 'General Department',
            'status' => 'active',
        ]);

        $user = User::factory()->create(['clinic_id' => $clinic->id]);
        $doctor = Doctor::create([
            'user_id' => $user->id,
            'clinic_id' => $clinic->id,
            'primary_department_id' => $department->id,
            'specialization' => 'General',
            'status' => 'active',
            'consultation_fee' => 50.00,
        ]);

        $patient = Patient::factory()->create(['clinic_id' => $clinic->id]);

        $appointment = Appointment::create([
            'clinic_id' => $clinic->id,
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'department_id' => $department->id,
            'appointment_date' => now(),
            'start_time' => '09:00',
            'end_time' => '09:30',
            'status' => 'completed',
            'appointment_type' => 'in_person',
            'booking_source' => 'online',
        ]);

        $visit = Visit::create([
            'clinic_id' => $clinic->id,
            'appointment_id' => $appointment->id,
            'check_in_time' => now(),
            'visit_status' => 'completed',
        ]);

        $consultation = Consultation::create([
            'visit_id' => $visit->id,
            'clinic_id' => $clinic->id,
            'doctor_id' => $doctor->id,
            'patient_id' => $patient->id,
            'diagnosis' => 'Test Diagnosis',
            'status' => 'completed',
        ]);

        // Create Invoice and InvoiceItem for this consultation
        // Emulate BillingController logic
        $invoice = Invoice::create([
            'clinic_id' => $clinic->id,
            'patient_id' => $patient->id,
            'invoice_number' => 'INV-001',
            'issued_at' => now(),
            'subtotal' => 50.00,
            'discount' => 0.00,
            'tax' => 0.00,
            'total_amount' => 50.00,
            'status' => 'paid',
        ]);

        InvoiceItem::create([
            'clinic_id' => $clinic->id,
            'invoice_id' => $invoice->id,
            'item_type' => 'consultation', // BillingController uses singular 'consultation'
            'reference_id' => $consultation->id,
            'description' => 'Consultation Fee',
            'quantity' => 1,
            'unit_price' => 50.00,
            'total_price' => 50.00,
        ]);

        // Check if Consultation thinks it has an invoiceItem
        // If relationship is broken (looking for 'consultations'), this will be null
        $hasInvoiceItem = $consultation->invoiceItem;

        // Query used by BillingController::getPatientItems
        $pendingConsultations = Consultation::where('patient_id', $patient->id)
            ->whereDoesntHave('invoiceItem')
            ->get();

        // If bug exists:
        // 1. $hasInvoiceItem will be null
        // 2. $pendingConsultations will contain the consultation (count 1)

        // We assert that we expect NO pending consultations
        $this->assertCount(0, $pendingConsultations, "Consultation appeared in pending list despite being billed!");
    }
}
