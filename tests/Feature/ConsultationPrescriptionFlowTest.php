<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\Consultation;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConsultationPrescriptionFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_consultation_creation_redirects_to_prescription_but_is_blocked_if_status_is_completed()
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

        $role = Role::firstOrCreate(['name' => 'Doctor']);
        $p1 = Permission::firstOrCreate(['name' => 'create_prescriptions']);
        $p2 = Permission::firstOrCreate(['name' => 'create']); // Consultation create
        $role->givePermissionTo([$p1, $p2]);

        $user = User::factory()->create(['clinic_id' => $clinic->id]);
        $user->assignRole($role);

        $doctor = Doctor::create([
            'user_id' => $user->id,
            'clinic_id' => $clinic->id,
            'primary_department_id' => $department->id,
            'specialization' => 'General',
            'status' => 'active',
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
            'status' => 'arrived', // Must be arrived/confirmed
            'appointment_type' => 'in_person',
            'booking_source' => 'online',
        ]);

        $this->actingAs($user);

        // 1. Store Consultation
        // This simulates the form submission
        $response = $this->post(route('clinical.consultations.store', $appointment), [
            'diagnosis' => 'Flu',
            'doctor_notes' => 'Rest',
            'symptoms' => ['Fever'],
        ]);

        // It should redirect to prescription create page
        $consultation = Consultation::first();
        $this->assertNotNull($consultation);
        $response->assertRedirect(route('clinical.prescriptions.create.withConsultation', $consultation));

        // 2. Try to access Prescription Create Page
        // This is where the bug manifests. If status is 'completed', it redirects back with error.
        $response2 = $this->get(route('clinical.prescriptions.create.withConsultation', $consultation));

        // Assert that we are NOT redirected back (status 200 OK)
        // If bug exists, this will be a redirect (302)
        $response2->assertStatus(200);
    }
}
