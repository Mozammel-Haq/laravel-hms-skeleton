<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hrm_overtimes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->restrictOnDelete();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->date('date');
            $table->decimal('hours', 5, 2)->default(0);
            $table->decimal('multiplier', 4, 2)->default(1.5);
            $table->string('reason')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved');
            $table->timestamps();
            $table->index(['clinic_id', 'user_id', 'date'], 'hrm_overtimes_user_day_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hrm_overtimes');
    }
};

