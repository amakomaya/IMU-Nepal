<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLabTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lab_tests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token');
            $table->date('test_date');
            $table->string('hb')->nullable();
            $table->string('albumin')->nullable();
            $table->string('woman_token')->nullable();
            $table->char('hp_code', 16);
            $table->string('urine_protein')->nullable();
            $table->string('urine_sugar')->nullable();
            $table->string('blood_sugar')->nullable();
            $table->string('hbsag')->nullable();
            $table->string('vdrl')->nullable();
            $table->string('retro_virus')->nullable();
            $table->string('other')->nullable();
            $table->string('test_by')->nullable();
            $table->boolean('status');
            // $table->softDeletesTz();
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
        Schema::table('lab_tests', function (Blueprint $table) {
            //
            $table->dropColumn('deleted_at');
        });
    }
}
