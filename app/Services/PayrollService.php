<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\HrmAttendance;
use App\Models\HrmPayrollAllowance;
use App\Models\HrmPayrollDeduction;
use App\Models\HrmPayrollRun;
use App\Models\HrmPayrollTax;
use App\Models\HrmOvertime;
use App\Models\HrmTimesheet;
use App\Models\HrmPayslip;
use App\Models\HrmSalaryStructure;
use App\Models\HrmShiftAssignment;
use App\Models\HrmShift;
use App\Models\User;
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

                $hasFinalizedForPeriod = HrmPayslip::query()
                    ->where('clinic_id', $clinicId)
                    ->where('user_id', $user->id)
                    ->whereDate('period_start', $run->period_start instanceof Carbon ? $run->period_start->toDateString() : (string) $run->period_start)
                    ->whereDate('period_end', $run->period_end instanceof Carbon ? $run->period_end->toDateString() : (string) $run->period_end)
                    ->whereIn('status', ['confirmed', 'paid'])
                    ->exists();
                if ($hasFinalizedForPeriod) {
                    continue;
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

                $preGross = $basic;

                foreach ($allowances as $allowance) {
                    $amountConfig = (float) $allowance->amount;
                    $calculated = 0.0;

                    if ($allowance->calculation_type === 'fixed') {
                        $calculated = $amountConfig;
                    } elseif ($allowance->calculation_type === 'percent_basic') {
                        $calculated = $basic * $amountConfig / 100;
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

                $intermediateGross = $basic + $allowanceTotal;

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

                $gross = $basic + $allowanceTotal;

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
                    'basic' => $basic,
                    'total_allowances' => $allowanceTotal,
                    'total_deductions' => $totalDeductions,
                    'gross' => $gross,
                    'net' => $net,
                    'status' => 'draft',
                    'meta' => [
                        'salary_structure_id' => $structure->id,
                        'basic_source' => $user->basic_salary_override !== null ? 'override' : 'structure',
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
