<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommunityDeathsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('community_deaths', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('hp_code', 16);
            $table->string('name');
            $table->tinyInteger('age')->nullable();
            $table->tinyInteger('age_unit')->nullable();
            $table->tinyInteger('gender')->nullable();
            $table->string('phone', 15)->nullable();
            $table->string('address', 128)->nullable();
            $table->integer('province_id')->nullable();
            $table->integer('district_id')->nullable();
            $table->integer('municipality_id')->nullable();
            $table->integer('ward')->nullable();
            $table->string('tole', 20)->nullable();
            $table->string('guardian_name', 64)->nullable();
            $table->integer('method_of_diagnosis')->nullable();
            $table->string('comorbidity', 42)->nullable();
            $table->string('other_comorbidity')->nullable();
            $table->boolean('pregnant_status')->nullable();
            $table->integer('complete_vaccination')->nullable();
            $table->enum('vaccine_type', ['1', '2', '3', '4', '5', '10'])->nullable();
            $table->string('other_vaccine_type', 20)->nullable();
            $table->date('date_of_positive_en')->nullable();
            $table->string('date_of_positive_np',15)->nullable();
            $table->integer('cause_of_death')->nullable();
            $table->string('other_death_cause')->nullable();
            $table->date('date_of_outcome_en')->nullable();
            $table->string('date_of_outcome_np',15)->nullable();
            $table->string('time_of_death')->nullable();
            $table->string('reg_dev', 10)->nullable();
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('community_deaths');
    }
}
