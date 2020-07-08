<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWomenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('women', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token');
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('height')->nullable();
            $table->tinyInteger('age');
            $table->date('lmp_date_en');
            $table->char('blood_group',4)->nullable();
            $table->integer('province_id');
            $table->integer('district_id');
            $table->integer('municipality_id');
            $table->char('hp_code', 16);
            $table->string('tole');
            $table->string('ward');
            $table->tinyInteger('caste')->default('0');
            $table->string('husband_name');
            $table->integer('mool_darta_no');
            $table->integer('sewa_darta_no');
            $table->integer('orc_darta_no');
            $table->boolean('anc_status')->nullable();
            $table->boolean('delivery_status')->nullable();
            $table->boolean('labtest_status')->nullable();
            $table->boolean('pnc_status')->nullable();
            $table->char('registered_device', 8);
            $table->string('created_by');
            $table->char('longitude',16)->nullable();
            $table->char('latitude',16)->nullable();
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
        Schema::dropIfExists('women');
    }
}
