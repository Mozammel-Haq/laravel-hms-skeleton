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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('clinic_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('action');               // e.g. created, updated, deleted, approved
            $table->string('entity_type');           // e.g. Appointment, Invoice, Admission
            $table->unsignedBigInteger('entity_id'); // polymorphic reference without FK
            $table->ipAddress('ip_address')->nullable();

            $table->timestamp('created_at')->useCurrent();

            $table->index(['entity_type', 'entity_id']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
