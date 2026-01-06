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
        // 1. Admissions: Add softDeletes
        if (Schema::hasTable('admissions') && !Schema::hasColumn('admissions', 'deleted_at')) {
            Schema::table('admissions', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // 2. Appointments: Add softDeletes
        if (Schema::hasTable('appointments') && !Schema::hasColumn('appointments', 'deleted_at')) {
            Schema::table('appointments', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // 3. Lab Test Orders: Add clinic_id, timestamps, softDeletes
        if (Schema::hasTable('lab_test_orders')) {
            Schema::table('lab_test_orders', function (Blueprint $table) {
                if (!Schema::hasColumn('lab_test_orders', 'clinic_id')) {
                    $table->foreignId('clinic_id')->nullable()->constrained()->restrictOnDelete();
                }
                if (!Schema::hasColumn('lab_test_orders', 'created_at')) {
                    $table->timestamps();
                }
                if (!Schema::hasColumn('lab_test_orders', 'deleted_at')) {
                    $table->softDeletes();
                }
            });
        }

        // 4. Doctors: Add softDeletes only (clinic_id is managed via doctor_clinic pivot assignments)
        if (Schema::hasTable('doctors') && !Schema::hasColumn('doctors', 'deleted_at')) {
            Schema::table('doctors', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('admissions')) {
            Schema::table('admissions', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
        if (Schema::hasTable('appointments')) {
            Schema::table('appointments', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
        if (Schema::hasTable('lab_test_orders')) {
            Schema::table('lab_test_orders', function (Blueprint $table) {
                $table->dropForeign(['clinic_id']);
                $table->dropColumn('clinic_id');
                $table->dropTimestamps();
                $table->dropSoftDeletes();
            });
        }
        if (Schema::hasTable('doctors')) {
            Schema::table('doctors', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
};
