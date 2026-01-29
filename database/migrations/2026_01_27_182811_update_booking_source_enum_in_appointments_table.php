<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            //
        });
        DB::statement("ALTER TABLE lara_appointments MODIFY COLUMN booking_source ENUM('reception', 'patient_portal', 'online', 'in_person') DEFAULT 'reception'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            //
        });
        // WARNING: This will truncate data if 'online' or 'in_person' values exist
        DB::statement("ALTER TABLE lara_appointments MODIFY COLUMN booking_source ENUM('reception', 'patient_portal') DEFAULT 'reception'");
    }
};
