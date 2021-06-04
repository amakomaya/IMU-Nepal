<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCasePaymentTable extends Migration
{
    public function up()
    {
        Schema::table('payment_cases', function (Blueprint $table) {
          $table->integer('province_id')->nullable();
          $table->integer('district_id')->nullable();
          $table->integer('municipality_id')->nullable();
          $table->integer('ward')->nullable();
          $table->string('tole', 20)->nullable();
          $table->enum('vaccine_type', ['1', '2', '3', '4', '5', '10'])->nullable();
          $table->string('other_vaccine_type', 20)->nullable();
          $table->integer('complete_vaccination')->nullable()->change();
        });

    }
}