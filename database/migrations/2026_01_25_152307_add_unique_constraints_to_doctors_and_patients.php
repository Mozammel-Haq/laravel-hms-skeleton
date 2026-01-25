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
        Schema::table('doctors', function (Blueprint $table) {
            $table->unique('license_number');
            $table->unique('registration_number');
        });

        Schema::table('patients', function (Blueprint $table) {
            $table->unique('nid_number');
            $table->unique('birth_certificate_number');
            $table->unique('passport_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropUnique(['license_number']);
            $table->dropUnique(['registration_number']);
        });

        Schema::table('patients', function (Blueprint $table) {
            $table->dropUnique(['nid_number']);
            $table->dropUnique(['birth_certificate_number']);
            $table->dropUnique(['passport_number']);
        });
    }
};
