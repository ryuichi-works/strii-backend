<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rackets', function (Blueprint $table) {
            $table->integer('release_year')->length(4)->unsigned()->nullable()->default(null)->after('balance');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rackets', function (Blueprint $table) {
            $table->dropColumn('release_year');
        });
    }
};
