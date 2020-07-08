<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePncsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pncs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token');
            $table->string('woman_token');
            $table->date('date_of_visit');
            $table->string('visit_time')->nullable();
            $table->string('mother_status')->nullable();
            $table->string('baby_status')->nullable();
            $table->string('advice')->nullable();
            $table->string('family_plan')->nullable();
            $table->string('iron_pills_amount')->nullable();
            $table->string('vitamin_a')->nullable();
            $table->string('checked_by');
            $table->char('hp_code', 16);
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
        Schema::dropIfExists('pncs');
    }
}
