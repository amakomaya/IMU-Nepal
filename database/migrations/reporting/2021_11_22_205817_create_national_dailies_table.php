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
            $table->string('org_code', 16);
            $table->tinyInteger('hospital_type', 1);
            $table->tinyInteger('province_id', 1);
            $table->smallInteger('district_id', 3);
            $table->smallInteger('municipality_id', 6);
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
        Schema::dropIfExists('national_dailies');
    }
}
