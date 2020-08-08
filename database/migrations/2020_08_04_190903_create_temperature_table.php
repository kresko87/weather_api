<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemperatureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temperature', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_place');
            $table->dateTime('datetime');
            $table->double('temperature');
            $table->double('temperature_in_12h');
            $table->double('temperature_predicted_in_past')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('id_place')->references('id')->on('places');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('temperature');
    }
}
