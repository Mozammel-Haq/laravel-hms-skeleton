<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hrm_leave_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->restrictOnDelete();
            $table->string('name');
            $table->string('code')->nullable();
            $table->decimal('default_days', 5, 2)->default(0);
            $table->boolean('carry_forward')->default(false);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->unique(['clinic_id', 'code']);
            $table->unique(['clinic_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hrm_leave_types');
    }
};

