<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lab_test_results', function (Blueprint $table) {
            $table->string('pdf_path')->nullable()->after('remarks');
        });
    }

    public function down(): void
    {
        Schema::table('lab_test_results', function (Blueprint $table) {
            $table->dropColumn('pdf_path');
        });
    }
};
