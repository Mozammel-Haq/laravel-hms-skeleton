<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->string('password')->nullable()->after('email');
            $table->string('remember_token', 100)->nullable()->after('password');
            $table->boolean('must_change_password')->default(true)->after('remember_token');
            $table->timestamp('password_changed_at')->nullable()->after('must_change_password');
            $table->timestamp('last_login_at')->nullable()->after('password_changed_at');
        });

        if (!Schema::hasColumn('patients', 'email')) {
            Schema::table('patients', function (Blueprint $table) {
                $table->string('email')->nullable()->after('phone');
            });
        }

        Schema::table('patients', function (Blueprint $table) {
            $table->unique(['clinic_id', 'email'], 'patients_clinic_email_unique');
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropUnique('patients_clinic_email_unique');
            $table->dropColumn(['password', 'remember_token', 'must_change_password', 'password_changed_at', 'last_login_at']);
        });
    }
};
