<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppointmentPlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointment_plan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('hp_code');
            $table->string('plan')->nullable();
            $table->string('place')->nullable();
            $table->string('from')->nullable();
            $table->string('to')->nullable();
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
            $table->string('duration')->nullable();
            $table->string('total_seat')->nullable();
            $table->string('booked_seat')->nullable();
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
        Schema::dropIfExists('appointment_plan');
    }
}