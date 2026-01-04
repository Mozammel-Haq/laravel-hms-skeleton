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
        Schema::create('inpatient_rounds', function (Blueprint $table) {
    $table->id();

    $table->foreignId('admission_id')->constrained()->restrictOnDelete();
    $table->foreignId('doctor_id')->constrained()->restrictOnDelete();

    $table->text('notes')->nullable();
    $table->date('round_date');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inpatient_rounds');
    }
};
