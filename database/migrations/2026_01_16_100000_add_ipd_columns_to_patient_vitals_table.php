<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('patient_vitals')) {
            Schema::table('patient_vitals', function (Blueprint $table) {
                if (!Schema::hasColumn('patient_vitals', 'admission_id')) {
                    $table->foreignId('admission_id')->nullable()->after('visit_id')->constrained()->nullOnDelete();
                }
                if (!Schema::hasColumn('patient_vitals', 'inpatient_round_id')) {
                    $table->foreignId('inpatient_round_id')->nullable()->after('admission_id')->constrained()->nullOnDelete();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('patient_vitals')) {
            Schema::table('patient_vitals', function (Blueprint $table) {
                if (Schema::hasColumn('patient_vitals', 'inpatient_round_id')) {
                    $table->dropForeign(['inpatient_round_id']);
                    $table->dropColumn('inpatient_round_id');
                }
                if (Schema::hasColumn('patient_vitals', 'admission_id')) {
                    $table->dropForeign(['admission_id']);
                    $table->dropColumn('admission_id');
                }
            });
        }
    }
};

