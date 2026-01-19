<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('beds', function (Blueprint $table) {
            $table->integer('position')->default(0)->after('status');
        });
    }

    public function down()
    {
        Schema::table('beds', function (Blueprint $table) {
            $table->dropColumn('position');
        });
    }
};
