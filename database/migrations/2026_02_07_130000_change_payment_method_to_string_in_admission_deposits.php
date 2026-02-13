<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Skip for SQLite as it doesn't support MODIFY and treats enums as text anyway
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        $prefix = DB::getTablePrefix();
        // Using raw SQL to avoid doctrine/dbal dependency issues for enum to string conversion
        DB::statement("ALTER TABLE {$prefix}admission_deposits MODIFY payment_method VARCHAR(255) NOT NULL DEFAULT 'cash'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Skip for SQLite
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        $prefix = DB::getTablePrefix();
        // Reverting back to ENUM might fail if there are values not in the enum list
        // So we will just leave it as VARCHAR or try to revert if possible.
        // For safety, we will try to revert to the original enum definition.
        DB::statement("ALTER TABLE {$prefix}admission_deposits MODIFY payment_method ENUM('cash', 'card', 'bank_transfer') NOT NULL DEFAULT 'cash'");
    }
};
