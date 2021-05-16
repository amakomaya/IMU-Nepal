<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationObservationCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organization_observation_cases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('hp_code')->nullable();
            $table->integer('add')->nullable();
            $table->integer('transfer_to_bed')->nullable();
            $table->integer('return_to_home')->nullable();
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
        Schema::dropIfExists('organization_observation_cases');
    }
}
