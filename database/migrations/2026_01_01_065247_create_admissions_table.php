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
        Schema::create('admissions', function (Blueprint $table) {
    $table->id();

    $table->foreignId('clinic_id')->constrained()->restrictOnDelete();
    $table->foreignId('patient_id')->constrained()->restrictOnDelete();
    $table->foreignId('admitting_doctor_id')->constrained('doctors')->restrictOnDelete();

    $table->dateTime('admission_date');
    $table->string('admission_reason');

    $table->foreignId('current_bed_id')->nullable()->constrained('beds')->nullOnDelete();

    $table->enum('status', ['admitted', 'transferred', 'discharged'])->default('admitted');

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admissions');
    }
};
