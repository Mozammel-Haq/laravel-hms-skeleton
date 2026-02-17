<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hrm_training_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->restrictOnDelete();
            $table->foreignId('course_id')->constrained('hrm_training_courses')->restrictOnDelete();
            $table->foreignId('facilitator_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('location')->nullable();
            $table->unsignedInteger('capacity')->nullable();
            $table->enum('status', ['scheduled', 'ongoing', 'completed', 'cancelled'])->default('scheduled');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['clinic_id', 'course_id'], 'hrm_training_sessions_course_idx');
            $table->index(['clinic_id', 'status'], 'hrm_training_sessions_status_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hrm_training_sessions');
    }
};

