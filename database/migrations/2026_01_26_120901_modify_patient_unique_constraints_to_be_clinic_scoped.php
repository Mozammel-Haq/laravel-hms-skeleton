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
        Schema::table('patients', function (Blueprint $table) {
            // Drop global unique constraints
            $table->dropUnique(['nid_number']);
            $table->dropUnique(['birth_certificate_number']);
            $table->dropUnique(['passport_number']);

            // Add clinic-scoped unique constraints
            $table->unique(['clinic_id', 'nid_number']);
            $table->unique(['clinic_id', 'birth_certificate_number']);
            $table->unique(['clinic_id', 'passport_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            // Drop clinic-scoped unique constraints
            $table->dropUnique(['clinic_id', 'nid_number']);
            $table->dropUnique(['clinic_id', 'birth_certificate_number']);
            $table->dropUnique(['clinic_id', 'passport_number']);

            // Restore global unique constraints
            $table->unique('nid_number');
            $table->unique('birth_certificate_number');
            $table->unique('passport_number');
        });
    }
};
