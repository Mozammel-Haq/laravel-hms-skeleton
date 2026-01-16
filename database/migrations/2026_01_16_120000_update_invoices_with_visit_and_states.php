<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('invoices', 'visit_id')) {
                $table->foreignId('visit_id')->nullable()->after('appointment_id')->constrained()->nullOnDelete();
            }
            if (!Schema::hasColumn('invoices', 'admission_id')) {
                // Ensure admission_id exists; skip if already added by prior migration
                $table->foreignId('admission_id')->nullable()->after('visit_id')->constrained()->nullOnDelete();
            }
            if (!Schema::hasColumn('invoices', 'invoice_type')) {
                $table->string('invoice_type')->nullable()->after('invoice_number');
            }
            if (!Schema::hasColumn('invoices', 'issued_at')) {
                $table->dateTime('issued_at')->nullable()->after('status');
            }
            if (!Schema::hasColumn('invoices', 'state')) {
                $table->enum('state', ['draft', 'finalized'])->default('draft')->after('status');
            }
            if (!Schema::hasColumn('invoices', 'finalized_at')) {
                $table->dateTime('finalized_at')->nullable()->after('issued_at');
            }
            if (!Schema::hasColumn('invoices', 'finalized_by')) {
                $table->foreignId('finalized_by')->nullable()->after('finalized_at')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('invoices', 'created_by')) {
                $table->foreignId('created_by')->nullable()->after('finalized_by')->constrained('users')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            if (Schema::hasColumn('invoices', 'visit_id')) {
                $table->dropForeign(['visit_id']);
                $table->dropColumn('visit_id');
            }
            if (Schema::hasColumn('invoices', 'admission_id')) {
                $table->dropForeign(['admission_id']);
                $table->dropColumn('admission_id');
            }
            if (Schema::hasColumn('invoices', 'invoice_type')) {
                $table->dropColumn('invoice_type');
            }
            if (Schema::hasColumn('invoices', 'issued_at')) {
                $table->dropColumn('issued_at');
            }
            if (Schema::hasColumn('invoices', 'state')) {
                $table->dropColumn('state');
            }
            if (Schema::hasColumn('invoices', 'finalized_at')) {
                $table->dropColumn('finalized_at');
            }
            if (Schema::hasColumn('invoices', 'finalized_by')) {
                $table->dropForeign(['finalized_by']);
                $table->dropColumn('finalized_by');
            }
            if (Schema::hasColumn('invoices', 'created_by')) {
                $table->dropForeign(['created_by']);
                $table->dropColumn('created_by');
            }
        });
    }
};

