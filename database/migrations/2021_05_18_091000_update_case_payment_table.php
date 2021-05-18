<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCasePaymentTable extends Migration
{
    public function up()
    {
        Schema::table('payment_cases', function (Blueprint $table) {
          $table->boolean('complete_vaccination')->nullable();
        });

    }
}