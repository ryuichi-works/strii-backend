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
        Schema::create('my_equipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('user_height', 20)->default('未設定');
            $table->string('user_age')->default('未設定');
            $table->integer('experience_period')->default(0);
            $table->foreignId('racket_id')->constrained('rackets');
            $table->string('stringing_way', 20)->default('single');
            $table->foreignId('main_gut_id')->constrained('guts');
            $table->foreignId('cross_gut_id')->constrained('guts');
            $table->float('main_gut_guage')->default(1.25);
            $table->float('cross_gut_guage')->default(1.25);
            $table->integer('main_gut_tension')->default(50);
            $table->integer('cross_gut_tension')->default(50);
            $table->date('new_gut_date');
            $table->date('change_gut_date')->nullable();
            $table->text('comment', 500);
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
        Schema::dropIfExists('my_equipments');
    }
};
