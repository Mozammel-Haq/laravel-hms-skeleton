<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patient_complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->restrictOnDelete();
            $table->string('name')->unique();
        });

        Schema::create('prescription_complaint', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prescription_id')->constrained()->cascadeOnDelete();
            $table->foreignId('complaint_id')->constrained('patient_complaints')->cascadeOnDelete();
            $table->unique(['prescription_id', 'complaint_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prescription_complaint');
        Schema::dropIfExists('patient_complaints');
    }
};
