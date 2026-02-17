<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hrm_holidays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->restrictOnDelete();
            $table->date('date');
            $table->string('name');
            $table->string('type')->default('public');
            $table->boolean('is_full_day')->default(true);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->unique(['clinic_id', 'date'], 'hrm_holidays_unique_clinic_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hrm_holidays');
    }
};

