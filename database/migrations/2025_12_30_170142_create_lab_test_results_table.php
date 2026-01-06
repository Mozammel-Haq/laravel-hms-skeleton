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
        Schema::create('lab_test_results', function (Blueprint $table) {
            $table->id();

            $table->foreignId('lab_test_order_id')->constrained()->restrictOnDelete();
            $table->foreignId('lab_test_id')->constrained()->restrictOnDelete();

            $table->string('result_value')->nullable();
            $table->string('unit')->nullable();
            $table->string('reference_range')->nullable();
            $table->text('remarks')->nullable();

            $table->foreignId('reported_by')->constrained('users')->restrictOnDelete();
            $table->timestamp('reported_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_test_results');
    }
};
