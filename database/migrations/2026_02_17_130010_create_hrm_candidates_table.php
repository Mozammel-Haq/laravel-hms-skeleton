<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hrm_candidates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->restrictOnDelete();
            $table->foreignId('job_post_id')->nullable()->constrained('hrm_job_posts')->nullOnDelete();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('source')->nullable();
            $table->string('resume_url')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['new', 'screening', 'interview', 'offered', 'hired', 'rejected', 'archived'])->default('new');
            $table->timestamps();
            $table->index(['clinic_id', 'status'], 'hrm_candidates_status_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hrm_candidates');
    }
};

