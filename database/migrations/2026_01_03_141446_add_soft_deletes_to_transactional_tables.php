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
        $tables = [
            'patients',
            'appointments',
            'invoices',
            'admissions',
            'doctors',
            'prescriptions',
            'visits',
            'payments',
            'pharmacy_sales', // Added this as it's transactional
            'lab_test_orders', // Added this as it's transactional
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && !Schema::hasColumn($table, 'deleted_at')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->softDeletes();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'patients',
            'appointments',
            'invoices',
            'admissions',
            'doctors',
            'prescriptions',
            'visits',
            'payments',
            'pharmacy_sales',
            'lab_test_orders',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'deleted_at')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->dropSoftDeletes();
                });
            }
        }
    }
};
