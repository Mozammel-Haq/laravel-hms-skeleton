<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('patient_vitals', function (Blueprint $table) {
            $table->id();

            $table->foreignId('patient_id')->constrained()->restrictOnDelete();
            $table->foreignId('visit_id')->constrained()->cascadeOnDelete();

            $table->string('blood_pressure')->nullable();
            $table->integer('heart_rate')->nullable();
            $table->decimal('temperature', 4, 1)->nullable();
            $table->decimal('spo2', 5, 2)->nullable();
            $table->integer('respiratory_rate')->nullable();
            $table->decimal('weight', 5, 2)->nullable();
            $table->decimal('height', 5, 2)->nullable();
            $table->decimal('bmi', 5, 2)->nullable();

            $table->foreignId('recorded_by')->constrained('users')->restrictOnDelete();
            $table->timestamp('recorded_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vitals');
    }
};
