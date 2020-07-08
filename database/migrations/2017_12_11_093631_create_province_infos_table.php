<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateprovinceInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('province_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('province_id');
            $table->string('token');
            $table->string('phone')->nullable();
            $table->text('office_address');
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
        Schema::dropIfExists('province_infos');
    }
}
