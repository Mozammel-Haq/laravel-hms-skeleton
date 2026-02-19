<?php

namespace Database\Seeders;

use App\Models\Clinic;
use App\Models\HrmAttendance;
use App\Models\HrmCandidate;
use App\Models\HrmCompliancePolicy;
use App\Models\HrmHoliday;
use App\Models\HrmJobOffer;
use App\Models\HrmInterview;
use App\Models\HrmJobPost;
use App\Models\HrmLeaveBalance;
use App\Models\HrmLeaveType;
use App\Models\HrmOnboarding;
use App\Models\HrmOvertime;
use App\Models\HrmPayrollAllowance;
use App\Models\HrmPayrollDeduction;
use App\Models\HrmPayrollRun;
use App\Models\HrmPayrollTax;
use App\Models\HrmPayslip;
use App\Models\HrmPerformanceKpi;
use App\Models\HrmPerformanceReview;
use App\Models\HrmSalaryStructure;
use App\Models\HrmShift;
use App\Models\HrmShiftAssignment;
use App\Models\HrmTimesheet;
use App\Models\HrmTrainingCourse;
use App\Models\HrmTrainingEvaluation;
use App\Models\HrmTrainingSession;
use App\Models\LeaveRequest;
use App\Models\User;
use App\Services\PayrollService;
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
            ['name' => 'Annual Leave', 'code' => 'AL', 'default_days' => 20, 'carry_forward' => true, 'is_paid' => true, 'pay_factor' => 1.0],
            ['name' => 'Sick Leave', 'code' => 'SL', 'default_days' => 10, 'carry_forward' => false, 'is_paid' => true, 'pay_factor' => 1.0],
            ['name' => 'Casual Leave', 'code' => 'CL', 'default_days' => 7, 'carry_forward' => false, 'is_paid' => true, 'pay_factor' => 1.0],
            ['name' => 'Unpaid Leave', 'code' => 'UL', 'default_days' => 10, 'carry_forward' => false, 'is_paid' => false, 'pay_factor' => 0.0],
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
                    'is_paid' => $type['is_paid'],
                    'pay_factor' => $type['pay_factor'],
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

        $users = User::where('clinic_id', $clinicId)->get();

        if ($users->isEmpty()) {
            return;
        }

        $hrAdmin = $users->firstWhere('email', 'hr@hospital.com');
        $doctor = $users->firstWhere('email', 'doctor@hospital.com');
        $nurse = $users->firstWhere('email', 'nurse@hospital.com');

        $staffForDemo = collect([$hrAdmin, $doctor, $nurse])->filter();

        if ($staffForDemo->isEmpty()) {
            $staffForDemo = $users->take(3);
        }

        $defaultStructure = HrmSalaryStructure::firstOrCreate(
            [
                'clinic_id' => $clinicId,
                'code' => 'STAFF-DEFAULT',
            ],
            [
                'clinic_id' => $clinicId,
                'name' => 'Default Staff Structure',
                'code' => 'STAFF-DEFAULT',
                'basic_amount' => 30000,
                'is_default' => true,
                'status' => 'active',
            ]
        );

        $doctorStructure = HrmSalaryStructure::firstOrCreate(
            [
                'clinic_id' => $clinicId,
                'code' => 'DOCTOR-DEFAULT',
            ],
            [
                'clinic_id' => $clinicId,
                'name' => 'Doctor Structure',
                'code' => 'DOCTOR-DEFAULT',
                'basic_amount' => 80000,
                'is_default' => false,
                'status' => 'active',
            ]
        );

        foreach ($staffForDemo as $user) {
            if ($user->email === 'doctor@hospital.com') {
                if (! $user->salary_structure_id) {
                    $user->salary_structure_id = $doctorStructure->id;
                    $user->save();
                }
            } else {
                if (! $user->salary_structure_id) {
                    $user->salary_structure_id = $defaultStructure->id;
                    $user->save();
                }
            }
        }

        $allowances = [
            ['name' => 'House Rent', 'code' => 'HRA', 'calculation_type' => 'percent_basic', 'amount' => 50],
            ['name' => 'Medical Allowance', 'code' => 'MED', 'calculation_type' => 'fixed', 'amount' => 1500],
            ['name' => 'Transport Allowance', 'code' => 'TRAN', 'calculation_type' => 'fixed', 'amount' => 1000],
        ];

        foreach ($allowances as $item) {
            HrmPayrollAllowance::firstOrCreate(
                [
                    'clinic_id' => $clinicId,
                    'name' => $item['name'],
                ],
                [
                    'clinic_id' => $clinicId,
                    'name' => $item['name'],
                    'code' => $item['code'],
                    'calculation_type' => $item['calculation_type'],
                    'amount' => $item['amount'],
                    'status' => 'active',
                ]
            );
        }

        $deductions = [
            ['name' => 'Provident Fund', 'code' => 'PF', 'calculation_type' => 'percent_basic', 'amount' => 10],
            ['name' => 'Professional Tax', 'code' => 'PT', 'calculation_type' => 'fixed', 'amount' => 200],
        ];

        foreach ($deductions as $item) {
            HrmPayrollDeduction::firstOrCreate(
                [
                    'clinic_id' => $clinicId,
                    'name' => $item['name'],
                ],
                [
                    'clinic_id' => $clinicId,
                    'name' => $item['name'],
                    'code' => $item['code'],
                    'calculation_type' => $item['calculation_type'],
                    'amount' => $item['amount'],
                    'status' => 'active',
                ]
            );
        }

        $taxes = [
            ['name' => 'Income Tax', 'code' => 'IT', 'calculation_type' => 'percent', 'rate' => 5.0, 'threshold' => 30000],
        ];

        foreach ($taxes as $item) {
            HrmPayrollTax::firstOrCreate(
                [
                    'clinic_id' => $clinicId,
                    'name' => $item['name'],
                ],
                [
                    'clinic_id' => $clinicId,
                    'name' => $item['name'],
                    'code' => $item['code'],
                    'calculation_type' => $item['calculation_type'],
                    'rate' => $item['rate'],
                    'threshold' => $item['threshold'],
                    'status' => 'active',
                ]
            );
        }

        $year = (int) date('Y');

        $leaveTypesByCode = HrmLeaveType::where('clinic_id', $clinicId)
            ->where('status', 'active')
            ->get()
            ->keyBy('code');

        foreach ($staffForDemo as $user) {
            foreach ($leaveTypesByCode as $code => $type) {
                $opening = $type->default_days ?? 0;
                $accrued = $opening;
                $used = $code === 'AL' ? 3 : ($code === 'SL' ? 2 : 0);
                $closing = $opening + $accrued - $used;

                HrmLeaveBalance::firstOrCreate(
                    [
                        'clinic_id' => $clinicId,
                        'user_id' => $user->id,
                        'leave_type' => $code,
                        'year' => $year,
                    ],
                    [
                        'clinic_id' => $clinicId,
                        'user_id' => $user->id,
                        'leave_type' => $code,
                        'year' => $year,
                        'opening_balance' => $opening,
                        'accrued' => $accrued,
                        'used' => $used,
                        'closing_balance' => $closing,
                        'status' => 'active',
                    ]
                );
            }
        }

        $today = Carbon::today();
        $startPeriod = $today->copy()->subMonth()->startOfMonth();
        $endPeriod = $today->copy()->subMonth()->endOfMonth();

        foreach ($staffForDemo as $user) {
            for ($i = 0; $i < 5; $i++) {
                $date = $today->copy()->subDays($i + 1);

                HrmAttendance::firstOrCreate(
                    [
                        'clinic_id' => $clinicId,
                        'user_id' => $user->id,
                        'attendance_date' => $date->toDateString(),
                    ],
                    [
                        'clinic_id' => $clinicId,
                        'user_id' => $user->id,
                        'attendance_date' => $date->toDateString(),
                        'check_in_time' => '09:00',
                        'check_out_time' => '17:00',
                        'worked_hours' => 8,
                        'status' => 'present',
                        'is_late' => false,
                        'is_early_exit' => false,
                        'source' => 'demo',
                        'meta' => null,
                    ]
                );

                HrmTimesheet::firstOrCreate(
                    [
                        'clinic_id' => $clinicId,
                        'user_id' => $user->id,
                        'date' => $date->toDateString(),
                        'project' => 'Clinic Operations',
                        'task' => 'General Duties',
                    ],
                    [
                        'clinic_id' => $clinicId,
                        'user_id' => $user->id,
                        'date' => $date->toDateString(),
                        'hours' => 8,
                        'project' => 'Clinic Operations',
                        'task' => 'General Duties',
                        'notes' => 'Demo timesheet entry',
                        'status' => 'approved',
                    ]
                );
            }

            HrmOvertime::firstOrCreate(
                [
                    'clinic_id' => $clinicId,
                    'user_id' => $user->id,
                    'date' => $today->copy()->subDays(3)->toDateString(),
                ],
                [
                    'clinic_id' => $clinicId,
                    'user_id' => $user->id,
                    'date' => $today->copy()->subDays(3)->toDateString(),
                    'hours' => 2,
                    'multiplier' => 1.5,
                    'reason' => 'Demo overtime',
                    'status' => 'approved',
                ]
            );
        }

        foreach ($staffForDemo as $user) {
            LeaveRequest::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'leave_type' => 'AL',
                    'start_date' => $startPeriod->copy()->addDays(5)->toDateString(),
                    'end_date' => $startPeriod->copy()->addDays(7)->toDateString(),
                ],
                [
                    'user_id' => $user->id,
                    'leave_type' => 'AL',
                    'start_date' => $startPeriod->copy()->addDays(5)->toDateString(),
                    'end_date' => $startPeriod->copy()->addDays(7)->toDateString(),
                    'reason' => 'Demo annual leave',
                    'status' => 'approved',
                ]
            );

            LeaveRequest::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'leave_type' => 'UL',
                    'start_date' => $startPeriod->copy()->addDays(10)->toDateString(),
                    'end_date' => $startPeriod->copy()->addDays(10)->toDateString(),
                ],
                [
                    'user_id' => $user->id,
                    'leave_type' => 'UL',
                    'start_date' => $startPeriod->copy()->addDays(10)->toDateString(),
                    'end_date' => $startPeriod->copy()->addDays(10)->toDateString(),
                    'reason' => 'Demo unpaid leave',
                    'status' => 'approved',
                ]
            );
        }

        $hrActor = $hrAdmin ?: $staffForDemo->first();

        if ($hrActor) {
            $run = HrmPayrollRun::firstOrCreate(
                [
                    'clinic_id' => $clinicId,
                    'period_start' => $startPeriod->toDateString(),
                    'period_end' => $endPeriod->toDateString(),
                ],
                [
                    'clinic_id' => $clinicId,
                    'period_start' => $startPeriod->toDateString(),
                    'period_end' => $endPeriod->toDateString(),
                    'status' => 'draft',
                    'total_gross' => 0,
                    'total_net' => 0,
                    'processed_by' => null,
                ]
            );

            $service = app(PayrollService::class);
            $service->processRun($run, $hrActor);
        }

        $shift = HrmShift::firstOrCreate(
            [
                'clinic_id' => $clinicId,
                'name' => 'General Shift',
            ],
            [
                'clinic_id' => $clinicId,
                'name' => 'General Shift',
                'start_time' => Carbon::parse('09:00:00'),
                'end_time' => Carbon::parse('17:00:00'),
                'status' => 'active',
            ]
        );

        foreach ($staffForDemo as $user) {
            HrmShiftAssignment::firstOrCreate(
                [
                    'clinic_id' => $clinicId,
                    'user_id' => $user->id,
                    'shift_id' => $shift->id,
                    'effective_from' => $today->copy()->subMonth()->startOfMonth()->toDateString(),
                ],
                [
                    'clinic_id' => $clinicId,
                    'user_id' => $user->id,
                    'shift_id' => $shift->id,
                    'effective_from' => $today->copy()->subMonth()->startOfMonth()->toDateString(),
                    'effective_to' => null,
                    'status' => 'active',
                    'is_primary' => true,
                ]
            );
        }

        $kpis = [
            ['name' => 'Patient Satisfaction', 'code' => 'KPI-PS', 'category' => 'Quality', 'frequency' => 'monthly', 'weight' => 40],
            ['name' => 'On-Time Attendance', 'code' => 'KPI-AT', 'category' => 'Discipline', 'frequency' => 'monthly', 'weight' => 30],
        ];

        foreach ($kpis as $item) {
            HrmPerformanceKpi::firstOrCreate(
                [
                    'clinic_id' => $clinicId,
                    'code' => $item['code'],
                ],
                [
                    'clinic_id' => $clinicId,
                    'name' => $item['name'],
                    'code' => $item['code'],
                    'category' => $item['category'],
                    'frequency' => $item['frequency'],
                    'weight' => $item['weight'],
                    'target_role' => null,
                    'target_department_id' => null,
                    'target_user_id' => null,
                    'description' => $item['name'],
                    'status' => 'active',
                ]
            );
        }

        foreach ($staffForDemo as $user) {
            HrmPerformanceReview::firstOrCreate(
                [
                    'clinic_id' => $clinicId,
                    'user_id' => $user->id,
                    'period_start' => $startPeriod->toDateString(),
                    'period_end' => $endPeriod->toDateString(),
                ],
                [
                    'clinic_id' => $clinicId,
                    'user_id' => $user->id,
                    'reviewer_user_id' => $hrActor ? $hrActor->id : $user->id,
                    'period_start' => $startPeriod->toDateString(),
                    'period_end' => $endPeriod->toDateString(),
                    'overall_rating' => 4,
                    'summary' => 'Demo performance review',
                    'status' => 'finalized',
                ]
            );
        }

        $candidate = HrmCandidate::firstOrCreate(
            [
                'clinic_id' => $clinicId,
                'email' => 'candidate@hospital.com',
            ],
                [
                    'clinic_id' => $clinicId,
                    'job_post_id' => HrmJobPost::where('clinic_id', $clinicId)->first()?->id,
                    'name' => 'Demo Candidate',
                    'email' => 'candidate@hospital.com',
                    'phone' => '01700000001',
                    'source' => 'Referral',
                    'resume_url' => null,
                    'notes' => 'Demo candidate for HRM flows',
                    'status' => 'interview',
                ]
            );

        if ($candidate) {
            $jobPostId = $candidate->job_post_id ?: HrmJobPost::where('clinic_id', $clinicId)->first()?->id;

            $interview = HrmInterview::firstOrCreate(
                [
                    'clinic_id' => $clinicId,
                    'candidate_id' => $candidate->id,
                    'job_post_id' => $jobPostId,
                ],
                [
                    'clinic_id' => $clinicId,
                    'candidate_id' => $candidate->id,
                    'job_post_id' => $jobPostId,
                    'scheduled_at' => $today->copy()->addDays(2),
                    'mode' => 'in_person',
                    'location' => 'Clinic Meeting Room',
                    'interviewer_name' => $hrActor ? $hrActor->name : 'HR',
                    'interviewer_user_id' => $hrActor ? $hrActor->id : null,
                    'result' => 'pending',
                    'notes' => 'Demo interview schedule',
                ]
            );

            HrmJobOffer::firstOrCreate(
                [
                    'clinic_id' => $clinicId,
                    'candidate_id' => $candidate->id,
                    'job_post_id' => $jobPostId,
                ],
                [
                    'clinic_id' => $clinicId,
                    'candidate_id' => $candidate->id,
                    'job_post_id' => $jobPostId,
                    'offered_role' => 'Staff Nurse',
                    'salary_offered' => 28000,
                    'joining_date' => $today->copy()->addWeeks(2),
                    'status' => 'sent',
                    'notes' => 'Demo job offer',
                ]
            );

            HrmOnboarding::firstOrCreate(
                [
                    'clinic_id' => $clinicId,
                    'candidate_id' => $candidate->id,
                ],
                [
                    'clinic_id' => $clinicId,
                    'candidate_id' => $candidate->id,
                    'user_id' => null,
                    'start_date' => $today->copy()->addWeeks(2),
                    'completion_date' => null,
                    'status' => 'in_progress',
                    'checklist' => ['Documents verification', 'Orientation scheduled'],
                    'notes' => 'Demo onboarding checklist',
                ]
            );
        }

        $sessionsForEvaluation = HrmTrainingSession::where('clinic_id', $clinicId)->get();

        foreach ($sessionsForEvaluation as $session) {
            foreach ($staffForDemo as $user) {
                HrmTrainingEvaluation::firstOrCreate(
                    [
                        'clinic_id' => $clinicId,
                        'session_id' => $session->id,
                        'user_id' => $user->id,
                    ],
                    [
                        'clinic_id' => $clinicId,
                        'session_id' => $session->id,
                        'user_id' => $user->id,
                        'rating' => 5,
                        'feedback' => 'Very useful training session',
                        'completed_at' => $today->copy()->subDays(1),
                    ]
                );
            }
        }
    }
}
