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
        Schema::table('admissions', function (Blueprint $table) {
            if (!Schema::hasColumn('admissions', 'discharge_recommended')) {
                $table->boolean('discharge_recommended')->default(false);
            }
            if (!Schema::hasColumn('admissions', 'discharge_recommended_by')) {
                $table->foreignId('discharge_recommended_by')->nullable()->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('admissions', 'discharged_by')) {
                $table->foreignId('discharged_by')->nullable()->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('admissions', 'discharge_date')) {
                $table->dateTime('discharge_date')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admissions', function (Blueprint $table) {
            $table->dropForeign(['discharge_recommended_by']);
            $table->dropForeign(['discharged_by']);
            $table->dropColumn([
                'discharge_recommended',
                'discharge_recommended_by',
                'discharged_by',
                'discharge_date',
            ]);
        });
    }
};
