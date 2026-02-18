<?php

namespace Database\Seeders;

use App\Models\Clinic;
use App\Models\HrmCompliancePolicy;
use App\Models\HrmHoliday;
use App\Models\HrmJobPost;
use App\Models\HrmLeaveType;
use App\Models\HrmTrainingCourse;
use App\Models\HrmTrainingSession;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class HrmDemoSeeder extends Seeder
{
    public function run(): void
    {
        $clinic = Clinic::first();

        if (! $clinic) {
            return;
        }

        $clinicId = $clinic->id;

        $leaveTypes = [
            ['name' => 'Annual Leave', 'code' => 'AL', 'default_days' => 20, 'carry_forward' => true],
            ['name' => 'Sick Leave', 'code' => 'SL', 'default_days' => 10, 'carry_forward' => false],
            ['name' => 'Casual Leave', 'code' => 'CL', 'default_days' => 7, 'carry_forward' => false],
        ];

        foreach ($leaveTypes as $type) {
            HrmLeaveType::firstOrCreate(
                [
                    'clinic_id' => $clinicId,
                    'code' => $type['code'],
                ],
                [
                    'clinic_id' => $clinicId,
                    'name' => $type['name'],
                    'code' => $type['code'],
                    'default_days' => $type['default_days'],
                    'carry_forward' => $type['carry_forward'],
                    'status' => 'active',
                ]
            );
        }

        $year = (int) date('Y');

        $holidays = [
            ['date' => Carbon::create($year, 1, 1), 'name' => 'New Year\'s Day', 'type' => 'public'],
            ['date' => Carbon::create($year, 3, 26), 'name' => 'Independence Day', 'type' => 'public'],
            ['date' => Carbon::create($year, 12, 16), 'name' => 'Victory Day', 'type' => 'public'],
        ];

        foreach ($holidays as $holiday) {
            HrmHoliday::firstOrCreate(
                [
                    'clinic_id' => $clinicId,
                    'date' => $holiday['date']->toDateString(),
                ],
                [
                    'clinic_id' => $clinicId,
                    'date' => $holiday['date']->toDateString(),
                    'name' => $holiday['name'],
                    'type' => $holiday['type'],
                    'is_full_day' => true,
                    'status' => 'active',
                ]
            );
        }

        $jobPosts = [
            [
                'title' => 'Staff Nurse',
                'employment_type' => 'full_time',
                'location' => 'Dhaka',
                'openings' => 3,
            ],
            [
                'title' => 'Medical Officer',
                'employment_type' => 'full_time',
                'location' => 'Dhaka',
                'openings' => 2,
            ],
            [
                'title' => 'HR Executive',
                'employment_type' => 'full_time',
                'location' => 'Dhaka',
                'openings' => 1,
            ],
        ];

        foreach ($jobPosts as $post) {
            HrmJobPost::firstOrCreate(
                [
                    'clinic_id' => $clinicId,
                    'title' => $post['title'],
                ],
                [
                    'clinic_id' => $clinicId,
                    'title' => $post['title'],
                    'department_id' => null,
                    'employment_type' => $post['employment_type'],
                    'location' => $post['location'],
                    'description' => $post['title'].' position at '.$clinic->name,
                    'requirements' => 'Relevant experience in hospital or clinic setting.',
                    'openings' => $post['openings'],
                    'status' => 'open',
                    'posted_at' => Carbon::now()->subDays(7),
                    'closes_at' => Carbon::now()->addDays(23),
                ]
            );
        }

        $courses = [
            [
                'title' => 'Infection Control Basics',
                'code' => 'IC-101',
                'category' => 'Compliance',
                'target_role' => 'Nurse',
                'mode' => 'classroom',
                'duration_hours' => 4,
            ],
            [
                'title' => 'Patient Communication Skills',
                'code' => 'PC-201',
                'category' => 'Soft Skills',
                'target_role' => 'Doctor',
                'mode' => 'online',
                'duration_hours' => 3,
            ],
        ];

        $courseIds = [];

        foreach ($courses as $course) {
            $model = HrmTrainingCourse::firstOrCreate(
                [
                    'clinic_id' => $clinicId,
                    'code' => $course['code'],
                ],
                [
                    'clinic_id' => $clinicId,
                    'title' => $course['title'],
                    'code' => $course['code'],
                    'category' => $course['category'],
                    'target_role' => $course['target_role'],
                    'mode' => $course['mode'],
                    'duration_hours' => $course['duration_hours'],
                    'description' => $course['title'].' for clinical staff.',
                    'status' => 'active',
                ]
            );

            $courseIds[$course['code']] = $model->id;
        }

        $today = Carbon::today();

        $sessions = [
            [
                'course_code' => 'IC-101',
                'start_date' => $today->copy()->addDays(3),
                'end_date' => $today->copy()->addDays(3),
                'location' => 'Training Room A',
                'capacity' => 20,
                'status' => 'scheduled',
            ],
            [
                'course_code' => 'PC-201',
                'start_date' => $today->copy()->addDays(10),
                'end_date' => $today->copy()->addDays(10),
                'location' => 'Conference Hall',
                'capacity' => 30,
                'status' => 'scheduled',
            ],
        ];

        foreach ($sessions as $session) {
            $courseId = $courseIds[$session['course_code']] ?? null;

            if (! $courseId) {
                continue;
            }

            HrmTrainingSession::firstOrCreate(
                [
                    'clinic_id' => $clinicId,
                    'course_id' => $courseId,
                    'start_date' => $session['start_date']->toDateString(),
                ],
                [
                    'clinic_id' => $clinicId,
                    'course_id' => $courseId,
                    'facilitator_user_id' => null,
                    'start_date' => $session['start_date']->toDateString(),
                    'end_date' => $session['end_date']->toDateString(),
                    'location' => $session['location'],
                    'capacity' => $session['capacity'],
                    'status' => $session['status'],
                    'notes' => null,
                ]
            );
        }

        $policies = [
            [
                'title' => 'Code of Conduct',
                'category' => 'General',
            ],
            [
                'title' => 'Workplace Safety',
                'category' => 'Safety',
            ],
            [
                'title' => 'Patient Data Privacy',
                'category' => 'Compliance',
            ],
        ];

        foreach ($policies as $policy) {
            HrmCompliancePolicy::firstOrCreate(
                [
                    'clinic_id' => $clinicId,
                    'title' => $policy['title'],
                ],
                [
                    'clinic_id' => $clinicId,
                    'title' => $policy['title'],
                    'category' => $policy['category'],
                    'description' => $policy['title'].' policy for all staff.',
                    'status' => 'active',
                    'effective_from' => Carbon::today()->subMonth(),
                ]
            );
        }
    }
}
