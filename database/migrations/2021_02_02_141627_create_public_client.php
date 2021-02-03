<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicClient extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('public-client', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('caste');
            $table->string('gender');
            $table->string('date_of_birth');
            $table->tinyInteger('age');
            $table->string('phone');
            $table->string('nationality');
            $table->string('identity_no');
            $table->string('occupation');
            $table->integer('province_id');
            $table->integer('district_id');
            $table->integer('municipality_id');
            $table->string('ward');
            $table->string('tole');
            $table->string('email_address');
            $table->string('first_vaccinated_date');
            $table->string('second_vaccinated_date');
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
        Schema::dropIfExists('public-client');
    }
}
