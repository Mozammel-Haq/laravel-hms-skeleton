<?php

namespace Tests\Feature\Api;

use App\Models\Clinic;
use App\Models\Expense;
use App\Models\HrmPayrollAllowance;
use App\Models\HrmPayrollDeduction;
use App\Models\HrmPayrollRun;
use App\Models\HrmPayrollTax;
use App\Models\HrmPayslip;
use App\Models\HrmSalaryStructure;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class HrmPayrollRunApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_processing_payroll_run_generates_payslips_and_expense(): void
    {
        Carbon::setTestNow('2026-02-18 10:00:00');

        $clinic = Clinic::create([
            'name' => 'Payroll Clinic',
            'code' => 'PAY-CLINIC',
            'registration_number' => 'PAY-123',
            'address_line_1' => '123 Payroll St',
            'city' => 'City',
            'country' => 'Country',
            'timezone' => 'UTC',
            'currency' => 'USD',
            'status' => 'active',
        ]);

        $manager = User::factory()->create([
            'clinic_id' => $clinic->id,
            'name' => 'Payroll Manager',
            'email' => 'payroll-manager@example.com',
            'status' => 'active',
        ]);

        $staffA = User::factory()->create([
            'clinic_id' => $clinic->id,
            'status' => 'active',
        ]);

        $staffB = User::factory()->create([
            'clinic_id' => $clinic->id,
            'status' => 'active',
        ]);

        $role = Role::firstOrCreate(['name' => 'Finance Manager']);

        $permNames = [
            'view_hrm_dashboard',
            'view_reports',
            'view_financial_reports',
        ];

        $permissionIds = [];

        foreach ($permNames as $name) {
            $perm = Permission::where('name', $name)->first();

            if (! $perm) {
                $perm = Permission::create(['name' => $name]);
            }

            $permissionIds[] = $perm->id;
        }

        $role->permissions()->sync($permissionIds);
        $manager->roles()->syncWithoutDetaching([$role->id]);
        $manager->refresh();

        $structure = HrmSalaryStructure::create([
            'clinic_id' => $clinic->id,
            'name' => 'Default Structure',
            'code' => 'DEF',
            'basic_amount' => 1000,
            'is_default' => true,
            'status' => 'active',
        ]);

        $staffA->update([
            'salary_structure_id' => $structure->id,
            'basic_salary_override' => null,
        ]);

        $staffB->update([
            'salary_structure_id' => $structure->id,
            'basic_salary_override' => 2000,
        ]);

        HrmPayrollAllowance::create([
            'clinic_id' => $clinic->id,
            'name' => 'Transport',
            'code' => 'TR',
            'calculation_type' => 'fixed',
            'amount' => 100,
            'status' => 'active',
        ]);

        HrmPayrollAllowance::create([
            'clinic_id' => $clinic->id,
            'name' => 'House Rent',
            'code' => 'HR',
            'calculation_type' => 'percent_basic',
            'amount' => 10,
            'status' => 'active',
        ]);

        HrmPayrollAllowance::create([
            'clinic_id' => $clinic->id,
            'name' => 'Bonus',
            'code' => 'BN',
            'calculation_type' => 'percent_gross',
            'amount' => 5,
            'status' => 'active',
        ]);

        HrmPayrollDeduction::create([
            'clinic_id' => $clinic->id,
            'name' => 'Provident Fund',
            'code' => 'PF',
            'calculation_type' => 'percent_basic',
            'amount' => 5,
            'status' => 'active',
        ]);

        HrmPayrollTax::create([
            'clinic_id' => $clinic->id,
            'name' => 'Income Tax',
            'code' => 'IT',
            'calculation_type' => 'percent',
            'rate' => 10,
            'threshold' => null,
            'status' => 'active',
        ]);

        Sanctum::actingAs($manager, ['*']);

        $createResponse = $this->withHeader('X-Clinic-ID', $clinic->id)
            ->postJson('/api/v2/payroll-runs', [
                'period_start' => '2026-02-01',
                'period_end' => '2026-02-28',
            ]);

        $createResponse->assertStatus(201);

        $runId = $createResponse->json('data.id');

        $processResponse = $this->withHeader('X-Clinic-ID', $clinic->id)
            ->putJson('/api/v2/payroll-runs/'.$runId, [
                'status' => 'processing',
            ]);

        $processResponse->assertStatus(200);
        $processResponse->assertJsonPath('data.status', 'completed');

        $run = HrmPayrollRun::findOrFail($runId);

        $this->assertEquals($clinic->id, $run->clinic_id);
        $this->assertEquals('completed', $run->status);
        $this->assertEquals($manager->id, $run->processed_by);

        $payslips = HrmPayslip::where('clinic_id', $clinic->id)
            ->where('payroll_run_id', $runId)
            ->orderBy('user_id')
            ->get();

        $this->assertGreaterThanOrEqual(2, $payslips->count());

        $payslipA = $payslips->firstWhere('user_id', $staffA->id);
        $payslipB = $payslips->firstWhere('user_id', $staffB->id);

        $this->assertNotNull($payslipA);
        $this->assertNotNull($payslipB);

        $expectedBasicA = 1000.0;
        $expectedBasicB = 2000.0;

        $allowancesA = 100.0 + ($expectedBasicA * 0.10);
        $intermediateGrossA = $expectedBasicA + $allowancesA;
        $allowancesA += $intermediateGrossA * 0.05;
        $grossA = $expectedBasicA + $allowancesA;

        $deductionA = $expectedBasicA * 0.05;
        $taxA = $grossA * 0.10;
        $netA = $grossA - ($deductionA + $taxA);

        $allowancesB = 100.0 + ($expectedBasicB * 0.10);
        $intermediateGrossB = $expectedBasicB + $allowancesB;
        $allowancesB += $intermediateGrossB * 0.05;
        $grossB = $expectedBasicB + $allowancesB;

        $deductionB = $expectedBasicB * 0.05;
        $taxB = $grossB * 0.10;
        $netB = $grossB - ($deductionB + $taxB);

        $this->assertEqualsWithDelta($expectedBasicA, $payslipA->basic, 0.01);
        $this->assertEqualsWithDelta($expectedBasicB, $payslipB->basic, 0.01);

        $this->assertEqualsWithDelta($grossA, $payslipA->gross, 0.01);
        $this->assertEqualsWithDelta($grossB, $payslipB->gross, 0.01);

        $this->assertEqualsWithDelta($netA, $payslipA->net, 0.01);
        $this->assertEqualsWithDelta($netB, $payslipB->net, 0.01);

        $expectedTotalGross = $payslips->sum('gross');
        $expectedTotalNet = $payslips->sum('net');

        $this->assertEqualsWithDelta($expectedTotalGross, $run->total_gross, 0.01);
        $this->assertEqualsWithDelta($expectedTotalNet, $run->total_net, 0.01);

        $this->assertDatabaseHas('expenses', [
            'clinic_id' => $clinic->id,
            'category' => 'salary',
            'reference_type' => HrmPayrollRun::class,
            'reference_id' => $runId,
        ]);

        $expense = Expense::where('clinic_id', $clinic->id)
            ->where('reference_type', HrmPayrollRun::class)
            ->where('reference_id', $runId)
            ->first();

        $this->assertNotNull($expense);
        $this->assertEqualsWithDelta($expectedTotalNet, (float) $expense->amount, 0.01);
        $this->assertEquals($manager->id, $expense->created_by);
    }
}
