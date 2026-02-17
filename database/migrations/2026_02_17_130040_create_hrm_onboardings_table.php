<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hrm_onboardings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->restrictOnDelete();
            $table->foreignId('candidate_id')->nullable()->constrained('hrm_candidates')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->date('start_date')->nullable();
            $table->date('completion_date')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->json('checklist')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['clinic_id', 'status'], 'hrm_onboardings_status_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hrm_onboardings');
    }
};

