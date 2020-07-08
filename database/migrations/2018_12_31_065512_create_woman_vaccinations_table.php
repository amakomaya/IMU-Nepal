<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWomanVaccinationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('woman_vaccinations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('woman_token');
            $table->string('vaccine_name');
            $table->string('vaccine_reg_no');
            $table->string('hp_code');
            $table->integer('vaccine_type');
            $table->date('vaccinated_date_en');
            $table->date('vaccinated_date_np');
            $table->integer('no_of_pills');
            $table->integer('status');
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
        Schema::dropIfExists('woman_vaccinations');
    }
}
