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
        Schema::table('gut_images', function (Blueprint $table) {
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
        Schema::table('gut_images', function (Blueprint $table) {
            $table->dropForeign('gut_images_maker_id_foreign');
            $table->dropColumn('maker_id');
        });
    }
};
