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
       Schema::create('nursing_notes', function (Blueprint $table) {
    $table->id();

    $table->foreignId('admission_id')->constrained()->restrictOnDelete();
    $table->foreignId('nurse_id')->constrained('users')->restrictOnDelete();

    $table->text('notes');
    $table->dateTime('recorded_at');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nursing_notes');
    }
};
