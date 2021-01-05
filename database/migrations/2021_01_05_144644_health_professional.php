<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class HealthProfessional extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('health_professional', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token');
            $table->string('organization_type');
            $table->string('organization_name');
            $table->string('organization_phn');
            $table->string('organization_address');
            $table->string('designation');
            $table->string('level');
            $table->string('service_date');
            $table->string('name');
            $table->string('gender');
            $table->tinyInteger('age');
            $table->string('phone')->unique();
            $table->integer('province_id');
            $table->integer('district_id');
            $table->integer('municipality_id');
            $table->string('tole');
            $table->string('ward');
            $table->integer('perm_province_id');
            $table->integer('perm_district_id');
            $table->integer('perm_municipality_id');
            $table->string('perm_tole');
            $table->string('perm_ward');
            $table->string('citizenship_no');
            $table->string('issue_district');
            $table->string('allergies');
            $table->string('disease');
            $table->string('covid_status');
            $table->string('serial_no');
            $table->boolean('status');
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
        Schema::dropIfExists('health_professional');
    }
}
