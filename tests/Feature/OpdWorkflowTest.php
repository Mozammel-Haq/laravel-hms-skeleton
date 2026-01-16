<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OpdWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Model::unguard();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_full_opd_flow_requires_payment_before_consultation()
    {
        $clinic = Clinic::create([
            'name' => 'Clinic A',
            'code' => 'CA',
            'address_line_1' => 'addr',
            'city' => 'City',
            'country' => 'Country',
            'timezone' => 'UTC',
            'currency' => 'USD',
        ]);

        $department = Department::create([
            'clinic_id' => $clinic->id,
            'name' => 'General Medicine',
        ]);

        $doctorUser = User::factory()->create(['clinic_id' => $clinic->id]);
        $doctorUser->assignRole('Doctor');

        $doctor = Doctor::create([
            'user_id' => $doctorUser->id,
            'primary_department_id' => $department->id,
            'specialization' => 'General Physician',
            'consultation_fee' => 100,
            'follow_up_fee' => 80,
            'status' => 'active',
        ]);
        $doctor->clinics()->attach($clinic->id);

        $patient = Patient::create([
            'clinic_id' => $clinic->id,
            'patient_code' => 'P-0001',
            'name' => 'John Doe',
        ]);

        $receptionist = User::factory()->create(['clinic_id' => $clinic->id]);
        $receptionist->assignRole('Receptionist');

        $accountant = User::factory()->create(['clinic_id' => $clinic->id]);
        $accountant->assignRole('Accountant');

        $appointment = Appointment::create([
            'clinic_id' => $clinic->id,
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'department_id' => $department->id,
            'appointment_date' => now()->toDateString(),
            'start_time' => now()->format('H:i:s'),
            'end_time' => now()->addMinutes(15)->format('H:i:s'),
            'appointment_type' => 'in_person',
            'reason_for_visit' => 'Checkup',
            'booking_source' => 'reception',
            'status' => 'pending',
            'created_by' => $receptionist->id,
        ]);

        $this->actingAs($receptionist)->patch(route('appointments.status.update', $appointment), [
            'status' => 'arrived',
        ]);

        $appointment->refresh();
        $this->assertEquals('arrived', $appointment->status);

        $this->post(route('visits.store'), [
            'appointment_id' => $appointment->id,
        ])->assertRedirect();

        $appointment->refresh();
        $this->assertEquals('arrived', $appointment->status);

        $invoice = Invoice::where('appointment_id', $appointment->id)
            ->where('invoice_type', 'consultation')
            ->first();

        $this->assertNotNull($invoice);
        $this->assertEquals('unpaid', $invoice->status);

        $responseBeforePayment = $this->actingAs($doctorUser)
            ->get(route('clinical.consultations.create', $appointment));

        $responseBeforePayment->assertRedirect(route('appointments.index'));

        $this->actingAs($accountant)->post(route('billing.payment.store', $invoice), [
            'amount' => $invoice->total_amount,
            'payment_method' => 'cash',
        ])->assertRedirect(route('billing.show', $invoice));

        $invoice->refresh();
        $this->assertEquals('paid', $invoice->status);

        $appointment->refresh();
        $this->assertEquals('confirmed', $appointment->status);

        $responseAfterPayment = $this->actingAs($doctorUser)
            ->get(route('clinical.consultations.create', $appointment));

        $responseAfterPayment->assertOk();
        $responseAfterPayment->assertSee($patient->name);
    }
}

