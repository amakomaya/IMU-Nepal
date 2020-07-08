<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVaccinationRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vaccination_records', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token');
            $table->string('baby_token');
            $table->char('hp_code', 16);
            $table->char('vaccine_name', 16);
            $table->char('vaccine_period', 16)->nullable();
            $table->date('vaccinated_date_en');
            $table->string('vaccinated_address')->nullable();
            $table->string('vial_image');
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
        Schema::dropIfExists('vaccination_records');
    }
}
