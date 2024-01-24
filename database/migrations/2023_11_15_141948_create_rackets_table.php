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
        Schema::create('rackets', function (Blueprint $table) {
            $table->id();
            $table->string('name_ja', 30)->nullable(false);
            $table->string('name_en', 30);
            $table->foreignId('maker_id')->constrained('makers');
            $table->foreignId('image_id')->nullable()->constrained('racket_images');
            $table->boolean('need_posting_image')->nullable(false)->default(true);
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
        Schema::dropIfExists('rackets');
    }
};
