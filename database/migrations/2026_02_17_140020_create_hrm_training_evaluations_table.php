<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hrm_training_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->restrictOnDelete();
            $table->foreignId('session_id')->constrained('hrm_training_sessions')->restrictOnDelete();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->unsignedTinyInteger('rating')->nullable();
            $table->text('feedback')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['clinic_id', 'session_id', 'user_id'], 'hrm_training_evaluations_unique');
            $table->index(['clinic_id', 'session_id'], 'hrm_training_evaluations_session_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hrm_training_evaluations');
    }
};

