<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hrm_payroll_taxes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->restrictOnDelete();
            $table->string('name');
            $table->string('code')->nullable();
            $table->enum('calculation_type', ['flat', 'percent'])->default('percent');
            $table->decimal('rate', 6, 3)->default(0);
            $table->decimal('threshold', 12, 2)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->unique(['clinic_id', 'name'], 'hrm_payroll_taxes_unique_name');
            $table->unique(['clinic_id', 'code'], 'hrm_payroll_taxes_unique_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hrm_payroll_taxes');
    }
};

