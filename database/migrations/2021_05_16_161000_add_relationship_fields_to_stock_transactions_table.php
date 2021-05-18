<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToStockTransactionsTable extends Migration
{
    public function up()
    {
        Schema::table('stock_transactions', function (Blueprint $table) {
            $table->unsignedInteger('asset_id');
            $table->foreign('asset_id')->references('id')->on('assets');
            $table->char('hp_code', 16);
            //$table->foreign('hp_code')->references('hp_code')->on('healthposts');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
        });

    }
}