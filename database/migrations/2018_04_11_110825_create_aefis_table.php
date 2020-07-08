<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAefisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aefis', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token');
            $table->string('baby_token');
            $table->char('hp_code', 16);
            $table->char('vaccine', 16);
            $table->date('vaccinated_date')->nullable();
            $table->date('aefi_date')->nullable();
            $table->text('problem')->nullable();
            $table->text('advice')->nullable();
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
        Schema::dropIfExists('aefis');
    }
}
