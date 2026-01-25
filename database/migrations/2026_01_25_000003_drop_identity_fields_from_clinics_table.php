<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('clinics', 'nid_number')) {
            Schema::table('clinics', function (Blueprint $table) {
                $table->dropColumn('nid_number');
            });
        }
        if (Schema::hasColumn('clinics', 'birth_certificate_number')) {
            Schema::table('clinics', function (Blueprint $table) {
                $table->dropColumn('birth_certificate_number');
            });
        }
        if (Schema::hasColumn('clinics', 'passport_number')) {
            Schema::table('clinics', function (Blueprint $table) {
                $table->dropColumn('passport_number');
            });
        }
    }

    public function down(): void
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->string('nid_number', 32)->nullable()->after('registration_number');
            $table->string('birth_certificate_number', 32)->nullable()->after('nid_number');
            $table->string('passport_number', 32)->nullable()->after('birth_certificate_number');
        });
    }
};
