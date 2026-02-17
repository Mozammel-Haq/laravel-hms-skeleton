<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hrm_payslips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->restrictOnDelete();
            $table->foreignId('payroll_run_id')->nullable()->constrained('hrm_payroll_runs')->nullOnDelete();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->date('period_start');
            $table->date('period_end');
            $table->decimal('basic', 12, 2)->default(0);
            $table->decimal('total_allowances', 12, 2)->default(0);
            $table->decimal('total_deductions', 12, 2)->default(0);
            $table->decimal('gross', 12, 2)->default(0);
            $table->decimal('net', 12, 2)->default(0);
            $table->enum('status', ['draft', 'confirmed', 'paid'])->default('draft');
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->index(['clinic_id', 'user_id', 'period_start', 'period_end'], 'hrm_payslips_period_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hrm_payslips');
    }
};

