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
        Schema::table('guts', function (Blueprint $table) {
            $table->string('guage', 30)->nullable()->default('')->after('need_posting_image');
            $table->string('category', 30)->nullable()->default('')->after('guage');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('guts', function (Blueprint $table) {
            $table->dropColumn('guage');
            $table->dropColumn('category');
        });
    }
};
