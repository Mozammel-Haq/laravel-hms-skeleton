<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('hrm_performance_appraisals')) {
            return;
        }

        Schema::create('hrm_performance_appraisals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->restrictOnDelete();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->foreignId('review_id')->nullable()->constrained('hrm_performance_reviews')->nullOnDelete();
            $table->date('effective_date')->nullable();
            $table->decimal('current_salary', 12, 2)->nullable();
            $table->decimal('new_salary', 12, 2)->nullable();
            $table->decimal('salary_change_amount', 12, 2)->nullable();
            $table->decimal('salary_change_percent', 5, 2)->nullable();
            $table->unsignedBigInteger('promotion_to_designation_id')->nullable();
            $table->foreign('promotion_to_designation_id', 'hrm_appraisal_promo_designation_fk')
                ->references('id')
                ->on('designations')
                ->nullOnDelete();
            $table->enum('status', ['draft', 'recommended', 'approved', 'rejected'])->default('draft');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['clinic_id', 'user_id', 'status'], 'hrm_performance_appraisals_user_status_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hrm_performance_appraisals');
    }
};
