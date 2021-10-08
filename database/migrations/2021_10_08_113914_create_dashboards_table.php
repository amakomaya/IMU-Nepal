<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDashboardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dashboards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('province_id');
            $table->smallInteger('district_id');
            $table->smallInteger('municipality_id');
            $table->integer('anitigen_positive');
            $table->integer('anitigen_negative');
            $table->integer('pcr_positive');
            $table->integer('pcr_negative');
            $table->integer('admission');
            $table->integer('under_treatment');
            $table->integer('discharge');
            $table->integer('death');
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
        Schema::dropIfExists('dashboards');
    }
}
