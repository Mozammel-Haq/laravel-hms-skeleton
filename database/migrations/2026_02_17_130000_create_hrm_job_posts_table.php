<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hrm_job_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->restrictOnDelete();
            $table->string('title');
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('employment_type', ['full_time', 'part_time', 'contract', 'locum', 'internship'])->default('full_time');
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->text('requirements')->nullable();
            $table->unsignedInteger('openings')->default(1);
            $table->enum('status', ['draft', 'open', 'closed', 'archived'])->default('draft');
            $table->timestamp('posted_at')->nullable();
            $table->timestamp('closes_at')->nullable();
            $table->timestamps();
            $table->index(['clinic_id', 'status'], 'hrm_job_posts_status_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hrm_job_posts');
    }
};

