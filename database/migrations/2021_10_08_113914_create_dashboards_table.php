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
            $table->integer('anitigen_positive')->default(0);
            $table->integer('anitigen_negative')->default(0);
            $table->integer('pcr_positive')->default(0);
            $table->integer('pcr_negative')->default(0);
            $table->integer('admission')->default(0);
            $table->integer('under_treatment')->default(0);
            $table->integer('discharge')->default(0);
            $table->integer('death')->default(0);
            $table->integer('cict_a_form')->default(0);
            $table->integer('cict_b_one_form')->default(0);
            $table->integer('cict_b_two_form')->default(0);
            $table->integer('poe_total_registration')->default(0);
            $table->integer('poe_malaria_total_test')->default(0);
            $table->integer('poe_malaria_positive_test')->default(0);
            $table->integer('poe_antigen_total')->default(0);
            $table->integer('poe_antigen_positive')->default(0);
            $table->integer('vaccine_pre_registration')->default(0);
            $table->integer('vaccine_certificate')->default(0);
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
        Schema::dropIfExists('dashboards');
    }
}
