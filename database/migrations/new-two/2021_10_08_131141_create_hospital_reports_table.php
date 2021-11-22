<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHospitalReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hospital_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date');
            $table->string('hporg_code_code', 16);
            $table->tinyInteger('province_id');
            $table->smallInteger('district_id');
            $table->smallInteger('municipality_id');
            $table->smallInteger('no_of_beds_occupied')->default(0);
            $table->smallInteger('no_of_hdu_occupied')->default(0);
            $table->smallInteger('no_of_icu_occupied')->default(0);
            $table->smallInteger('no_of_ventilator_occupied')->default(0);
            $table->smallInteger('admission')->default(0);
            $table->smallInteger('discharge')->default(0);
            $table->smallInteger('death')->default(0);
            $table->date('last_updated_date');
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
        Schema::dropIfExists('hospital_reports');
    }
}
