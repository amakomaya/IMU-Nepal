<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVaccineVialStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vaccine_vial_stocks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token');
            $table->char('name', 16);
            $table->char('hp_code', 16);
            $table->integer('dose_in_stock')->nullable();
            $table->integer('new_dose');
            $table->integer('reuseable_dose')->nullable();
            $table->date('vaccination_ending_at');
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
        Schema::dropIfExists('vaccine_vial_stocks');
    }
    
}
