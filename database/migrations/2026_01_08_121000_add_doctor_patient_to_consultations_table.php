<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('consultations')) {
            Schema::table('consultations', function (Blueprint $table) {
                if (!Schema::hasColumn('consultations', 'doctor_id')) {
                    $table->foreignId('doctor_id')->nullable()->constrained()->restrictOnDelete();
                }
                if (!Schema::hasColumn('consultations', 'patient_id')) {
                    $table->foreignId('patient_id')->nullable()->constrained()->restrictOnDelete();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('consultations')) {
            Schema::table('consultations', function (Blueprint $table) {
                if (Schema::hasColumn('consultations', 'doctor_id')) {
                    $table->dropForeign(['doctor_id']);
                    $table->dropColumn('doctor_id');
                }
                if (Schema::hasColumn('consultations', 'patient_id')) {
                    $table->dropForeign(['patient_id']);
                    $table->dropColumn('patient_id');
                }
            });
        }
    }
};
