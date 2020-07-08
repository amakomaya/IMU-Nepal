<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVaccineVialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vaccine_vials', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token');
            $table->char('hp_code', 8);
            $table->char('vaccine_name', 16);
            $table->integer('maximum_doses');
            $table->string('vial_image');
            $table->boolean('status');
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
        Schema::dropIfExists('vaccine_vials');
    }
}
