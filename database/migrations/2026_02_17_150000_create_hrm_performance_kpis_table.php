<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hrm_performance_kpis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->restrictOnDelete();
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('category')->nullable();
            $table->enum('frequency', ['monthly', 'quarterly', 'annually'])->default('annually');
            $table->unsignedTinyInteger('weight')->default(0);
            $table->string('target_role')->nullable();
            $table->foreignId('target_department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->foreignId('target_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('description')->nullable();
            $table->enum('status', ['draft', 'active', 'inactive', 'archived'])->default('draft');
            $table->timestamps();

            $table->unique(['clinic_id', 'code']);
            $table->index(['clinic_id', 'status'], 'hrm_performance_kpis_status_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hrm_performance_kpis');
    }
};

