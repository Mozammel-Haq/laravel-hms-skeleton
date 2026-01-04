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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->foreignId('primary_department_id')->constrained('departments')->restrictOnDelete();
            $table->string('registration_number')->nullable();
            $table->string('license_number')->nullable();
            $table->string('specialization');
            $table->unsignedInteger('experience_years')->default(0);
            $table->string('gender')->nullable();
            $table->string('blood_group')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->decimal('consultation_fee', 10, 2)->nullable();
            $table->text('biography')->nullable();
            $table->string('profile_photo')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
