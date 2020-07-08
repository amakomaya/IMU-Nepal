<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVialDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vial_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('hp_code');
            $table->string('vaccine_name');
            $table->integer('maximum_doses');
            $table->string('vial_damaged_reason');
            $table->string('vial_image');
            $table->integer('vial_used_doses');
            $table->integer('vial_wastage_doses');
            $table->dateTime('vial_opened_date');
            $table->integer('status');
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
        Schema::dropIfExists('vial_details');
    }
}
