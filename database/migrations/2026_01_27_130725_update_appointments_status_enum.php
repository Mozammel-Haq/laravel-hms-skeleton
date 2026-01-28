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
        // We need to modify the enum column.
        // Since Laravel's Schema builder doesn't support changing enum values directly easily in all DB drivers without raw SQL or doctrine/dbal,
        // and assuming MySQL/MariaDB which is common:

        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE lara_appointments MODIFY COLUMN status ENUM('pending', 'arrived', 'confirmed', 'completed', 'cancelled', 'noshow') DEFAULT 'pending'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original statuses if needed. Original: ['pending', 'confirmed', 'cancelled', 'completed']
        // Note: Data with 'arrived' or 'noshow' might be lost or need mapping if reverted.
        // For safety, we just keep the superset or revert to a safe subset.

        // This is a destructive operation if data exists with new statuses.
        DB::statement("ALTER TABLE lara_appointments MODIFY COLUMN status ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending'");
    }
};
