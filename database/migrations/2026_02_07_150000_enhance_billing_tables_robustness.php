<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add clinic_id to invoice_items
        Schema::table('invoice_items', function (Blueprint $table) {
            if (!Schema::hasColumn('invoice_items', 'clinic_id')) {
                $table->foreignId('clinic_id')->nullable()->after('id')->constrained()->restrictOnDelete();
            }
        });

        // Populate clinic_id from invoices
        $prefix = DB::getTablePrefix();
        if (DB::getDriverName() === 'sqlite') {
             DB::statement("UPDATE {$prefix}invoice_items SET clinic_id = (SELECT clinic_id FROM {$prefix}invoices WHERE {$prefix}invoices.id = {$prefix}invoice_items.invoice_id) WHERE clinic_id IS NULL");
        } else {
             // MySQL/Postgres syntax
             DB::statement("UPDATE {$prefix}invoice_items JOIN {$prefix}invoices ON {$prefix}invoice_items.invoice_id = {$prefix}invoices.id SET {$prefix}invoice_items.clinic_id = {$prefix}invoices.clinic_id WHERE {$prefix}invoice_items.clinic_id IS NULL");
        }

        // 2. Change payment_method to string in payments table
        // Skip for SQLite as it usually treats enums as text (or check constraints which are hard to remove without dropping table)
        if (DB::getDriverName() !== 'sqlite') {
            $prefix = DB::getTablePrefix();
            // Modify payment_method to VARCHAR to allow 'stripe', 'sslcommerz', etc.
            // Original enum: ['cash', 'card', 'mobile_banking', 'bank_transfer']
            DB::statement("ALTER TABLE {$prefix}payments MODIFY payment_method VARCHAR(255) NOT NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_items', function (Blueprint $table) {
            if (Schema::hasColumn('invoice_items', 'clinic_id')) {
                // For SQLite, dropping foreign keys is limited, but we can try dropping the column
                if (DB::getDriverName() !== 'sqlite') {
                    $table->dropForeign(['clinic_id']);
                }
                $table->dropColumn('clinic_id');
            }
        });

        if (DB::getDriverName() !== 'sqlite') {
             $prefix = DB::getTablePrefix();
             // Attempt to revert to enum (might fail if data is incompatible)
             try {
                 DB::statement("ALTER TABLE {$prefix}payments MODIFY payment_method ENUM('cash', 'card', 'mobile_banking', 'bank_transfer') NOT NULL");
             } catch (\Exception $e) {
                 // Ignore if data is incompatible
             }
        }
    }
};
