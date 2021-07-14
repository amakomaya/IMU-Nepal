<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCictTracingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cict_tracings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('token', 50);
            $table->string('woman_token', 50);
            $table->enum('case_what', ['1', '2'])->nullable();
            $table->string('name', 64)->nullable();
            $table->tinyInt('age', 3)->nullable();
            $table->enum('age_unit', ['0', '1', '2', '3'])->nullable();
            $table->string('emergency_contact_one', 10)->nullable();
            $table->string('emergency_contact_two', 10)->nullable();
            $table->tinyInt('province_id', 3)->nullable();
            $table->tinyInt('district_id', 3)->nullable();
            $table->tinyInt('municipality_id', 3)->nullable();
            $table->string('tole', 50)->nullable();
            $table->string('ward', 10)->nullable();
            $table->string('informant_relation', 3)->nullable();
            $table->string('informant_relation_other', 64)->nullable();
            $table->string('informant_phone', 10)->nullable();
            $table->string('case_managed_at', 3)->nullable();
            $table->string('case_managed_at_other', 64)->nullable();
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
        Schema::dropIfExists('cict_tracings');
    }
}
