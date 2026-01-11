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
        Schema::table('doctors', function (Blueprint $table) {
            $table->decimal('follow_up_fee', 10, 2)->nullable()->after('consultation_fee');
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->decimal('fee', 10, 2)->nullable()->after('status');
            $table->enum('visit_type', ['new', 'follow_up'])->default('new')->after('fee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn('follow_up_fee');
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['fee', 'visit_type']);
        });
    }
};
