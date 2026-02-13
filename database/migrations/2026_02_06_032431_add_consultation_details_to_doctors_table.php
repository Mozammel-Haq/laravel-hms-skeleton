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
            if (! Schema::hasColumn('doctors', 'consultation_room_number')) {
                $table->string('consultation_room_number')->nullable()->after('profile_photo');
            }
            if (! Schema::hasColumn('doctors', 'consultation_floor')) {
                $table->string('consultation_floor')->nullable()->after('consultation_room_number');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            if (Schema::hasColumn('doctors', 'consultation_room_number')) {
                $table->dropColumn('consultation_room_number');
            }
            if (Schema::hasColumn('doctors', 'consultation_floor')) {
                $table->dropColumn('consultation_floor');
            }
        });
    }
};
