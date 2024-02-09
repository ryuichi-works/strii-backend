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
            $table->foreignId('posting_user_id')->nullable()->constrained('users');
            $table->foreignId('series_id')->nullable()->constrained('racket_series');
            $table->integer('head_size')->nullable()->unsigned();
            $table->string('pattern')->default('');
            $table->integer('weight')->nullable()->unsigned();
            $table->integer('balance')->nullable()->unsigned();
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
            $table->dropForeign('rackets_posting_user_id_foreign');
            $table->dropColumn('posting_user_id');

            $table->dropForeign('rackets_series_id_foreign');
            $table->dropColumn('series_id');

            $table->dropColumn('head_size');
            $table->dropColumn('weight');
            $table->dropColumn('balance');
        });
    }
};
