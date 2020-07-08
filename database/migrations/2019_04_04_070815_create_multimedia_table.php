<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMultimediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('multimedia', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('type');
            $table->text('title_en');
            $table->text('title_np');
            $table->longText('description_np')->nullable();
            $table->longText('description_en')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('path')->nullable();
            $table->integer('week_id');
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
        Schema::dropIfExists('multimedia');
    }
}
