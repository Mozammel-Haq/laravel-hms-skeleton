<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hrm_leave_types', function (Blueprint $table) {
            if (! Schema::hasColumn('hrm_leave_types', 'is_paid')) {
                $table->boolean('is_paid')->default(true)->after('carry_forward');
            }

            if (! Schema::hasColumn('hrm_leave_types', 'pay_factor')) {
                $table->decimal('pay_factor', 5, 2)->nullable()->after('is_paid');
            }
        });
    }

    public function down(): void
    {
        Schema::table('hrm_leave_types', function (Blueprint $table) {
            if (Schema::hasColumn('hrm_leave_types', 'pay_factor')) {
                $table->dropColumn('pay_factor');
            }

            if (Schema::hasColumn('hrm_leave_types', 'is_paid')) {
                $table->dropColumn('is_paid');
            }
        });
    }
};

