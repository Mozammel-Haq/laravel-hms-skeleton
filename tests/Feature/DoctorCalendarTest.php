<?php

namespace Tests\Feature;

use App\Models\Clinic;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\DoctorScheduleException;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DoctorCalendarTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        \Illuminate\Database\Eloquent\Model::unguard();
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    }

    public function test_can_fetch_calendar_events()
    {
        // 1. Setup Clinic, User, Department and Doctor
        $clinic = Clinic::create([
            'name' => 'Main Clinic',
            'code' => 'MC001',
            'address_line_1' => '123 Main St',
            'city' => 'Test City',
            'country' => 'Testland',
            'timezone' => 'UTC',
            'currency' => 'USD',
            'phone' => '1234567890',
            'email' => 'clinic@test.com'
        ]);
        $user = User::factory()->create(['clinic_id' => $clinic->id]);
        $user->assignRole('Clinic Admin');
        $department = Department::create(['name' => 'General Medicine', 'clinic_id' => $clinic->id]);
        $doctor = Doctor::create([
            'user_id' => $user->id,
            'specialization' => 'General',
            'primary_department_id' => $department->id
        ]);
        $doctor->clinics()->attach($clinic->id);

        // 2. Weekly Schedule (Mondays)
        DoctorSchedule::create([
            'doctor_id' => $doctor->id,
            'clinic_id' => $clinic->id,
            'department_id' => $department->id,
            'day_of_week' => 1, // Monday
            'start_time' => '09:00:00',
            'end_time' => '17:00:00',
            'slot_duration_minutes' => 15,
        ]);

        // 3. Specific Date Schedule
        $specificDate = Carbon::now()->addMonth()->startOfMonth()->addDays(2); // 3rd of next month
        DoctorSchedule::create([
            'doctor_id' => $doctor->id,
            'clinic_id' => $clinic->id,
            'department_id' => $department->id,
            'schedule_date' => $specificDate->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '14:00:00',
            'slot_duration_minutes' => 15,
        ]);

        // 4. Exception (overlapping a Monday)
        $nextMonthStart = Carbon::now()->addMonth()->startOfMonth();
        $firstMonday = $nextMonthStart->copy()->next(Carbon::MONDAY);

        DoctorScheduleException::create([
            'doctor_id' => $doctor->id,
            'clinic_id' => $clinic->id,
            'start_date' => $firstMonday->toDateString(),
            'end_date' => $firstMonday->toDateString(),
            'reason' => 'Holiday',
        ]);

        // 5. Authenticate
        $this->actingAs($user);

        // 6. Request Calendar Data
        $response = $this->getJson(route('doctors.schedules.events', [
            'year' => $nextMonthStart->year,
            'month' => $nextMonthStart->month,
        ]));

        $response->assertStatus(200);
        $data = $response->json();

        // 7. Verify Specific Date is present
        $this->assertArrayHasKey($specificDate->toDateString(), $data);
        $this->assertEquals('specific', $data[$specificDate->toDateString()][0]['type']);

        // 8. Verify Exception (First Monday should NOT be present)
        $this->assertArrayNotHasKey($firstMonday->toDateString(), $data);

        // 9. Verify Regular Weekly Schedule (Second Monday should be present)
        $secondMonday = $firstMonday->copy()->addWeek();
        // Only if it's still in the same month
        if ($secondMonday->month == $nextMonthStart->month) {
            $this->assertArrayHasKey($secondMonday->toDateString(), $data);
            $this->assertEquals('weekly', $data[$secondMonday->toDateString()][0]['type']);
        }
    }
}
