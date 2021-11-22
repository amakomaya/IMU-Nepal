<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNationalDailiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('national_dailies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date');
            $table->tinyInteger('hospital_type', 1);
            $table->tinyInteger('province_id', 1);
            $table->smallInteger('district_id', 3);
            $table->smallInteger('municipality_id', 6);
            $table->smallInteger('anitigen_positive')->default(0);
            $table->smallInteger('anitigen_negative')->default(0);
            $table->smallInteger('pcr_positive')->default(0);
            $table->smallInteger('pcr_negative')->default(0);
            $table->smallInteger('reg_dev_web')->default(0);
            $table->smallInteger('reg_dev_mobile')->default(0);
            $table->smallInteger('reg_dev_api')->default(0);
            $table->smallInteger('reg_dev_excel')->default(0);
            $table->smallInteger('admission')->default(0);
            $table->smallInteger('under_treatment')->default(0);
            $table->smallInteger('discharge')->default(0);
            $table->smallInteger('death')->default(0);
            $table->smallInteger('cict_a_form')->default(0);
            $table->smallInteger('cict_b_one_form')->default(0);
            $table->smallInteger('cict_b_two_form')->default(0);
            $table->smallInteger('poe_total_registration')->default(0);
            $table->smallInteger('poe_malaria_total_test')->default(0);
            $table->smallInteger('poe_malaria_positive_test')->default(0);
            $table->smallInteger('poe_antigen_total')->default(0);
            $table->smallInteger('poe_antigen_positive')->default(0);
            $table->smallInteger('vaccine_pre_registration')->default(0);
            $table->smallInteger('vaccine_certificate')->default(0);
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
        Schema::dropIfExists('national_dailies');
    }
}
