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
        // For MySQL/PostgreSQL/SQLite compatibility, modifying enum can be complex.
        // Since we are in dev/prototype phase, we can use a raw statement or Doctrine if installed.
        // But simpler approach for Laravel migration on enum is usually to modify the column.

        // Note: SQLite doesn't support modifying columns easily.
        // Assuming we are running on a standard SQL environment or using simple types.
        // If SQLite, we might ignore or just change type to string.

        // Let's try standard Schema change.
        Schema::table('appointments', function (Blueprint $table) {
            $table->string('status')->change(); // Temporarily change to string to allow any value
        });

        // Or re-define enum if DB supports it.
        // For this task, changing to string provides flexibility and is safe.
        // We will enforce values in model/application logic.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert is risky if we have data not fitting the old enum.
        // We'll leave it as string.
    }
};
