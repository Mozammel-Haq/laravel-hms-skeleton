<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add 'online' and 'in_person' to the booking_source enum
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE lara_appointments MODIFY COLUMN booking_source ENUM('reception', 'patient_portal', 'online', 'in_person') DEFAULT 'reception'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum values
        // WARNING: This will truncate data if 'online' or 'in_person' values exist
        DB::statement("ALTER TABLE lara_appointments MODIFY COLUMN booking_source ENUM('reception', 'patient_portal') DEFAULT 'reception'");
    }
};
