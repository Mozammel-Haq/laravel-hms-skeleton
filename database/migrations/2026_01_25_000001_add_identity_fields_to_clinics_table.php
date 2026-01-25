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
        Schema::table('clinics', function (Blueprint $table) {
            $table->string('nid_number', 32)->nullable()->after('registration_number');
            $table->string('birth_certificate_number', 32)->nullable()->after('nid_number');
            $table->string('passport_number', 32)->nullable()->after('birth_certificate_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->dropColumn(['nid_number', 'birth_certificate_number', 'passport_number']);
        });
    }
};
