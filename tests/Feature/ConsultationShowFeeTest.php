<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\Consultation;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\Role;
use App\Models\User;
use App\Models\Visit;
use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConsultationShowFeeTest extends TestCase
{
    use RefreshDatabase;

    protected Clinic $clinic;
    protected User $doctorUser;
    protected Doctor $doctor;
    protected Patient $patient;
    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->clinic = Clinic::create([
            'name' => 'Test Clinic',
            'code' => 'TC001',
            'address_line_1' => '123 St',
            'city' => 'City',
            'country' => 'Country',
            'timezone' => 'UTC',
            'currency' => 'USD'
        ]);

        $this->admin = User::factory()->create(['clinic_id' => $this->clinic->id]);
        Role::firstOrCreate(['name' => 'Super Admin', 'description' => 'System Owner']);
        $this->admin->assignRole('Super Admin');

        $department = new Department();
        $department->forceFill([
            'clinic_id' => $this->clinic->id,
            'name' => 'Cardiology',
            'status' => 'active'
        ])->save();

        $this->doctorUser = User::factory()->create(['clinic_id' => $this->clinic->id]);
        $this->doctor = Doctor::create([
            'user_id' => $this->doctorUser->id,
            'primary_department_id' => $department->id,
            'specialization' => 'Heart',
            'consultation_fee' => 100.00,
            'follow_up_fee' => 50.00,
            'status' => 'active'
        ]);
        $this->doctor->clinics()->attach($this->clinic->id);

        $this->patient = new Patient();
        $this->patient->forceFill([
            'clinic_id' => $this->clinic->id,
            'patient_code' => 'P001',
            'name' => 'John Doe',
            'status' => 'active'
        ])->save();
    }

    public function test_show_displays_initial_consultation_fee()
    {
        $appointment = new Appointment();
        $appointment->forceFill([
            'clinic_id' => $this->clinic->id,
            'doctor_id' => $this->doctor->id,
            'patient_id' => $this->patient->id,
            'department_id' => $this->doctor->primary_department_id,
            'appointment_date' => now()->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '10:30:00',
            'appointment_type' => 'in_person',
            'booking_source' => 'reception',
            'status' => 'confirmed',
            'created_by' => $this->admin->id,
        ])->save();

        $visit = new Visit();
        $visit->forceFill([
            'clinic_id' => $this->clinic->id,
            'appointment_id' => $appointment->id,
            'visit_status' => 'in_progress',
        ])->save();

        $consultation = Consultation::create([
            'visit_id' => $visit->id,
            'doctor_id' => $this->doctor->id,
            'patient_id' => $this->patient->id,
            'diagnosis' => 'N/A',
        ]);

        $response = $this->actingAs($this->admin)->get(route('clinical.consultations.show', $consultation));
        $response->assertStatus(200);
        $response->assertSee('Consultation Fee');
        $response->assertSee('100.00 BDT');
        $response->assertSee('Initial');
    }

    public function test_show_displays_follow_up_fee_when_prior_completed_visit_exists()
    {
        // Prior completed appointment with the same doctor and patient
        $prior = new Appointment();
        $prior->forceFill([
            'clinic_id' => $this->clinic->id,
            'doctor_id' => $this->doctor->id,
            'patient_id' => $this->patient->id,
            'department_id' => $this->doctor->primary_department_id,
            'appointment_date' => now()->subDay()->toDateString(),
            'start_time' => '09:00:00',
            'end_time' => '09:30:00',
            'appointment_type' => 'in_person',
            'booking_source' => 'reception',
            'status' => 'completed',
            'created_by' => $this->admin->id,
        ])->save();

        $appointment = new Appointment();
        $appointment->forceFill([
            'clinic_id' => $this->clinic->id,
            'doctor_id' => $this->doctor->id,
            'patient_id' => $this->patient->id,
            'department_id' => $this->doctor->primary_department_id,
            'appointment_date' => now()->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '10:30:00',
            'appointment_type' => 'in_person',
            'booking_source' => 'reception',
            'status' => 'confirmed',
            'created_by' => $this->admin->id,
        ])->save();

        $visit = new Visit();
        $visit->forceFill([
            'clinic_id' => $this->clinic->id,
            'appointment_id' => $appointment->id,
            'visit_status' => 'in_progress',
        ])->save();

        $consultation = Consultation::create([
            'visit_id' => $visit->id,
            'doctor_id' => $this->doctor->id,
            'patient_id' => $this->patient->id,
            'diagnosis' => 'N/A',
        ]);

        $response = $this->actingAs($this->admin)->get(route('clinical.consultations.show', $consultation));
        $response->assertStatus(200);
        $response->assertSee('50.00 BDT');
        $response->assertSee('Follow-up');
        $response->assertSee('Discount');
    }
}
