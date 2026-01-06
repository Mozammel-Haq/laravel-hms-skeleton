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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('invoice_id')->constrained()->restrictOnDelete();

            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['cash', 'card', 'mobile_banking', 'bank_transfer']);
            $table->string('transaction_reference')->nullable();

            $table->timestamp('paid_at');
            $table->foreignId('received_by')->constrained('users')->restrictOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
