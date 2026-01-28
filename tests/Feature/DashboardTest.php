<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Role;
use App\Models\User;
use App\Support\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        if (!Role::where('name', 'Clinic Admin')->exists()) {
            Role::create(['name' => 'Clinic Admin', 'description' => 'Clinic Administrator']);
        }
    }

    /** @test */
    public function clinic_admin_dashboard_loads_without_sql_errors()
    {
        // 1. Create Clinic
        $clinic = Clinic::create([
            'name' => 'Test Clinic',
            'code' => 'TC001',
            'status' => 'active',
            'city' => 'Test City',
            'country' => 'Test Country',
            'timezone' => 'UTC',
            'currency' => 'USD',
            'address_line_1' => '123 St',
        ]);

        // Set tenant context
        TenantContext::setClinicId($clinic->id);

        // 2. Create Clinic Admin User
        $user = User::factory()->create([
            'clinic_id' => $clinic->id,
            'name' => 'Clinic Admin',
            'email' => 'admin@clinic.com',
        ]);
        $user->assignRole('Clinic Admin');

        // 3. Create Doctor
        $department = Department::create(['name' => 'General', 'clinic_id' => $clinic->id]);
        $doctorUser = User::factory()->create(['clinic_id' => $clinic->id]);
        $doctor = Doctor::create([
            'user_id' => $doctorUser->id,
            'primary_department_id' => $department->id,
            'license_number' => 'DOC001',
            'specialization' => ['General'],
            'joining_date' => now(),
        ]);
        $clinic->doctors()->attach($doctor->id);

        // 4. Create Patient (Global but linked)
        $patient = Patient::create([
            'patient_code' => 'P-001',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
            'blood_group' => 'O+',
            'address' => '123 St',
        ]);
        $clinic->patients()->attach($patient->id);

        // 5. Create Appointment
        $appointment = Appointment::create([
            'clinic_id' => $clinic->id,
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'department_id' => $department->id,
            'appointment_date' => now()->format('Y-m-d'),
            'start_time' => '10:00:00',
            'end_time' => '10:15:00',
            'status' => 'completed',
            'appointment_type' => 'New Visit',
            'booking_source' => 'walk-in',
        ]);

        // 6. Create Invoice
        $invoice = Invoice::create([
            'clinic_id' => $clinic->id,
            'patient_id' => $patient->id,
            'appointment_id' => $appointment->id,
            'invoice_number' => 'INV-001',
            'subtotal' => 100.00,
            'total_amount' => 100.00,
            'paid_amount' => 100.00,
            'due_amount' => 0,
            'status' => 'paid',
            'issue_date' => now(),
            'due_date' => now(),
        ]);

        // 7. Visit Dashboard
        $response = $this->actingAs($user)->get(route('dashboard'));

        // 8. Assert Success
        $response->assertStatus(200);
        $response->assertViewIs('dashboards.clinic_admin');

        // Assert view has data
        $response->assertViewHas('stats');
        $response->assertViewHas('topPatients');
    }
}
