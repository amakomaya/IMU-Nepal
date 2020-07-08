<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMunicipalityInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('municipality_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token');
            $table->string('phone')->nullable();
            $table->string('province_id');
            $table->string('district_id');
            $table->string('municipality_id');
            $table->string('office_address');
            $table->char('office_longitude',16)->nullable();
            $table->char('office_lattitude',16)->nullable();
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
        Schema::dropIfExists('municipality_infos');
    }
}
