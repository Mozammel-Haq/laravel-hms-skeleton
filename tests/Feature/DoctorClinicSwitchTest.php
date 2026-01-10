<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\User;
use App\Models\Role;
use App\Models\Appointment;
use App\Models\Patient;
use App\Support\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\Permission;

class DoctorClinicSwitchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Reset TenantContext
        TenantContext::setClinicId(null);

        // Ensure roles exist
        if (!Role::where('name', 'Doctor')->exists()) {
            $role = Role::create(['name' => 'Doctor']);
        } else {
            $role = Role::where('name', 'Doctor')->first();
        }

        // Ensure permissions exist and are assigned
        $permissions = ['create_appointments', 'view_appointments'];
        foreach ($permissions as $permName) {
            if (!Permission::where('name', $permName)->exists()) {
                $perm = Permission::create(['name' => $permName]);
                $role->permissions()->attach($perm->id);
            } else {
                $perm = Permission::where('name', $permName)->first();
                if (!$role->permissions()->where('permissions.id', $perm->id)->exists()) {
                    $role->permissions()->attach($perm->id);
                }
            }
        }
    }

    private function createClinic(array $attributes = [])
    {
        return Clinic::create(array_merge([
            'name' => 'Test Clinic',
            'code' => 'TC' . rand(100, 999),
            'address_line_1' => '123 Test St',
            'city' => 'Test City',
            'country' => 'Test Country',
            'timezone' => 'UTC',
            'currency' => 'USD',
        ], $attributes));
    }

    private function createDoctor($user, $clinicId)
    {
        Department::unguard();
        $dept = Department::create([
            'name' => 'General',
            'clinic_id' => $clinicId,
            'description' => 'Test Dept'
        ]);
        Department::reguard();

        return Doctor::create([
            'user_id' => $user->id,
            'clinic_id' => $clinicId,
            'primary_department_id' => $dept->id,
            'specialization' => 'General',
            'license_number' => 'DOC' . rand(100, 999),
            'joining_date' => now(),
        ]);
    }

    public function test_doctor_can_switch_to_assigned_clinic()
    {
        $clinicA = $this->createClinic(['name' => 'Clinic A']);
        $clinicB = $this->createClinic(['name' => 'Clinic B']);

        $user = User::factory()->create(['clinic_id' => $clinicA->id]);
        $user->assignRole('Doctor');

        $doctor = $this->createDoctor($user, $clinicA->id);

        // Assign doctor to both clinics
        $doctor->clinics()->attach([$clinicA->id, $clinicB->id]);

        $response = $this->actingAs($user)
            ->get(route('doctor.switch-clinic', $clinicB->id));

        $response->assertRedirect();
        $this->assertEquals($clinicB->id, session('selected_clinic_id'));
    }

    public function test_doctor_cannot_switch_to_unassigned_clinic()
    {
        $clinicA = $this->createClinic(['name' => 'Clinic A']);
        $clinicC = $this->createClinic(['name' => 'Clinic C']);

        $user = User::factory()->create(['clinic_id' => $clinicA->id]);
        $user->assignRole('Doctor');

        $doctor = $this->createDoctor($user, $clinicA->id);

        // Doctor only in Clinic A (by default via create but let's be explicit if needed, currently create only sets main clinic_id)
        $doctor->clinics()->attach($clinicA->id);

        $response = $this->actingAs($user)
            ->get(route('doctor.switch-clinic', $clinicC->id));

        $response->assertStatus(403);
    }

    public function test_doctor_data_is_scoped_after_switch()
    {
        $clinicA = $this->createClinic(['name' => 'Clinic A']);
        $clinicB = $this->createClinic(['name' => 'Clinic B']);

        $user = User::factory()->create(['clinic_id' => $clinicA->id]);
        $user->assignRole('Doctor');

        $doctor = $this->createDoctor($user, $clinicA->id);

        $doctor->clinics()->attach([$clinicA->id, $clinicB->id]);

        // Create Department for Clinic B
        Department::unguard();
        $deptB = Department::create([
            'name' => 'General B',
            'clinic_id' => $clinicB->id,
            'description' => 'Test Dept B'
        ]);
        Department::reguard();

        // Get Dept A
        $deptA = $doctor->department;

        // Create Patients
        Patient::unguard();
        $patientA = Patient::create(['clinic_id' => $clinicA->id, 'name' => 'Patient A', 'patient_code' => 'PA']);
        $patientB = Patient::create(['clinic_id' => $clinicB->id, 'name' => 'Patient B', 'patient_code' => 'PB']);
        Patient::reguard();

        // Create Appointments
        Appointment::unguard();
        $apptA = Appointment::create([
            'clinic_id' => $clinicA->id,
            'doctor_id' => $doctor->id,
            'patient_id' => $patientA->id,
            'department_id' => $deptA->id,
            'appointment_date' => now(),
            'start_time' => '10:00:00',
            'end_time' => '10:30:00',
            'appointment_type' => 'in_person',
            'booking_source' => 'reception',
            'created_by' => $user->id,
            'status' => 'confirmed'
        ]);
        $apptB = Appointment::create([
            'clinic_id' => $clinicB->id,
            'doctor_id' => $doctor->id,
            'patient_id' => $patientB->id,
            'department_id' => $deptB->id,
            'appointment_date' => now(),
            'start_time' => '10:00:00',
            'end_time' => '10:30:00',
            'appointment_type' => 'in_person',
            'booking_source' => 'reception',
            'created_by' => $user->id,
            'status' => 'confirmed'
        ]);
        Appointment::reguard();

        // 1. Initial State (Simulate Clinic A context)
        TenantContext::setClinicId($clinicA->id);

        // Verify we only see Appt A
        $this->assertEquals(1, Appointment::count());
        $this->assertEquals($apptA->id, Appointment::first()->id);

        // 2. Switch to Clinic B (Simulate Clinic B context)
        TenantContext::setClinicId($clinicB->id);

        $this->assertEquals(1, Appointment::count());
        $this->assertEquals($apptB->id, Appointment::first()->id);
    }

    public function test_appointment_creation_respects_switched_context()
    {
        $clinicA = $this->createClinic(['name' => 'Clinic A']);
        $clinicB = $this->createClinic(['name' => 'Clinic B']);

        $user = User::factory()->create(['clinic_id' => $clinicA->id]);
        $user->assignRole('Doctor');
        $user->refresh(); // Load roles/permissions
        $doctor = $this->createDoctor($user, $clinicA->id);
        $doctor->clinics()->attach([$clinicA->id, $clinicB->id]);

        // Create Patient in Clinic B
        TenantContext::setClinicId($clinicB->id);
        Patient::unguard();
        $patientB = Patient::create(['clinic_id' => $clinicB->id, 'name' => 'Patient B', 'patient_code' => 'PB']);
        Patient::reguard();
        TenantContext::setClinicId(null); // Reset

        // Switch to Clinic B
        $this->actingAs($user)->get(route('doctor.switch-clinic', $clinicB->id));

        // Create Appointment via Controller
        $response = $this->actingAs($user)
            ->post(route('appointments.store'), [
                'doctor_id' => $doctor->id,
                'patient_id' => $patientB->id,
                'appointment_date' => now()->addDay()->format('Y-m-d'),
                'start_time' => '10:00',
                'appointment_type' => 'in_person',
                'booking_source' => 'reception',
                'reason_for_visit' => 'Test Appointment'
            ]);

        $response->assertRedirect();

        $latestAppt = Appointment::withoutGlobalScope('clinic')->latest('id')->first();
        $this->assertEquals($clinicB->id, $latestAppt->clinic_id, 'Appointment should be created in switched clinic context');
    }
}
