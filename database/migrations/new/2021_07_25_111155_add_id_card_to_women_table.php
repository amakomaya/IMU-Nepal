<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdCardToWomenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('women', function (Blueprint $table) {
            $table->string('id_card_type', 3)->nullable();
            $table->string('id_card_type_other', 20)->nullable();
            $table->string('dose_details', 200)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('women', function (Blueprint $table) {
            //
        });
    }
}
