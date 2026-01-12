<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\Consultation;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use App\Models\Visit;
use App\Models\Medicine;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConsultationFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        \Illuminate\Database\Eloquent\Model::unguard();
    }

    public function test_consultation_lifecycle()
    {
        // Setup data
        $clinic = Clinic::create([
            'name' => 'Test Clinic',
            'code' => 'TEST01',
            'address_line_1' => 'Test Address',
            'city' => 'Test City',
            'country' => 'Test Country',
            'timezone' => 'UTC',
            'currency' => 'USD',
            'phone' => '123',
            'email' => 'test@clinic.com'
        ]);

        // Create Role and Permissions
        // Using Spatie Role/Permission models directly if they are installed, or App\Models\Role if the project uses a custom one.
        // The project has App\Models\Role and App\Models\Permission based on file list.
        $role = \App\Models\Role::create(['name' => 'Doctor', 'description' => 'Doctor Role']);
        $permissionCreate = \App\Models\Permission::create(['name' => 'create_prescriptions']);
        $permissionCreateConsultation = \App\Models\Permission::create(['name' => 'create']);
        $permissionPerformConsultation = \App\Models\Permission::create(['name' => 'perform_consultation']);

        $role->permissions()->attach([$permissionCreate->id, $permissionCreateConsultation->id, $permissionPerformConsultation->id]);

        // Let's create a user and assign role
        $user = User::factory()->create([
            'clinic_id' => $clinic->id,
            'name' => 'Dr. Test',
            'email' => 'doctor@test.com',
            'password' => bcrypt('password')
        ]);

        // Manually attach role if needed, or if User model has assignRole.
        // The project seems to use custom role system (user_role table).
        $user->roles()->attach($role->id);

        $department = \App\Models\Department::create([
            'name' => 'General',
            'clinic_id' => $clinic->id,
            'description' => 'General Department'
        ]);

        $doctor = Doctor::create([
            'user_id' => $user->id,
            'specialization' => 'General',
            'license_number' => '123',
            'primary_department_id' => $department->id
        ]);

        $doctor->clinics()->attach($clinic->id);

        $patient = Patient::create([
            'name' => 'Test Patient',
            'clinic_id' => $clinic->id,
            'patient_code' => 'PAT001',
            'gender' => 'Male',
            'date_of_birth' => '2000-01-01',
            'phone' => '123'
        ]);

        $medicine = Medicine::create(['name' => 'Paracetamol', 'price' => 10, 'status' => 'active']);

        // 1. Create Appointment
        $appointment = Appointment::create([
            'clinic_id' => $clinic->id,
            'doctor_id' => $doctor->id,
            'patient_id' => $patient->id,
            'department_id' => $department->id,
            'appointment_date' => now()->addDay()->toDateString(),
            'start_time' => '09:00:00',
            'end_time' => '09:30:00',
            'appointment_type' => 'in_person',
            'booking_source' => 'reception',
            'status' => 'confirmed',
            'created_by' => $user->id,
        ]);

        $this->actingAs($user);

        // 2. Start Consultation (Simulate store request)
        // We need to bypass Gate::authorize if possible or ensure policy passes.
        // Assuming policies are set up correctly for 'Doctor' role.

        $response = $this->post(route('clinical.consultations.store', $appointment), [
            'doctor_notes' => 'Initial notes',
            'diagnosis' => 'Flu',
            'prescription_items' => []
        ]);

        if ($response->status() !== 302) {
            // dump($response->content());
            echo "Status: " . $response->status();
        }
        $response->assertRedirect();

        $appointment->refresh();
        $this->assertEquals('completed', $appointment->status);

        $visit = Visit::where('appointment_id', $appointment->id)->first();
        $this->assertNotNull($visit);
        $this->assertEquals('completed', $visit->visit_status);

        $consultation = Consultation::where('visit_id', $visit->id)->first();
        $this->assertNotNull($consultation);
        $this->assertEquals('completed', $consultation->status);

        // 3. Try to add prescription to completed consultation
        $response = $this->post(route('clinical.prescriptions.store', $consultation), [
            'items' => [['medicine_id' => $medicine->id, 'dosage' => '1', 'frequency' => '1', 'duration_days' => 1]],
        ]);

        // Should redirect back with error
        $response->assertSessionHas('error', 'Consultation is already completed.');

        // 4. Create NEW Appointment for same patient
        $appointment2 = Appointment::create([
            'clinic_id' => $clinic->id,
            'doctor_id' => $doctor->id,
            'patient_id' => $patient->id,
            'department_id' => $department->id,
            'appointment_date' => now()->addDays(2)->toDateString(),
            'start_time' => '09:00:00',
            'end_time' => '09:30:00',
            'appointment_type' => 'in_person',
            'booking_source' => 'reception',
            'status' => 'confirmed',
            'created_by' => $user->id,
        ]);

        // 5. Start Consultation for new appointment
        $response = $this->post(route('clinical.consultations.store', $appointment2), [
            'doctor_notes' => 'Follow up notes',
            'diagnosis' => 'Recovered',
        ]);

        $visit2 = Visit::where('appointment_id', $appointment2->id)->first();
        $this->assertNotNull($visit2);
        $this->assertNotEquals($visit->id, $visit2->id); // Different visits

        $consultation2 = Consultation::where('visit_id', $visit2->id)->first();
        $this->assertNotNull($consultation2);
        $this->assertNotEquals($consultation->id, $consultation2->id); // Different consultations

        // Ensure consultation2 is linked to appointment2
        $this->assertEquals($visit2->id, $consultation2->visit_id);
    }
}
