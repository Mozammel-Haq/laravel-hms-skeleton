<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hrm_timesheets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->restrictOnDelete();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->date('date');
            $table->decimal('hours', 5, 2)->default(0);
            $table->string('project')->nullable();
            $table->string('task')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected'])->default('approved');
            $table->timestamps();
            $table->index(['clinic_id', 'user_id', 'date'], 'hrm_timesheets_user_day_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hrm_timesheets');
    }
};

