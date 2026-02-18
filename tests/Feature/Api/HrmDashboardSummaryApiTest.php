<?php

namespace Tests\Feature\Api;

use App\Models\Clinic;
use App\Models\HrmAttendance;
use App\Models\HrmJobPost;
use App\Models\HrmTrainingCourse;
use App\Models\HrmTrainingSession;
use App\Models\LeaveRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class HrmDashboardSummaryApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_sees_correct_summary_and_attendance_series(): void
    {
        Carbon::setTestNow('2026-02-18 10:00:00');

        $clinic = Clinic::create([
            'name' => 'Test Clinic',
            'code' => 'TC-HRM',
            'registration_number' => 'HRM-123',
            'address_line_1' => '123 HR St',
            'city' => 'City',
            'country' => 'Country',
            'timezone' => 'UTC',
            'currency' => 'USD',
            'status' => 'active',
        ]);

        $manager = User::factory()->create([
            'clinic_id' => $clinic->id,
            'name' => 'HR Manager',
            'email' => 'hr-manager@example.com',
        ]);

        $staffA = User::factory()->create(['clinic_id' => $clinic->id]);
        $staffB = User::factory()->create(['clinic_id' => $clinic->id]);

        $role = Role::firstOrCreate(['name' => 'HR Manager']);

        $perms = [
            'view_hrm_dashboard',
            'view_staff',
            'manage_leaves',
            'view_reports',
        ];

        $permissionIds = [];
        foreach ($perms as $name) {
            $perm = Permission::where('name', $name)->first();
            if (! $perm) {
                $perm = Permission::create(['name' => $name]);
            }
            $permissionIds[] = $perm->id;
        }

        $role->permissions()->sync($permissionIds);
        $manager->roles()->syncWithoutDetaching([$role->id]);
        $manager->refresh();

        $today = Carbon::today();
        $yesterday = $today->copy()->subDay();

        HrmAttendance::create([
            'clinic_id' => $clinic->id,
            'user_id' => $staffA->id,
            'attendance_date' => $today->toDateString(),
            'status' => 'present',
        ]);

        HrmAttendance::create([
            'clinic_id' => $clinic->id,
            'user_id' => $staffB->id,
            'attendance_date' => $yesterday->toDateString(),
            'status' => 'present',
        ]);

        LeaveRequest::create([
            'user_id' => $staffB->id,
            'leave_type' => 'annual',
            'start_date' => $today->copy()->subDay()->toDateString(),
            'end_date' => $today->copy()->addDay()->toDateString(),
            'status' => 'approved',
        ]);

        HrmJobPost::create([
            'clinic_id' => $clinic->id,
            'title' => 'Staff Nurse',
            'employment_type' => 'full_time',
            'location' => 'City',
            'description' => 'Nurse role',
            'requirements' => 'Nursing degree',
            'openings' => 2,
            'status' => 'open',
            'posted_at' => $today->copy()->subDays(3),
            'closes_at' => $today->copy()->addDays(10),
        ]);

        $course = HrmTrainingCourse::create([
            'clinic_id' => $clinic->id,
            'title' => 'Onboarding 101',
            'code' => 'ONB-101',
            'category' => 'General',
            'target_role' => 'Staff',
            'mode' => 'classroom',
            'duration_hours' => 2,
            'description' => 'Intro training',
            'status' => 'active',
        ]);

        HrmTrainingSession::create([
            'clinic_id' => $clinic->id,
            'course_id' => $course->id,
            'facilitator_user_id' => $manager->id,
            'start_date' => $today->copy()->addDays(2)->toDateString(),
            'end_date' => $today->copy()->addDays(2)->toDateString(),
            'location' => 'Room A',
            'capacity' => 20,
            'status' => 'scheduled',
        ]);

        Sanctum::actingAs($manager, ['*']);

        $response = $this->withHeader('X-Clinic-ID', $clinic->id)
            ->getJson('/api/v2/hrm-summary');

        $response->assertStatus(200);

        $response->assertJsonPath('data.role', 'manager');

        $response->assertJsonPath('data.manager.total_staff', 3);
        $response->assertJsonPath('data.manager.present_today', 1);
        $response->assertJsonPath('data.manager.on_leave_today', 1);
        $response->assertJsonPath('data.manager.open_positions', 1);
        $response->assertJsonPath('data.manager.active_trainings', 1);

        $series = $response->json('data.attendance_timeseries');

        $this->assertIsArray($series);
        $this->assertCount(7, $series);

        foreach ($series as $day) {
            $this->assertArrayHasKey('date', $day);
            $this->assertArrayHasKey('present', $day);
            $this->assertArrayHasKey('total_staff', $day);
        }
    }
}
