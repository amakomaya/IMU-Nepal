<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHealthpostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('healthposts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token');
            $table->string('name');
            $table->integer('province_id');
            $table->integer('district_id');
            $table->integer('municipality_id');
            $table->integer('vaccination_center_id');
            $table->integer('ward_no');
            $table->char('hp_code', 16);
            $table->string('phone')->nullable();
            $table->text('address');
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
        Schema::dropIfExists('healthposts');
    }
}
