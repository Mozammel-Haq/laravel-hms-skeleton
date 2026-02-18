<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'salary_structure_id')) {
                $table->foreignId('salary_structure_id')
                    ->nullable()
                    ->constrained('hrm_salary_structures')
                    ->nullOnDelete()
                    ->after('join_date');
            }

            if (! Schema::hasColumn('users', 'basic_salary_override')) {
                $table->decimal('basic_salary_override', 12, 2)
                    ->nullable()
                    ->after('salary_structure_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'salary_structure_id')) {
                $table->dropConstrainedForeignId('salary_structure_id');
            }

            if (Schema::hasColumn('users', 'basic_salary_override')) {
                $table->dropColumn('basic_salary_override');
            }
        });
    }
};

