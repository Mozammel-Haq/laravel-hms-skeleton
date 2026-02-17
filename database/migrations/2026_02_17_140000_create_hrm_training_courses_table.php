<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hrm_training_courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->restrictOnDelete();
            $table->string('title');
            $table->string('code')->nullable();
            $table->string('category')->nullable();
            $table->string('target_role')->nullable();
            $table->enum('mode', ['online', 'classroom', 'blended'])->default('classroom');
            $table->unsignedInteger('duration_hours')->default(0);
            $table->text('description')->nullable();
            $table->enum('status', ['draft', 'active', 'inactive', 'archived'])->default('draft');
            $table->timestamps();

            $table->unique(['clinic_id', 'code']);
            $table->index(['clinic_id', 'status'], 'hrm_training_courses_status_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hrm_training_courses');
    }
};

