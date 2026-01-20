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
        Schema::table('lab_test_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('lab_test_orders', 'lab_test_id')) {
                $table->foreignId('lab_test_id')->nullable()->after('patient_id')->constrained()->restrictOnDelete();
            }
            // Make appointment_id nullable
            $table->foreignId('appointment_id')->nullable()->change();
            // Make doctor_id nullable
            $table->foreignId('doctor_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lab_test_orders', function (Blueprint $table) {
            if (Schema::hasColumn('lab_test_orders', 'lab_test_id')) {
                $table->dropForeign(['lab_test_id']);
                $table->dropColumn('lab_test_id');
            }
            // We cannot easily revert nullable changes without knowing original state perfectly,
            // but we can assume they were required.
            // However, reverting to required might fail if nulls were introduced.
            // Leaving them nullable is safer for down() or just doing nothing.
        });
    }
};
