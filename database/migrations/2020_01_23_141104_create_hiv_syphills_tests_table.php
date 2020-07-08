<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHivSyphillsTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hiv_syphillis_tests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('woman_token');
            $table->string('token');
            $table->string('counselling_date')->nullable();
            $table->string('hiv_test_date')->nullable();
            $table->integer('hiv_status')->nullable();
            $table->integer('partner_hiv_status')->nullable();
            $table->integer('partner_referred')->nullable();
            $table->integer('result_recieved')->nullable();
            $table->integer('syphillis_status')->nullable();
            $table->integer('syphillis_treated')->nullable();
            $table->integer('syphillis_tested')->nullable();
            $table->string('art_started_date')->nullable();
            $table->string('hp_code')->nullable();
            $table->integer('status')->default('1');
            $table->softDeletes();
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
        Schema::dropIfExists('hiv_syphillis_tests');
    }
}
