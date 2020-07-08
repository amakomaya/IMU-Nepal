<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGlobalDataEntryLogTable extends Migration
{
    public function up(){
        Schema::create('global_data_entry_log', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token');
            $table->integer('type');
            $table->string('hp_code');
        });
    }
    public function down(){
        Schema::dropIfExists('global_data_entry_log');
    }
}