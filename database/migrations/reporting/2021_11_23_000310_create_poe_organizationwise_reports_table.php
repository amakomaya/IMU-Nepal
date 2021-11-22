<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePoeOrganizationwiseReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('poe_organizationwise_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date');
            $table->string('org_code', 16);
            $table->tinyInteger('province_id');
            $table->smallInteger('district_id');
            $table->smallInteger('municipality_id');
            $table->smallInteger('poe_total_registration')->default(0);
            $table->smallInteger('poe_antigen_total')->default(0);
            $table->smallInteger('poe_antigen_positive')->default(0);
            $table->smallInteger('poe_malaria_total_test')->default(0);
            $table->smallInteger('poe_malaria_positive_test')->default(0);
            $table->smallInteger('male')->default(0);
            $table->smallInteger('female')->default(0);
            $table->smallInteger('other')->default(0);
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
        Schema::dropIfExists('poe_organizationwise_reports');
    }
}
