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
        Schema::table('pharmacy_sale_items', function (Blueprint $table) {
            $table->decimal('unit_cost', 10, 2)->after('unit_price')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pharmacy_sale_items', function (Blueprint $table) {
            $table->dropColumn('unit_cost');
        });
    }
};
