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
        Schema::create('tennis_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('gender', 15)->default('未設定');
            $table->foreignId('my_racket_id')->nullable()->constrained('rackets');
            $table->string('grip_form', 20)->default('未設定');
            $table->string('height', 20)->default('未設定');
            $table->string('age', 20)->default('未設定');
            $table->string('physique', 20)->default('未設定');
            $table->integer('experience_period')->unsigned()->default(0);
            $table->string('frequency', 20)->default('未設定');
            $table->string('play_style', 20)->default('未設定');
            $table->string('favarit_shot', 20)->default('未設定');
            $table->string('weak_shot', 20);
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
        Schema::dropIfExists('tennis_profiles');
    }
};
