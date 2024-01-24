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
        Schema::table('racket_images', function (Blueprint $table) {
            $table->foreignId('maker_id')->nullable()->constrained('makers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('racket_images', function (Blueprint $table) {
            $table->dropForeign('racket_images_maker_id_foreign');
            $table->dropColumn('maker_id');
        });
    }
};
