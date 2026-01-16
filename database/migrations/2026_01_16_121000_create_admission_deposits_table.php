<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admission_deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->restrictOnDelete();
            $table->foreignId('admission_id')->constrained()->restrictOnDelete();
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['cash', 'card', 'bank_transfer'])->default('cash');
            $table->string('transaction_reference')->nullable();
            $table->dateTime('received_at')->nullable();
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admission_deposits');
    }
};

