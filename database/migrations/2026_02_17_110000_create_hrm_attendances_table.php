<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hrm_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->restrictOnDelete();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->date('attendance_date');
            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();
            $table->decimal('worked_hours', 5, 2)->default(0);
            $table->string('status')->default('present'); // present, absent, leave, half-day, holiday
            $table->boolean('is_late')->default(false);
            $table->boolean('is_early_exit')->default(false);
            $table->string('source')->default('manual'); // manual, biometric, api
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->unique(['clinic_id', 'user_id', 'attendance_date'], 'hrm_attendances_unique_user_day');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hrm_attendances');
    }
};

