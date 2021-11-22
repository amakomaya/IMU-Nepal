<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCictReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cict_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date');
            $table->tinyInteger('province_id');
            $table->smallInteger('district_id');
            $table->integer('municipality_id');
            $table->integer('total_positive')->default(0);
            $table->smallInteger('total_test')->default(0);
            $table->smallInteger('cict_a_form')->default(0);
            $table->smallInteger('cict_a_contact_count')->default(0);
            $table->smallInteger('cict_b_one_form')->default(0);
            $table->smallInteger('cict_b_two_form')->default(0);
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
        Schema::dropIfExists('cict_reports');
    }
}
