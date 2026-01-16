<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('rooms')) {
            if (!Schema::hasColumn('rooms', 'clinic_id')) {
                Schema::table('rooms', function (Blueprint $table) {
                    $table->foreignId('clinic_id')->after('ward_id')->nullable()->constrained()->restrictOnDelete();
                });
            }

            $rooms = DB::table('rooms')
                ->join('wards', 'rooms.ward_id', '=', 'wards.id')
                ->whereNull('rooms.clinic_id')
                ->select('rooms.id', 'wards.clinic_id')
                ->get();

            foreach ($rooms as $room) {
                DB::table('rooms')
                    ->where('id', $room->id)
                    ->update(['clinic_id' => $room->clinic_id]);
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('rooms') && Schema::hasColumn('rooms', 'clinic_id')) {
            Schema::table('rooms', function (Blueprint $table) {
                $table->dropForeign(['clinic_id']);
                $table->dropColumn('clinic_id');
            });
        }
    }
};
