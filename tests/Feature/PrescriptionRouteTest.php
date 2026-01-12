<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\Consultation;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Visit;
use App\Models\Clinic;
use App\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use Illuminate\Database\Eloquent\Model;

class PrescriptionRouteTest extends TestCase
{
    use RefreshDatabase;

    public function test_prescription_store_route_url_generation()
    {
        Model::unguard();

        // Setup
        $clinic = Clinic::create(['name' => 'Test Clinic', 'code' => 'TC', 'address_line_1' => 'A', 'city' => 'C', 'country' => 'C', 'currency' => 'USD', 'timezone' => 'UTC']);
        $user = User::factory()->create(['clinic_id' => $clinic->id]);
        $doctorRole = Role::create(['name' => 'Doctor']);

        // Assign the required permissions to the Role
        $createPermission = \App\Models\Permission::create(['name' => 'create_prescriptions']);
        $viewPermission = \App\Models\Permission::create(['name' => 'view_prescriptions']);
        $viewConsultationPermission = \App\Models\Permission::create(['name' => 'view_consultations']);
        $performConsultationPermission = \App\Models\Permission::create(['name' => 'perform_consultation']);

        $doctorRole->permissions()->attach([
            $createPermission->id,
            $viewPermission->id,
            $viewConsultationPermission->id,
            $performConsultationPermission->id
        ]);

        $user->roles()->attach($doctorRole);
        $this->actingAs($user);

        // Set clinic context in session as required by EnsureClinicContext middleware
        session(['selected_clinic_id' => $clinic->id]);

        $department = Department::create(['clinic_id' => $clinic->id, 'name' => 'General', 'status' => 'active']);
        $doctor = Doctor::create(['user_id' => $user->id, 'specialization' => 'General', 'primary_department_id' => $department->id]);

        $patient = Patient::create(['clinic_id' => $clinic->id, 'name' => 'Patient', 'patient_code' => 'P001', 'date_of_birth' => '1990-01-01', 'gender' => 'male', 'phone' => '1234567890']);

        $appointment = Appointment::create([
            'clinic_id' => $clinic->id,
            'doctor_id' => $doctor->id,
            'patient_id' => $patient->id,
            'department_id' => $department->id,
            'appointment_date' => now()->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '10:30:00',
            'status' => 'confirmed',
            'appointment_type' => 'Walk-in',
            'booking_source' => 'Walk-in',
            'created_by' => $user->id
        ]);

        $visit = Visit::create([
            'clinic_id' => $clinic->id,
            'appointment_id' => $appointment->id,
            'check_in_time' => now(),
            'visit_status' => 'in_progress'
        ]);

        $consultation = Consultation::create([
            'clinic_id' => $clinic->id,
            'visit_id' => $visit->id,
            'doctor_id' => $doctor->id,
            'patient_id' => $patient->id,
            'doctor_notes' => 'Notes',
            'diagnosis' => 'Diagnosis',
            'status' => 'pending' // Not completed
        ]);

        // Check URL generation
        $url = route('clinical.prescriptions.store', ['consultation' => $consultation]);
        echo "\nGenerated URL: " . $url . "\n";

        $this->assertStringContainsString('/clinical/prescriptions/' . $consultation->id, $url);

        // Check if the route is accessible via POST
        $response = $this->post($url, [
            'items' => [
                [
                    'medicine_id' => 1,
                    'dosage' => '1-0-1',
                    'frequency' => 'Daily',
                    'duration_days' => 5,
                    'instructions' => 'After food'
                ]
            ],
            'notes' => 'Test prescription'
        ]);

        $this->assertNotEquals(405, $response->status());
        $this->assertEquals(302, $response->status()); // Should redirect after success or validation error
    }

    public function test_prescription_create_page_has_correct_form_action()
    {
        Model::unguard();

        $clinic = Clinic::create(['name' => 'Test Clinic', 'code' => 'TC', 'address_line_1' => 'A', 'city' => 'C', 'country' => 'C', 'currency' => 'USD', 'timezone' => 'UTC']);
        $user = User::factory()->create(['clinic_id' => $clinic->id]);
        $doctorRole = Role::create(['name' => 'Doctor']);

        // Assign the required permissions to the Role
        $createPermission = \App\Models\Permission::create(['name' => 'create_prescriptions']);
        $viewPermission = \App\Models\Permission::create(['name' => 'view_prescriptions']);
        $viewConsultationPermission = \App\Models\Permission::create(['name' => 'view_consultations']);
        $performConsultationPermission = \App\Models\Permission::create(['name' => 'perform_consultation']);

        $doctorRole->permissions()->attach([
            $createPermission->id,
            $viewPermission->id,
            $viewConsultationPermission->id,
            $performConsultationPermission->id
        ]);

        $user->roles()->attach($doctorRole);
        $this->actingAs($user);

        $this->withSession(['selected_clinic_id' => $clinic->id]);

        $department = Department::create(['clinic_id' => $clinic->id, 'name' => 'General', 'status' => 'active']);
        $doctor = Doctor::create(['user_id' => $user->id, 'specialization' => 'General', 'primary_department_id' => $department->id]);
        $doctor->clinics()->attach($clinic->id);
        $patient = Patient::create(['clinic_id' => $clinic->id, 'name' => 'Patient', 'patient_code' => 'P001', 'date_of_birth' => '1990-01-01', 'gender' => 'male', 'phone' => '1234567890']);

        $appointment = Appointment::create([
            'clinic_id' => $clinic->id,
            'doctor_id' => $doctor->id,
            'patient_id' => $patient->id,
            'department_id' => $department->id,
            'appointment_date' => now()->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '10:30:00',
            'status' => 'confirmed',
            'appointment_type' => 'Walk-in',
            'booking_source' => 'Walk-in',
            'created_by' => $user->id
        ]);

        $visit = Visit::create([
            'clinic_id' => $clinic->id,
            'appointment_id' => $appointment->id,
            'check_in_time' => now(),
            'visit_status' => 'in_progress'
        ]);

        $consultation = Consultation::create(['clinic_id' => $clinic->id, 'visit_id' => $visit->id, 'doctor_id' => $doctor->id, 'patient_id' => $patient->id, 'status' => 'in_progress']);

        // Also create a medicine for the view
        \App\Models\Medicine::create(['name' => 'Paracetamol', 'price' => 10, 'status' => 'active']);

        $response = $this->get(route('clinical.prescriptions.create.withConsultation', $consultation));

        $response->assertStatus(200);
        $expectedAction = route('clinical.prescriptions.store', ['consultation' => $consultation->id]);
        // The action might be escaped in HTML, so we check if it contains the URL
        $response->assertSee($expectedAction, false);
    }
}
