<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hrm_compliance_policies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->restrictOnDelete();
            $table->string('title');
            $table->string('category')->nullable();
            $table->date('effective_from')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['draft', 'active', 'archived'])->default('draft');
            $table->timestamps();

            $table->index(['clinic_id', 'status'], 'hrm_compliance_policies_status_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hrm_compliance_policies');
    }
};

