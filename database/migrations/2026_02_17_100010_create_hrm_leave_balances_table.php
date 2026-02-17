<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hrm_leave_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->restrictOnDelete();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->string('leave_type');
            $table->integer('year');
            $table->decimal('opening_balance', 5, 2)->default(0);
            $table->decimal('accrued', 5, 2)->default(0);
            $table->decimal('used', 5, 2)->default(0);
            $table->decimal('closing_balance', 5, 2)->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->unique(['clinic_id', 'user_id', 'leave_type', 'year'], 'hrm_leave_balances_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hrm_leave_balances');
    }
};

