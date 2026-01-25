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
        // 1. Rename old column if it hasn't been renamed yet
        if (Schema::hasColumn('doctors', 'specialization') && !Schema::hasColumn('doctors', 'specialization_old')) {
            Schema::table('doctors', function (Blueprint $table) {
                $table->renameColumn('specialization', 'specialization_old');
            });
        }

        // 2. Create new JSON column if it doesn't exist
        if (!Schema::hasColumn('doctors', 'specialization')) {
            Schema::table('doctors', function (Blueprint $table) {
                $table->json('specialization')->nullable()->after('license_number');
            });
        }

        // 3. Migrate data: Convert string to JSON array
        // \Illuminate\Support\Facades\DB::statement("UPDATE doctors SET specialization = JSON_ARRAY(specialization_old) WHERE specialization_old IS NOT NULL");
        \Illuminate\Support\Facades\DB::table('doctors')
            ->whereNotNull('specialization_old')
            ->update([
                'specialization' => \Illuminate\Support\Facades\DB::raw('JSON_ARRAY(specialization_old)')
            ]);

        // 4. Drop old column
        if (Schema::hasColumn('doctors', 'specialization_old')) {
            Schema::table('doctors', function (Blueprint $table) {
                $table->dropColumn('specialization_old');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse process
        Schema::table('doctors', function (Blueprint $table) {
            $table->renameColumn('specialization', 'specialization_json');
        });

        Schema::table('doctors', function (Blueprint $table) {
            $table->string('specialization')->nullable()->after('license_number');
        });

        // Take first element of JSON array or just convert to string
        \Illuminate\Support\Facades\DB::statement("UPDATE doctors SET specialization = JSON_UNQUOTE(JSON_EXTRACT(specialization_json, '$[0]')) WHERE specialization_json IS NOT NULL");

        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn('specialization_json');
        });
    }
};
