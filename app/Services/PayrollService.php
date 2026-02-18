<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\HrmPayrollAllowance;
use App\Models\HrmPayrollDeduction;
use App\Models\HrmPayrollRun;
use App\Models\HrmPayrollTax;
use App\Models\HrmPayslip;
use App\Models\HrmSalaryStructure;
use App\Models\User;
use Illuminate\Support\Facades\DB;

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

                $allowanceBreakdown = [];
                $deductionBreakdown = [];
                $taxBreakdown = [];

                $allowanceTotal = 0.0;
                $deductionTotal = 0.0;
                $taxTotal = 0.0;

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
