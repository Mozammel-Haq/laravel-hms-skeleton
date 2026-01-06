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
        Schema::create('beds', function (Blueprint $table) {
            $table->id();

            $table->foreignId('room_id')->constrained()->restrictOnDelete();
            $table->foreignId('clinic_id')->constrained()->restrictOnDelete();
            $table->string('bed_number');
            $table->enum('status', ['available', 'occupied', 'maintenance'])->default('available');

            $table->timestamps();

            $table->unique(['room_id', 'bed_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beds');
    }
};
