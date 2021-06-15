<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToAncsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ancs', function (Blueprint $table) {
            $table->string('received_by', 64)->nullable();
            $table->date('received_date_en')->nullable();
            $table->string('received_date_np', 10)->nullable();
            $table->date('collection_date_en')->nullable();
            $table->string('collection_date_np', 10)->nullable();
            $table->date('sample_test_date_en')->nullable();
            $table->string('sample_test_date_np', 10)->nullable();
            $table->string('sample_test_time', 10)->nullable();
            $table->string('lab_token', 50)->nullable();
            $table->string('received_by_hp_code', 64)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ancs', function (Blueprint $table) {
            //
        });
    }
}
