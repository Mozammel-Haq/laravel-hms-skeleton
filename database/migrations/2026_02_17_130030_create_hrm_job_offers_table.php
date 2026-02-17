<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hrm_job_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->restrictOnDelete();
            $table->foreignId('candidate_id')->constrained('hrm_candidates')->restrictOnDelete();
            $table->foreignId('job_post_id')->nullable()->constrained('hrm_job_posts')->nullOnDelete();
            $table->string('offered_role');
            $table->decimal('salary_offered', 12, 2)->default(0);
            $table->date('joining_date')->nullable();
            $table->enum('status', ['draft', 'sent', 'accepted', 'rejected', 'withdrawn'])->default('draft');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['clinic_id', 'status'], 'hrm_job_offers_status_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hrm_job_offers');
    }
};

