<?php

namespace Tests\Feature;

use App\Models\Admission;
use App\Models\Bed;
use App\Models\Clinic;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Room;
use App\Models\User;
use App\Models\Ward;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IpdAdmissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Model::unguard();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_cannot_readmit_currently_admitted_patient()
    {
        // 1. Setup
        $clinic = Clinic::create([
            'name' => 'Clinic A',
            'code' => 'CA',
            'address_line_1' => 'addr',
            'city' => 'City',
            'country' => 'Country',
            'timezone' => 'UTC',
            'currency' => 'USD',
        ]);

        $receptionist = User::factory()->create(['clinic_id' => $clinic->id]);
        $receptionist->assignRole('Receptionist');
        // dd($receptionist->roles->pluck('name'));

        $department = Department::create([
            'clinic_id' => $clinic->id,
            'name' => 'General Medicine',
        ]);

        $doctorUser = User::factory()->create(['clinic_id' => $clinic->id]);
        $doctorUser->assignRole('Doctor');

        $doctor = Doctor::create([
            'clinic_id' => $clinic->id,
            'user_id' => $doctorUser->id,
            'primary_department_id' => $department->id,
            'specialization' => 'General Physician',
            'status' => 'active',
        ]);
        $doctor->clinics()->attach($clinic->id);

        $patient = Patient::create([
            'clinic_id' => $clinic->id,
            'patient_code' => 'P-0001',
            'name' => 'John Doe',
        ]);

        $ward = Ward::create([
            'clinic_id' => $clinic->id,
            'name' => 'Ward A',
            'type' => 'general',
        ]);

        $room = Room::create([
            'ward_id' => $ward->id,
            'room_number' => '101',
            'room_type' => 'General',
            'daily_rate' => 100,
            'clinic_id' => $clinic->id,
        ]);

        $bed1 = Bed::create([
            'clinic_id' => $clinic->id,
            'room_id' => $room->id,
            'bed_number' => '101-A',
            'status' => 'available',
        ]);

        $bed2 = Bed::create([
            'clinic_id' => $clinic->id,
            'room_id' => $room->id,
            'bed_number' => '101-B',
            'status' => 'available',
        ]);

        // 2. First Admission
        $response = $this->actingAs($receptionist)->post(route('ipd.store'), [
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'admission_date' => now()->toDateString(),
            'admission_reason' => 'Fever',
            'bed_id' => $bed1->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('admissions', [
            'patient_id' => $patient->id,
            'status' => 'admitted',
        ]);

        // 3. Attempt Second Admission (Should Fail)
        $response = $this->actingAs($receptionist)->post(route('ipd.store'), [
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'admission_date' => now()->toDateString(),
            'admission_reason' => 'Another Fever',
            'bed_id' => $bed2->id,
        ]);

        $response->assertSessionHasErrors(['patient_id']);

        // Ensure only 1 admission exists
        $this->assertEquals(1, Admission::where('patient_id', $patient->id)->where('status', 'admitted')->count());

        // 4. Discharge the patient
        $admission = Admission::where('patient_id', $patient->id)->first();

        $response = $this->actingAs($receptionist)->post(route('ipd.store-discharge', $admission), [
            'discharge_date' => now()->addDay()->toDateString(),
            'discount' => 0,
            'tax' => 0,
        ]);

        $response->assertRedirect();

        // 5. Attempt Re-admission (Should Succeed)
        $response = $this->actingAs($receptionist)->post(route('ipd.store'), [
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'admission_date' => now()->addDays(2)->toDateString(),
            'admission_reason' => 'Relapse',
            'bed_id' => $bed2->id,
        ]);

        $response->assertRedirect();
        $this->assertEquals(1, Admission::where('patient_id', $patient->id)->where('status', 'admitted')->count());
        $this->assertEquals(2, Admission::where('patient_id', $patient->id)->count());
    }
}
