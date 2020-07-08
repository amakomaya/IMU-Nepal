<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWomanRegistrationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('woman_registration', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token');
            $table->string('name');
            $table->integer('age');
            $table->boolean('is_first_time_parent');
            $table->enum('register_as', ['mom', 'dad', 'relative']);
            $table->date('lmp_date_en');
            $table->string('lmp_date_np');
            $table->bigInteger('phone');
            $table->string('email')->nullable();
            $table->integer('district_id');
            $table->integer('municipality_id');
            $table->string('tole')->nullable();
            $table->string('ward_no')->nullable();
            $table->string('username');
            $table->unique('username');
            $table->string('password');
            $table->integer('status')->nullable();
            $table->char('longitude',16)->nullable();
            $table->char('latitude',16)->nullable();
            $table->longText('mis_data')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('woman_registration');
    }
}