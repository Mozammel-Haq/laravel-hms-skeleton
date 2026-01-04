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
        Schema::create('appointment_status_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('appointment_id')->constrained()->restrictOnDelete();
            $table->string('old_status');
            $table->string('new_status');

            $table->foreignId('changed_by')->constrained('users')->restrictOnDelete();
            $table->text('change_reason')->nullable();
            $table->timestamp('changed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_status_logs');
    }
};
