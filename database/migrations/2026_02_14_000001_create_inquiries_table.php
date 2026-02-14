<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inquiries', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('clinic_id')->constrained()->onDelete('cascade');
            $blueprint->foreignId('patient_id')->nullable()->constrained()->onDelete('set null');
            $blueprint->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Staff handling the inquiry
            $blueprint->string('subject');
            $blueprint->text('message');
            $blueprint->enum('status', ['pending', 'responded', 'closed'])->default('pending');
            $blueprint->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $blueprint->string('source')->nullable(); // e.g., 'phone', 'email', 'walk-in'
            $blueprint->timestamps();
            $blueprint->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inquiries');
    }
};
