<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            if (!Schema::hasColumn('patients', 'nid_number')) {
                $table->string('nid_number')->nullable()->after('email');
            }
            if (!Schema::hasColumn('patients', 'birth_certificate_number')) {
                $table->string('birth_certificate_number')->nullable()->after('nid_number');
            }
            if (!Schema::hasColumn('patients', 'passport_number')) {
                $table->string('passport_number')->nullable()->after('birth_certificate_number');
            }
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            if (Schema::hasColumn('patients', 'passport_number')) {
                $table->dropColumn('passport_number');
            }
            if (Schema::hasColumn('patients', 'birth_certificate_number')) {
                $table->dropColumn('birth_certificate_number');
            }
            if (Schema::hasColumn('patients', 'nid_number')) {
                $table->dropColumn('nid_number');
            }
        });
    }
};
