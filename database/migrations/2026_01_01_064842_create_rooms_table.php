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
        Schema::create('rooms', function (Blueprint $table) {
    $table->id();

    $table->foreignId('ward_id')->constrained()->restrictOnDelete();

    $table->string('room_number');
    $table->string('room_type');
    $table->decimal('daily_rate', 10, 2);
    $table->enum('status', ['available', 'occupied', 'maintenance'])->default('available');

    $table->timestamps();

    $table->unique(['ward_id', 'room_number']);
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
