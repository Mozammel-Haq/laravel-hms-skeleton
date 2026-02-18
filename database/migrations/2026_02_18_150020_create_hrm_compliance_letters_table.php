<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hrm_compliance_letters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->restrictOnDelete();
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('category')->nullable();
            $table->string('subject')->nullable();
            $table->text('body')->nullable();
            $table->enum('status', ['draft', 'active', 'archived'])->default('draft');
            $table->timestamps();

            $table->index(['clinic_id', 'status'], 'hrm_compliance_letters_status_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hrm_compliance_letters');
    }
};

