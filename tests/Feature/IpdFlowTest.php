<?php

namespace Tests\Feature;

use App\Models\Admission;
use App\Models\Bed;
use App\Models\Clinic;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Room;
use App\Models\User;
use App\Models\Ward;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IpdFlowTest extends TestCase
{
    use RefreshDatabase;

    protected $clinic;

    protected $receptionist;

    protected $doctor;

    protected $patient;

    protected $bed;

    protected function setUp(): void
    {
        parent::setUp();
        Model::unguard();

        // 1. Setup Clinic
        $this->clinic = Clinic::create([
            'name' => 'Test Clinic',
            'code' => 'TC',
            'address_line_1' => '123 Test St',
            'city' => 'Test City',
            'country' => 'Test Country',
            'timezone' => 'UTC',
            'currency' => 'USD',
        ]);

        // 2. Setup Roles and Permissions
        $role = Role::create(['name' => 'Receptionist']);
        $perms = [
            'view_ipd', 'create_admissions', 'edit_admissions', 'delete_admissions',
            'view_patients', 'create_patients', 'view_doctors',
        ];
        foreach ($perms as $perm) {
            $p = Permission::firstOrCreate(['name' => $perm]);
            $role->givePermissionTo($p);
        }

        $this->receptionist = User::factory()->create(['clinic_id' => $this->clinic->id]);
        $this->receptionist->assignRole($role);

        // 3. Setup Doctor
        $doctorUser = User::factory()->create(['clinic_id' => $this->clinic->id]);
        $doctorRole = Role::create(['name' => 'Doctor']);
        $doctorUser->assignRole($doctorRole);

        $department = Department::create([
            'clinic_id' => $this->clinic->id,
            'name' => 'General Medicine',
            'status' => 'active',
        ]);

        $this->doctor = Doctor::create([
            'user_id' => $doctorUser->id,
            'clinic_id' => $this->clinic->id,
            'primary_department_id' => $department->id,
            'specialization' => 'General',
            'license_number' => 'DOC123',
            'status' => 'active',
        ]);

        // 4. Setup Patient
        $this->patient = Patient::create([
            'clinic_id' => $this->clinic->id,
            'name' => 'John Doe',
            'patient_code' => 'P001',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
            'phone' => '1234567890',
            'address' => '123 Test St',
        ]);

        // 5. Setup Bed
        $ward = Ward::create(['clinic_id' => $this->clinic->id, 'name' => 'General Ward', 'type' => 'general', 'status' => 'active']);
        $room = Room::create(['clinic_id' => $this->clinic->id, 'ward_id' => $ward->id, 'room_number' => '101', 'room_type' => 'general', 'status' => 'active', 'daily_rate' => 100]);
        $this->bed = Bed::create(['clinic_id' => $this->clinic->id, 'room_id' => $room->id, 'bed_number' => '101-A', 'status' => 'available']);
    }

    public function test_complete_ipd_flow_admission_bed_assignment_discharge()
    {
        // 1. Admit Patient
        $response = $this->actingAs($this->receptionist)->post(route('ipd.store'), [
            'patient_id' => $this->patient->id,
            'doctor_id' => $this->doctor->id,
            'admission_date' => now()->toDateString(),
            'admission_reason' => 'Severe Fever',
            'bed_id' => $this->bed->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('admissions', [
            'patient_id' => $this->patient->id,
            'status' => 'admitted',
            'current_bed_id' => $this->bed->id,
        ]);

        $admission = Admission::where('patient_id', $this->patient->id)->first();

        // Verify Bed Assignment
        $this->assertDatabaseHas('bed_assignments', [
            'admission_id' => $admission->id,
            'bed_id' => $this->bed->id,
            'released_at' => null,
        ]);

        // Verify Bed Status Updated
        $this->assertEquals('occupied', $this->bed->fresh()->status);

        // 2. Discharge Patient
        $dischargeReason = 'Patient recovered';
        $response = $this->actingAs($this->receptionist)->post(route('ipd.store-discharge', $admission), [
            'discharge_date' => now()->toDateString(),
            'discharge_reason' => $dischargeReason, // This was the missing feature
            'discount' => 0,
            'tax' => 0,
        ]);

        $response->assertRedirect();

        // 3. Verify Discharge
        $admission->refresh();
        $this->assertEquals('discharged', $admission->status);
        $this->assertEquals($dischargeReason, $admission->discharge_reason); // Verify the fix
        $this->assertNotNull($admission->discharge_date);
        $this->assertNull($admission->current_bed_id);

        // Verify Bed Assignment Released
        $this->assertDatabaseMissing('bed_assignments', [
            'admission_id' => $admission->id,
            'released_at' => null,
        ]);

        // Verify Bed Status Available
        $this->assertEquals('available', $this->bed->fresh()->status);
    }
}
