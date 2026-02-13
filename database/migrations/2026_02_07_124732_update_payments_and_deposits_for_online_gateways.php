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
        // 1. Update payments table
        Schema::table('payments', function (Blueprint $table) {
            // Modify existing columns
            $table->unsignedBigInteger('received_by')->nullable()->change(); // Nullable for online payments

            // Add new columns
            if (! Schema::hasColumn('payments', 'gateway')) {
                $table->string('gateway')->nullable()->after('payment_method'); // stripe, sslcommerz
            }
            if (! Schema::hasColumn('payments', 'gateway_transaction_id')) {
                $table->string('gateway_transaction_id')->nullable()->after('gateway');
            }
            if (! Schema::hasColumn('payments', 'status')) {
                $table->enum('status', ['pending', 'success', 'failed'])->default('success')->after('amount');
            }
        });

        // 2. Update admission_deposits table
        Schema::table('admission_deposits', function (Blueprint $table) {
            // Modify existing columns
            $table->unsignedBigInteger('received_by')->nullable()->change();

            // Add new columns
            if (! Schema::hasColumn('admission_deposits', 'gateway')) {
                $table->string('gateway')->nullable()->after('payment_method');
            }
            if (! Schema::hasColumn('admission_deposits', 'gateway_transaction_id')) {
                $table->string('gateway_transaction_id')->nullable()->after('gateway');
            }
            if (! Schema::hasColumn('admission_deposits', 'status')) {
                $table->enum('status', ['pending', 'success', 'failed'])->default('success')->after('amount');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->unsignedBigInteger('received_by')->nullable(false)->change();
            $table->dropColumn(['gateway', 'gateway_transaction_id', 'status']);
        });

        Schema::table('admission_deposits', function (Blueprint $table) {
            $table->unsignedBigInteger('received_by')->nullable(false)->change();
            $table->dropColumn(['gateway', 'gateway_transaction_id', 'status']);
        });
    }
};
