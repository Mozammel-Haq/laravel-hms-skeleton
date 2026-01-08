<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('visits') && !Schema::hasColumn('visits', 'consultation_id')) {
            Schema::table('visits', function (Blueprint $table) {
                $table->foreignId('consultation_id')
                    ->nullable()
                    ->constrained('consultations')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('visits') && Schema::hasColumn('visits', 'consultation_id')) {
            Schema::table('visits', function (Blueprint $table) {
                $table->dropForeign(['consultation_id']);
                $table->dropColumn('consultation_id');
            });
        }
    }
};
