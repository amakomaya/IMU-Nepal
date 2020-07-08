<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token');
            $table->string('woman_token');
            $table->date('delivery_date');
            $table->string('delivery_time')->nullable();
            $table->string('delivery_place')->nullable();
            $table->string('presentation')->nullable();
            $table->string('delivery_type')->nullable();
            $table->string('complexity')->nullable();
            $table->string('other_problem')->nullable();
            $table->string('advice')->nullable();
            $table->boolean('miscarriage_status');
            $table->char('hp_code', 16);
            $table->string('delivery_by');
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
        Schema::dropIfExists('deliveries');
    }
}
