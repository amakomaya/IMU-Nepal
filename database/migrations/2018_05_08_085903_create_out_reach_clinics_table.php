<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOutReachClinicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('out_reach_clinics', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('province_id');
            $table->integer('district_id');
            $table->integer('municipality_id');
            $table->integer('ward_no');
            $table->char('hp_code', 16);
            $table->text('address');
            $table->string('phone')->nullable();
            $table->char('longitude',16)->nullable();
            $table->char('lattitude',16)->nullable();
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
        Schema::dropIfExists('out_reach_clinics');
    }
}
