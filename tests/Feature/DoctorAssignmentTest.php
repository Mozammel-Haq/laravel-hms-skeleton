<?php

namespace Tests\Feature;

use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DoctorAssignmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_superadmin_can_submit_assignment_form()
    {
        \Illuminate\Database\Eloquent\Model::unguard();
        $clinic = Clinic::create(['name' => 'A', 'code' => 'A1', 'address_line_1' => 'A', 'city' => 'C', 'country' => 'C', 'currency' => 'USD', 'timezone' => 'UTC']);
        $user = User::factory()->create(['clinic_id' => $clinic->id]);
        $role = Role::firstOrCreate(['name' => 'Super Admin']);
        $editDoctors = \App\Models\Permission::firstOrCreate(['name' => 'edit_doctors']);
        $role->permissions()->syncWithoutDetaching([$editDoctors->id]);
        $user->roles()->attach($role);
        $this->actingAs($user);
        session(['selected_clinic_id' => $clinic->id]);

        $doctorUser = User::factory()->create(['clinic_id' => $clinic->id]);
        $department = \App\Models\Department::create(['clinic_id' => $clinic->id, 'name' => 'General', 'status' => 'active']);
        $doctor = Doctor::create(['user_id' => $doctorUser->id, 'specialization' => 'General', 'primary_department_id' => $department->id]);
        $doctor->clinics()->attach($clinic->id);

        $clinicB = Clinic::create(['name' => 'B', 'code' => 'B1', 'address_line_1' => 'B', 'city' => 'C', 'country' => 'C', 'currency' => 'USD', 'timezone' => 'UTC']);
        $clinicC = Clinic::create(['name' => 'C', 'code' => 'C1', 'address_line_1' => 'C', 'city' => 'C', 'country' => 'C', 'currency' => 'USD', 'timezone' => 'UTC']);

        $response = $this->post(route('doctors.assignment.update', $doctor->id), [
            'clinic_ids' => [$clinicB->id, $clinicC->id],
        ]);

        $response->assertRedirect(route('doctors.assignment'));
        $this->assertTrue($doctor->clinics()->whereKey($clinicB->id)->exists());
        $this->assertTrue($doctor->clinics()->whereKey($clinicC->id)->exists());
    }

    public function test_clinic_admin_cannot_submit_assignment_form_but_can_access_schedule()
    {
        \Illuminate\Database\Eloquent\Model::unguard();
        $clinic = Clinic::create(['name' => 'A', 'code' => 'A1', 'address_line_1' => 'A', 'city' => 'C', 'country' => 'C', 'currency' => 'USD', 'timezone' => 'UTC']);
        $user = User::factory()->create(['clinic_id' => $clinic->id]);
        $role = Role::firstOrCreate(['name' => 'Clinic Admin']);
        $manageSchedule = \App\Models\Permission::firstOrCreate(['name' => 'manage_doctor_schedule']);
        $editDoctors = \App\Models\Permission::firstOrCreate(['name' => 'edit_doctors']);
        $role->permissions()->syncWithoutDetaching([$manageSchedule->id, $editDoctors->id]);
        $user->roles()->attach($role);
        $this->actingAs($user);
        session(['selected_clinic_id' => $clinic->id]);

        $doctorUser = User::factory()->create(['clinic_id' => $clinic->id]);
        $department = \App\Models\Department::create(['clinic_id' => $clinic->id, 'name' => 'General', 'status' => 'active']);
        $doctor = Doctor::create(['user_id' => $doctorUser->id, 'specialization' => 'General', 'primary_department_id' => $department->id]);
        $doctor->clinics()->attach($clinic->id);

        $responseForbidden = $this->post(route('doctors.assignment.update', $doctor->id), [
            'clinic_ids' => [$clinic->id],
        ]);
        $responseForbidden->assertStatus(403);

        $responseSchedule = $this->get(route('doctors.schedule', $doctor->id));
        $this->assertNotEquals(403, $responseSchedule->status());
    }

    public function test_unauthorized_users_cannot_access_assignment_or_schedule()
    {
        \Illuminate\Database\Eloquent\Model::unguard();
        $clinic = Clinic::create(['name' => 'A', 'code' => 'A1', 'address_line_1' => 'A', 'city' => 'C', 'country' => 'C', 'currency' => 'USD', 'timezone' => 'UTC']);
        $doctorUser = User::factory()->create(['clinic_id' => $clinic->id]);
        $department = \App\Models\Department::create(['clinic_id' => $clinic->id, 'name' => 'General', 'status' => 'active']);
        $doctor = Doctor::create(['user_id' => $doctorUser->id, 'specialization' => 'General', 'primary_department_id' => $department->id]);

        $responseAssignment = $this->get(route('doctors.assignment'));
        $responseAssignment->assertRedirect(); // redirected to login due to auth middleware

        $responseSchedule = $this->get(route('doctors.schedule', $doctor->id));
        $responseSchedule->assertRedirect(); // redirected to login due to auth middleware
    }
}
