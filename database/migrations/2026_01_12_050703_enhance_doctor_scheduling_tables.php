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
        // Enhance doctor_schedules table
        Schema::table('doctor_schedules', function (Blueprint $table) {
            $table->unsignedTinyInteger('day_of_week')->nullable()->change();
            if (!Schema::hasColumn('doctor_schedules', 'schedule_date')) {
                $table->date('schedule_date')->nullable()->after('day_of_week');
                $table->index('schedule_date');
            }
        });

        // Enhance doctor_schedule_exceptions table
        Schema::table('doctor_schedule_exceptions', function (Blueprint $table) {
            if (Schema::hasColumn('doctor_schedule_exceptions', 'exception_date')) {
                $table->renameColumn('exception_date', 'start_date');
            }
        });

        Schema::table('doctor_schedule_exceptions', function (Blueprint $table) {
            if (!Schema::hasColumn('doctor_schedule_exceptions', 'end_date')) {
                $table->date('end_date')->nullable()->after('start_date');
            }
        });

        // Initialize end_date with start_date for existing records
        DB::table('doctor_schedule_exceptions')
            ->whereNull('end_date')
            ->update(['end_date' => DB::raw('start_date')]);

        // Make end_date required after populating
        Schema::table('doctor_schedule_exceptions', function (Blueprint $table) {
             $table->date('end_date')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctor_schedule_exceptions', function (Blueprint $table) {
            $table->dropColumn('end_date');
            $table->renameColumn('start_date', 'exception_date');
        });

        Schema::table('doctor_schedules', function (Blueprint $table) {
            $table->dropIndex(['schedule_date']);
            $table->dropColumn('schedule_date');
            // We cannot easily revert nullable->change() without knowing original state was not nullable, which it was.
            // But we might have data with nulls now? If we revert, we must ensure no nulls.
            // For now, let's just make it not nullable again if we reverse.
            // But if we have specific dates with null day_of_week, this will fail.
            // So strictly speaking, down method might fail if data exists.
            // We'll leave it as best effort.
            $table->unsignedTinyInteger('day_of_week')->nullable(false)->change();
        });
    }
};
