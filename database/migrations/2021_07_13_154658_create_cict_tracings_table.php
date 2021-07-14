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
            $table->string('woman_token');
            $table->enum('case_what', ['1', '2'])->nullable();
            $table->string('initiated_date_np')->nullable();
            $table->date('initiated_date_en')->nullable();
            $table->string('institution_details')->nullable();
            
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
