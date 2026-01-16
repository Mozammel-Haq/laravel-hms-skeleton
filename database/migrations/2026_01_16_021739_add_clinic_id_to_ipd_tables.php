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
        Schema::table('bed_assignments', function (Blueprint $table) {
            if (!Schema::hasColumn('bed_assignments', 'clinic_id')) {
                $table->foreignId('clinic_id')->after('id')->constrained()->restrictOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bed_assignments', function (Blueprint $table) {
            if (Schema::hasColumn('bed_assignments', 'clinic_id')) {
                $table->dropForeign(['clinic_id']);
                $table->dropColumn('clinic_id');
            }
        });
    }
};
