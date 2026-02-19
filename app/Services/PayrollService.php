<?php

namespace App\Services;

use App\Models\Expense;
<<<<<<< HEAD
use App\Models\HrmAttendance;
=======
use App\Models\HrmLeaveType;
use App\Models\HrmOvertime;
>>>>>>> f96678b944ab795acd27463a1e77c7170ce1b9f3
use App\Models\HrmPayrollAllowance;
use App\Models\HrmPayrollDeduction;
use App\Models\HrmPayrollRun;
use App\Models\HrmPayrollTax;
use App\Models\HrmOvertime;
use App\Models\HrmTimesheet;
use App\Models\HrmPayslip;
use App\Models\HrmSalaryStructure;
<<<<<<< HEAD
use App\Models\HrmShiftAssignment;
use App\Models\HrmShift;
=======
use App\Models\LeaveRequest;
>>>>>>> f96678b944ab795acd27463a1e77c7170ce1b9f3
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class PayrollService
{
    public function processRun(HrmPayrollRun $run, User $actor): HrmPayrollRun
    {
        if ($run->status === 'completed' || $run->status === 'cancelled') {
            return $run;
        }

        return DB::transaction(function () use ($run, $actor) {
            $clinicId = $run->clinic_id;

            HrmPayslip::query()
                ->where('clinic_id', $clinicId)
                ->where('payroll_run_id', $run->id)
                ->delete();

            Expense::query()
                ->where('clinic_id', $clinicId)
                ->where('reference_type', HrmPayrollRun::class)
                ->where('reference_id', $run->id)
                ->delete();

            $defaultStructure = HrmSalaryStructure::query()
                ->where('clinic_id', $clinicId)
                ->where('status', 'active')
                ->where('is_default', true)
                ->first();

            $allowances = HrmPayrollAllowance::query()
                ->where('clinic_id', $clinicId)
                ->where('status', 'active')
                ->get();

            $deductions = HrmPayrollDeduction::query()
                ->where('clinic_id', $clinicId)
                ->where('status', 'active')
                ->get();

            $taxes = HrmPayrollTax::query()
                ->where('clinic_id', $clinicId)
                ->where('status', 'active')
                ->get();

            $users = User::query()
                ->where('clinic_id', $clinicId)
                ->where('status', 'active')
                ->where(function ($q) use ($run) {
                    $q->whereNull('join_date')
                        ->orWhere('join_date', '<=', $run->period_end);
                })
                ->get();

            $userIds = $users->pluck('id');

            $leaveTypes = HrmLeaveType::query()
                ->where('clinic_id', $clinicId)
                ->where('status', 'active')
                ->get();

            $leaveTypesByCode = [];
            $leaveTypesByName = [];

            foreach ($leaveTypes as $type) {
                $nameKey = mb_strtolower($type->name);
                $leaveTypesByName[$nameKey] = $type;

                if ($type->code !== null && $type->code !== '') {
                    $codeKey = mb_strtolower($type->code);
                    $leaveTypesByCode[$codeKey] = $type;
                }
            }

            $leaveRequests = LeaveRequest::query()
                ->whereIn('user_id', $userIds)
                ->where('status', 'approved')
                ->whereDate('start_date', '<=', $run->period_end)
                ->whereDate('end_date', '>=', $run->period_start)
                ->get()
                ->groupBy('user_id');

            $overtimes = HrmOvertime::query()
                ->where('clinic_id', $clinicId)
                ->whereIn('user_id', $userIds)
                ->where('status', 'approved')
                ->whereDate('date', '>=', $run->period_start)
                ->whereDate('date', '<=', $run->period_end)
                ->get()
                ->groupBy('user_id');

            $totalGross = 0.0;
            $totalNet = 0.0;

            foreach ($users as $user) {
                $structure = null;

                if ($user->salary_structure_id) {
                    $structure = HrmSalaryStructure::query()
                        ->where('clinic_id', $clinicId)
                        ->where('id', $user->salary_structure_id)
                        ->where('status', 'active')
                        ->first();
                }

                if (! $structure) {
                    $structure = $defaultStructure;
                }

                if (! $structure) {
                    continue;
                }

                $basic = $user->basic_salary_override !== null
                    ? (float) $user->basic_salary_override
                    : (float) $structure->basic_amount;

                if ($basic <= 0) {
                    continue;
                }

<<<<<<< HEAD
                $hasFinalizedForPeriod = HrmPayslip::query()
                    ->where('clinic_id', $clinicId)
                    ->where('user_id', $user->id)
                    ->whereDate('period_start', $run->period_start instanceof Carbon ? $run->period_start->toDateString() : (string) $run->period_start)
                    ->whereDate('period_end', $run->period_end instanceof Carbon ? $run->period_end->toDateString() : (string) $run->period_end)
                    ->whereIn('status', ['confirmed', 'paid'])
                    ->exists();
                if ($hasFinalizedForPeriod) {
                    continue;
=======
                $periodStart = $run->period_start instanceof Carbon ? $run->period_start->copy()->startOfDay() : Carbon::parse($run->period_start)->startOfDay();
                $periodEnd = $run->period_end instanceof Carbon ? $run->period_end->copy()->startOfDay() : Carbon::parse($run->period_end)->startOfDay();
                $daysInPeriod = $periodStart->diffInDays($periodEnd) + 1;

                $userLeaves = $leaveRequests->get($user->id, collect());
                $payFactor = 1.0;
                $payFactorDetails = [
                    'period_days' => $daysInPeriod,
                    'effective_days' => $daysInPeriod,
                    'unpaid_days' => 0,
                    'average_factor' => 1.0,
                ];

                if ($daysInPeriod > 0 && $userLeaves->isNotEmpty()) {
                    $dayFactors = [];

                    for ($i = 0; $i < $daysInPeriod; $i++) {
                        $day = $periodStart->copy()->addDays($i)->toDateString();
                        $dayFactors[$day] = 1.0;
                    }

                    foreach ($userLeaves as $leave) {
                        $type = null;
                        $rawType = $leave->leave_type ?? '';

                        if ($rawType !== '') {
                            $key = mb_strtolower($rawType);
                            $type = $leaveTypesByCode[$key] ?? $leaveTypesByName[$key] ?? null;
                        }

                        $factorForLeave = 1.0;

                        if ($type) {
                            if ($type->pay_factor !== null) {
                                $factorForLeave = (float) $type->pay_factor;
                            } elseif ($type->is_paid === false) {
                                $factorForLeave = 0.0;
                            }
                        }

                        $leaveStart = $leave->start_date instanceof Carbon ? $leave->start_date->copy()->startOfDay() : Carbon::parse($leave->start_date)->startOfDay();
                        $leaveEnd = $leave->end_date instanceof Carbon ? $leave->end_date->copy()->startOfDay() : Carbon::parse($leave->end_date)->startOfDay();

                        if ($leaveEnd->lt($periodStart) || $leaveStart->gt($periodEnd)) {
                            continue;
                        }

                        if ($leaveStart->lt($periodStart)) {
                            $leaveStart = $periodStart->copy();
                        }

                        if ($leaveEnd->gt($periodEnd)) {
                            $leaveEnd = $periodEnd->copy();
                        }

                        $leaveDays = $leaveStart->diffInDays($leaveEnd) + 1;

                        for ($i = 0; $i < $leaveDays; $i++) {
                            $current = $leaveStart->copy()->addDays($i)->toDateString();

                            if (! array_key_exists($current, $dayFactors)) {
                                continue;
                            }

                            $dayFactors[$current] = min($dayFactors[$current], $factorForLeave);
                        }
                    }

                    $sumFactors = array_sum($dayFactors);
                    $payFactor = $daysInPeriod > 0 ? $sumFactors / $daysInPeriod : 1.0;

                    $unpaidDays = 0.0;

                    foreach ($dayFactors as $value) {
                        if ($value < 1.0) {
                            $unpaidDays += (1.0 - $value);
                        }
                    }

                    $payFactorDetails = [
                        'period_days' => $daysInPeriod,
                        'effective_days' => $daysInPeriod - $unpaidDays,
                        'unpaid_days' => $unpaidDays,
                        'average_factor' => $payFactor,
                    ];
                }

                $effectiveBasic = $basic;

                if ($payFactor > 0.0 && $payFactor < 1.0) {
                    $effectiveBasic = $basic * $payFactor;
>>>>>>> f96678b944ab795acd27463a1e77c7170ce1b9f3
                }

                $allowanceBreakdown = [];
                $deductionBreakdown = [];
                $taxBreakdown = [];
                $attendanceSummary = [];
                $overtimeItems = [];
                $timesheetSummary = [];

                $allowanceTotal = 0.0;
                $deductionTotal = 0.0;
                $taxTotal = 0.0;
                $attendanceDeduction = 0.0;
                $overtimeAllowance = 0.0;

                $preGross = $effectiveBasic;

                foreach ($allowances as $allowance) {
                    $amountConfig = (float) $allowance->amount;
                    $calculated = 0.0;

                    if ($allowance->calculation_type === 'fixed') {
                        $calculated = $amountConfig;
                    } elseif ($allowance->calculation_type === 'percent_basic') {
                        $calculated = $effectiveBasic * $amountConfig / 100;
                    } elseif ($allowance->calculation_type === 'percent_gross') {
                        continue;
                    }

                    if ($calculated <= 0) {
                        continue;
                    }

                    $allowanceTotal += $calculated;

                    $allowanceBreakdown[] = [
                        'id' => $allowance->id,
                        'name' => $allowance->name,
                        'calculation_type' => $allowance->calculation_type,
                        'amount_config' => $amountConfig,
                        'calculated_amount' => $calculated,
                    ];
                }

                $intermediateGross = $effectiveBasic + $allowanceTotal;

                foreach ($allowances as $allowance) {
                    if ($allowance->calculation_type !== 'percent_gross') {
                        continue;
                    }

                    $amountConfig = (float) $allowance->amount;
                    $calculated = $intermediateGross * $amountConfig / 100;

                    if ($calculated <= 0) {
                        continue;
                    }

                    $allowanceTotal += $calculated;

                    $allowanceBreakdown[] = [
                        'id' => $allowance->id,
                        'name' => $allowance->name,
                        'calculation_type' => $allowance->calculation_type,
                        'amount_config' => $amountConfig,
                        'calculated_amount' => $calculated,
                    ];
                }

                $userOvertimes = $overtimes->get($user->id, collect());

                $overtimeTotal = 0.0;

                if ($userOvertimes->isNotEmpty() && $daysInPeriod > 0) {
                    $standardHours = $daysInPeriod * 8;
                    $hourlyRate = $standardHours > 0 ? $basic / $standardHours : 0.0;

                    foreach ($userOvertimes as $overtime) {
                        $hours = (float) $overtime->hours;
                        $multiplier = (float) $overtime->multiplier;

                        if ($hours <= 0 || $multiplier <= 0) {
                            continue;
                        }

                        $calculated = $hourlyRate * $hours * $multiplier;

                        if ($calculated <= 0) {
                            continue;
                        }

                        $overtimeTotal += $calculated;

                        $allowanceBreakdown[] = [
                            'id' => $overtime->id,
                            'name' => 'Overtime '.$overtime->date->toDateString(),
                            'calculation_type' => 'overtime',
                            'amount_config' => $hours * $multiplier,
                            'calculated_amount' => $calculated,
                        ];
                    }
                }

                if ($overtimeTotal > 0) {
                    $allowanceTotal += $overtimeTotal;
                }

                $gross = $effectiveBasic + $allowanceTotal;

                $periodStart = Carbon::parse($run->period_start)->startOfDay();
                $periodEnd = Carbon::parse($run->period_end)->endOfDay();
                $periodDays = (int) ceil(max(1, $periodStart->diffInDays($periodEnd) + 1));
                $dailyRate = $basic / $periodDays;

                $hoursPerDay = 8.0;
                $primaryAssignment = HrmShiftAssignment::query()
                    ->with('shift')
                    ->where('clinic_id', $clinicId)
                    ->where('user_id', $user->id)
                    ->where('is_primary', true)
                    ->where('status', 'active')
                    ->whereDate('effective_from', '<=', $periodEnd->toDateString())
                    ->where(function ($q) use ($periodStart) {
                        $q->whereNull('effective_to')
                            ->orWhereDate('effective_to', '>=', $periodStart->toDateString());
                    })
                    ->orderByDesc('effective_from')
                    ->first();

                if ($primaryAssignment && $primaryAssignment->shift) {
                    $startStr = substr((string) $primaryAssignment->shift->start_time, 0, 5);
                    $endStr = substr((string) $primaryAssignment->shift->end_time, 0, 5);
                    try {
                        $start = Carbon::createFromFormat('H:i', $startStr);
                        $end = Carbon::createFromFormat('H:i', $endStr);
                        if ($end->lessThanOrEqualTo($start)) {
                            $end->addDay();
                        }
                        $durationMinutes = $start->diffInMinutes($end);
                        $breakMinutes = (int) ($primaryAssignment->shift->break_minutes ?? 0);
                        $workMinutes = max(0, $durationMinutes - $breakMinutes);
                        $hoursPerDayCandidate = round($workMinutes / 60, 2);
                        if ($hoursPerDayCandidate > 0 && $hoursPerDayCandidate <= 24) {
                            $hoursPerDay = $hoursPerDayCandidate;
                        }
                    } catch (\Throwable $e) {
                        // ignore parsing errors, fallback to default hoursPerDay
                    }
                }

                $hourlyRate = $dailyRate / $hoursPerDay;

                $attendances = HrmAttendance::query()
                    ->where('clinic_id', $clinicId)
                    ->where('user_id', $user->id)
                    ->whereDate('attendance_date', '>=', $periodStart->toDateString())
                    ->whereDate('attendance_date', '<=', $periodEnd->toDateString())
                    ->get();

                $presentDays = 0.0;
                $halfDays = 0.0;
                $absentDays = 0.0;
                $leaveDays = 0.0;
                $holidayDays = 0.0;

                foreach ($attendances as $a) {
                    $s = strtolower((string) $a->status);
                    if ($s === 'present') {
                        $presentDays += 1.0;
                    } elseif ($s === 'half-day') {
                        $halfDays += 1.0;
                    } elseif ($s === 'absent') {
                        $absentDays += 1.0;
                    } elseif ($s === 'leave') {
                        $leaveDays += 1.0;
                    } elseif ($s === 'holiday') {
                        $holidayDays += 1.0;
                    }
                }

                $treatMissingDaysAsAbsent = true;
                $countedDays = $presentDays + $halfDays + $leaveDays + $holidayDays + $absentDays;
                $missingDays = max(0.0, $periodDays - $countedDays);
                if ($treatMissingDaysAsAbsent && $missingDays > 0) {
                    $absentDays += $missingDays;
                }

                $attendanceSummary = [
                    'period_days' => (int) $periodDays,
                    'present_days' => (int) round($presentDays),
                    'half_days' => (int) round($halfDays),
                    'absent_days' => (int) round($absentDays),
                    'leave_days' => (int) round($leaveDays),
                    'holiday_days' => (int) round($holidayDays),
                ];

                $attendanceDeduction = ($absentDays * $dailyRate) + ($halfDays * $dailyRate * 0.5) + ($leaveDays * $dailyRate);

                if ($attendanceDeduction > 0) {
                    $deductionTotal += $attendanceDeduction;
                    $deductionBreakdown[] = [
                        'id' => null,
                        'name' => 'Attendance Proration',
                        'calculation_type' => 'derived',
                        'amount_config' => null,
                        'calculated_amount' => round($attendanceDeduction, 2),
                    ];
                }

                $overtime = HrmOvertime::query()
                    ->where('clinic_id', $clinicId)
                    ->where('user_id', $user->id)
                    ->whereDate('date', '>=', $periodStart->toDateString())
                    ->whereDate('date', '<=', $periodEnd->toDateString())
                    ->get();

                foreach ($overtime as $ot) {
                    $hours = (float) $ot->hours;
                    $multiplier = $ot->multiplier !== null ? (float) $ot->multiplier : 1.0;
                    $calc = $hourlyRate * $hours * $multiplier;
                    if ($calc <= 0) {
                        continue;
                    }
                    $overtimeAllowance += $calc;
                    $overtimeItems[] = [
                        'date' => $ot->date?->toDateString(),
                        'hours' => $hours,
                        'multiplier' => $multiplier,
                        'calculated_amount' => round($calc, 2),
                    ];
                }

                if ($overtimeAllowance > 0) {
                    $allowanceTotal += $overtimeAllowance;
                    $allowanceBreakdown[] = [
                        'id' => null,
                        'name' => 'Overtime',
                        'calculation_type' => 'derived',
                        'amount_config' => null,
                        'calculated_amount' => round($overtimeAllowance, 2),
                    ];
                }

                foreach ($deductions as $deduction) {
                    $amountConfig = (float) $deduction->amount;
                    $calculated = 0.0;

                    if ($deduction->calculation_type === 'fixed') {
                        $calculated = $amountConfig;
                    } elseif ($deduction->calculation_type === 'percent_basic') {
                        $calculated = $basic * $amountConfig / 100;
                    } elseif ($deduction->calculation_type === 'percent_gross') {
                        $calculated = $gross * $amountConfig / 100;
                    }

                    if ($calculated <= 0) {
                        continue;
                    }

                    $deductionTotal += $calculated;

                    $deductionBreakdown[] = [
                        'id' => $deduction->id,
                        'name' => $deduction->name,
                        'calculation_type' => $deduction->calculation_type,
                        'amount_config' => $amountConfig,
                        'calculated_amount' => $calculated,
                    ];
                }

                foreach ($taxes as $tax) {
                    $rate = (float) $tax->rate;
                    $threshold = $tax->threshold !== null ? (float) $tax->threshold : null;
                    $taxableBase = $gross;

                    if ($threshold !== null && $taxableBase <= $threshold) {
                        continue;
                    }

                    $calculated = 0.0;

                    if ($tax->calculation_type === 'percent') {
                        $calculated = $taxableBase * $rate / 100;
                    } elseif ($tax->calculation_type === 'flat') {
                        $calculated = $rate;
                    }

                    if ($calculated <= 0) {
                        continue;
                    }

                    $taxTotal += $calculated;

                    $taxBreakdown[] = [
                        'id' => $tax->id,
                        'name' => $tax->name,
                        'calculation_type' => $tax->calculation_type,
                        'rate' => $rate,
                        'threshold' => $threshold,
                        'calculated_amount' => $calculated,
                    ];
                }

                $totalDeductions = $deductionTotal + $taxTotal;
                $net = $gross - $totalDeductions;

                if ($net < 0) {
                    $net = 0.0;
                }

                $totalGross += $gross;
                $totalNet += $net;

                $timesheetHours = HrmTimesheet::query()
                    ->where('clinic_id', $clinicId)
                    ->where('user_id', $user->id)
                    ->whereDate('date', '>=', $periodStart->toDateString())
                    ->whereDate('date', '<=', $periodEnd->toDateString())
                    ->sum('hours');

                $timesheetSummary = [
                    'total_hours' => (float) $timesheetHours,
                ];

                HrmPayslip::create([
                    'clinic_id' => $clinicId,
                    'payroll_run_id' => $run->id,
                    'user_id' => $user->id,
                    'period_start' => $run->period_start,
                    'period_end' => $run->period_end,
                    'basic' => $effectiveBasic,
                    'total_allowances' => $allowanceTotal,
                    'total_deductions' => $totalDeductions,
                    'gross' => $gross,
                    'net' => $net,
                    'status' => 'draft',
                    'meta' => [
                        'salary_structure_id' => $structure->id,
                        'basic_source' => $user->basic_salary_override !== null ? 'override' : 'structure',
                        'basic_original' => $basic,
                        'payroll_factors' => $payFactorDetails,
                        'allowances' => $allowanceBreakdown,
                        'deductions' => $deductionBreakdown,
                        'taxes' => $taxBreakdown,
                        'attendance' => $attendanceSummary,
                        'attendance_deduction' => round($attendanceDeduction, 2),
                        'overtime' => $overtimeItems,
                        'overtime_allowance' => round($overtimeAllowance, 2),
                        'timesheets' => $timesheetSummary,
                        'derived_rates' => [
                            'hours_per_day' => $hoursPerDay,
                            'daily_rate' => round($dailyRate, 2),
                            'hourly_rate' => round($hourlyRate, 2),
                        ],
                    ],
                ]);
            }

            $run->total_gross = $totalGross;
            $run->total_net = $totalNet;
            $run->status = 'completed';
            $run->processed_by = $actor->id;
            $run->save();

            if ($totalNet > 0) {
                Expense::create([
                    'clinic_id' => $clinicId,
                    'description' => 'Payroll for '.$run->period_start->toDateString().' to '.$run->period_end->toDateString(),
                    'amount' => $totalNet,
                    'category' => 'salary',
                    'expense_date' => $run->period_end->toDateString(),
                    'reference_type' => HrmPayrollRun::class,
                    'reference_id' => $run->id,
                    'created_by' => $actor->id,
                ]);
            }

            return $run->fresh();
        });
    }
}
