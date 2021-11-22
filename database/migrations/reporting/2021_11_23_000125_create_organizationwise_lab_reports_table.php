<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationwiseLabReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizationwise_lab_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date');
            $table->string('org_code', 16);
            $table->tinyInteger('hospital_type');
            $table->tinyInteger('province_id');
            $table->smallInteger('district_id');
            $table->smallInteger('municipality_id');
            $table->smallInteger('antigen_positive')->default(0);
            $table->smallInteger('antigen_negative')->default(0);
            $table->integer('antigen_total')->default(0);
            $table->smallInteger('pcr_positive')->default(0);
            $table->smallInteger('pcr_negative')->default(0);
            $table->integer('pcr_total')->default(0);
            $table->smallInteger('healthworker_cases')->default(0);
            $table->smallInteger('reg_dev_web')->default(0);
            $table->smallInteger('reg_dev_mobile')->default(0);
            $table->smallInteger('reg_dev_api')->default(0);
            $table->smallInteger('reg_dev_excel')->default(0);
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
        Schema::dropIfExists('organizationwise_lab_reports');
    }
}
