<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hrm_performance_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->restrictOnDelete();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->foreignId('reviewer_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('period_start')->nullable();
            $table->date('period_end')->nullable();
            $table->unsignedTinyInteger('overall_rating')->nullable();
            $table->text('summary')->nullable();
            $table->enum('status', ['draft', 'submitted', 'finalized'])->default('draft');
            $table->timestamps();

            $table->index(['clinic_id', 'user_id', 'status'], 'hrm_performance_reviews_user_status_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hrm_performance_reviews');
    }
};

