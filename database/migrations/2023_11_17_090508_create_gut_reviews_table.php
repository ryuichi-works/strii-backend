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
        Schema::create('gut_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained('my_equipments');
            $table->float('match_rate', 2, 1)->default(3.0);
            $table->float('pysical_durability', 2, 1)->default(3.0);
            $table->float('performance_durability', 2, 1)->default(3.0);
            $table->text('review', 500);
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
        Schema::dropIfExists('gut_reviews');
    }
};
