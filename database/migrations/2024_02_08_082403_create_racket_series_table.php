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
        Schema::create('racket_series', function (Blueprint $table) {
            $table->id();
            $table->string('name_ja', 30)->nullable(false);
            $table->string('name_en', 30)->nullable()->default("");
            $table->foreignId('maker_id')->constrained('makers');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('racket_series');
    }
};
