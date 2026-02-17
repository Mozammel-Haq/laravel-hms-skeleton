<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hrm_interviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->restrictOnDelete();
            $table->foreignId('candidate_id')->constrained('hrm_candidates')->restrictOnDelete();
            $table->foreignId('job_post_id')->nullable()->constrained('hrm_job_posts')->nullOnDelete();
            $table->dateTime('scheduled_at');
            $table->enum('mode', ['in_person', 'video', 'phone'])->default('in_person');
            $table->string('location')->nullable();
            $table->string('interviewer_name')->nullable();
            $table->foreignId('interviewer_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('result', ['pending', 'shortlisted', 'rejected', 'on_hold'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['clinic_id', 'scheduled_at'], 'hrm_interviews_schedule_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hrm_interviews');
    }
};

