<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHealthWorkersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('health_workers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token');
            $table->string('name');
            $table->integer('province_id');
            $table->integer('district_id');
            $table->integer('municipality_id');
            $table->char('ward',16);
            $table->char('hp_code', 16);
            $table->string('image')->nullable();
            $table->string('phone')->nullable();
            $table->string('tole');
            $table->char('registered_device', 16)->nullable();
            $table->char('role', 16);
            $table->string('post');
            $table->char('longitude',16)->nullable();
            $table->char('latitude',16)->nullable();
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
        Schema::dropIfExists('health_workers');
    }
}
