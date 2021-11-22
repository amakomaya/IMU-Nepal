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
            $table->string('org_code', 16);
            $table->tinyInteger('province_id');
            $table->smallInteger('district_id');
            $table->smallInteger('municipality_id');
            $table->smallInteger('total_no_of_beds')->default(0);
            $table->smallInteger('total_no_of_hdu')->default(0);
            $table->smallInteger('total_no_of_icu')->default(0);
            $table->smallInteger('total_no_of_ventilator')->default(0);
            $table->smallInteger('no_of_beds_occupied')->default(0);
            $table->smallInteger('no_of_hdu_occupied')->default(0);
            $table->smallInteger('no_of_icu_occupied')->default(0);
            $table->smallInteger('no_of_ventilator_occupied')->default(0);
            $table->smallInteger('admission')->default(0);
            $table->smallInteger('discharge')->default(0);
            $table->smallInteger('death')->default(0);
            $table->smallInteger('total_single_dose')->default(0);
            $table->smallInteger('total_full_dose')->default(0);
            $table->smallInteger('death_single_dose')->default(0);
            $table->smallInteger('death_full_dose')->default(0);
            $table->smallInteger('death_male')->default(0);
            $table->smallInteger('death_female')->default(0);
            $table->smallInteger('death_other')->default(0);
            $table->smallInteger('total_comorbidity')->default(0);
            $table->smallInteger('death_comorbidity')->default(0);
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
