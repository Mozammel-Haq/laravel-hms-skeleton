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
        Schema::table('doctor_schedule_exceptions', function (Blueprint $table) {
            $table->time('start_time')->nullable()->after('is_available');
            $table->time('end_time')->nullable()->after('start_time');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctor_schedule_exceptions', function (Blueprint $table) {
            $table->dropColumn(['start_time', 'end_time', 'status']);
        });
    }
};
