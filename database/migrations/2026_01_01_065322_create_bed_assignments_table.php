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
        Schema::create('bed_assignments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('admission_id')->constrained()->restrictOnDelete();
            $table->foreignId('bed_id')->constrained()->restrictOnDelete();

            $table->dateTime('assigned_at');
            $table->dateTime('released_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bed_assignments');
    }
};
