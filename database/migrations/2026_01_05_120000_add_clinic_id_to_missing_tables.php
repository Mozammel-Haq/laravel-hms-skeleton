<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'invoice_items',
            'lab_test_results',
            'prescription_items',
            'appointment_status_logs',
            'inpatient_rounds',
            'inpatient_services',
            'nursing_notes',
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (!Schema::hasColumn($tableName, 'clinic_id')) {
                    $table->foreignId('clinic_id')->nullable()->constrained()->restrictOnDelete();
                }
            });
        }
    }

    public function down(): void
    {
        $tables = [
            'invoice_items',
            'lab_test_results',
            'prescription_items',
            'appointment_status_logs',
            'inpatient_rounds',
            'inpatient_services',
            'nursing_notes',
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                if (Schema::hasColumn($table->getTable(), 'clinic_id')) {
                    $table->dropForeign(['clinic_id']);
                    $table->dropColumn('clinic_id');
                }
            });
        }
    }
};
