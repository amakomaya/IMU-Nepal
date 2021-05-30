<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCasePaymentTable extends Migration
{
    public function up()
    {
        Schema::table('payment_cases', function (Blueprint $table) {
          $table->string('comorbidity', 42)->nullable();
          $table->string('other_comorbidity')->nullable();
          $table->boolean('pregnant_status')->nullable();
          $table->date('date_of_positive')->nullable();
          $table->string('date_of_positive_np',15)->nullable();
          $table->integer('cause_of_death')->nullable();
          $table->string('other_death_cause')->nullable();
          $table->string('time_of_death')->nullable();
          
        });

    }
}