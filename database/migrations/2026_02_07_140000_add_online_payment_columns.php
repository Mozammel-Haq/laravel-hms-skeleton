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
        Schema::table('admission_deposits', function (Blueprint $table) {
            if (! Schema::hasColumn('admission_deposits', 'status')) {
                $table->string('status')->default('success')->after('amount'); // Default success for existing cash payments
            }
            if (! Schema::hasColumn('admission_deposits', 'gateway')) {
                $table->string('gateway')->nullable()->after('payment_method');
            }
            if (! Schema::hasColumn('admission_deposits', 'gateway_transaction_id')) {
                $table->string('gateway_transaction_id')->nullable()->after('gateway');
            }
        });

        Schema::table('payments', function (Blueprint $table) {
            if (! Schema::hasColumn('payments', 'status')) {
                $table->string('status')->default('success')->after('amount'); // Default success for existing cash payments
            }
            if (! Schema::hasColumn('payments', 'gateway')) {
                $table->string('gateway')->nullable()->after('payment_method');
            }
            if (! Schema::hasColumn('payments', 'gateway_transaction_id')) {
                $table->string('gateway_transaction_id')->nullable()->after('gateway');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admission_deposits', function (Blueprint $table) {
            $table->dropColumn(['status', 'gateway', 'gateway_transaction_id']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['status', 'gateway', 'gateway_transaction_id']);
        });
    }
};
