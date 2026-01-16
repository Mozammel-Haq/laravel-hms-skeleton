<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\Patient;
use App\Models\Role;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VisitProcedureInvoiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_receptionist_can_create_procedure_invoice_for_visit()
    {
        $clinic = Clinic::create([
            'name' => 'Clinic A',
            'code' => 'CA',
            'address_line_1' => 'addr',
            'city' => 'c',
            'country' => 'x',
            'timezone' => 'UTC',
            'currency' => 'USD'
        ]);
        $user = User::factory()->create(['clinic_id' => $clinic->id]);
        $role = Role::firstOrCreate(['name' => 'Receptionist'], ['description' => 'Front Desk']);
        $role->permissions()->syncWithoutDetaching([
            \App\Models\Permission::firstOrCreate(['name' => 'create_invoices'])->id,
        ]);
        $user->assignRole($role);

        $patient = Patient::create(['clinic_id' => $clinic->id, 'patient_code' => 'P-0001', 'name' => 'John Doe']);
        $doctorUser = User::factory()->create();
        $department = \App\Models\Department::create(['name' => 'General Medicine', 'clinic_id' => $clinic->id]);
        $doctor = \App\Models\Doctor::create([
            'user_id' => $doctorUser->id,
            'primary_department_id' => $department->id,
            'specialization' => 'General Physician',
        ]);
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
            'status' => 'confirmed',
            'created_by' => $user->id,
        ]);
        $visit = Visit::create(['clinic_id' => $clinic->id, 'appointment_id' => $appointment->id, 'check_in_time' => now(), 'visit_status' => 'in_progress']);

        $resp = $this->actingAs($user)->post(route('visits.procedure.store', $visit), [
            'description' => 'Nebulization',
            'quantity' => 1,
            'unit_price' => 250,
        ]);
        $resp->assertRedirect(route('visits.show', $visit));

        $this->assertDatabaseHas('invoices', [
            'clinic_id' => $clinic->id,
            'patient_id' => $patient->id,
            'visit_id' => $visit->id,
            'invoice_type' => 'procedure',
            'state' => 'finalized',
        ]);
    }
}
