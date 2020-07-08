<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBabyDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baby_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token');
            $table->string('baby_name')->nullable();
            $table->string('delivery_token')->nullable();
            $table->char('gender', 8);
            $table->integer('caste')->nullable();
            $table->date('dob_en');
            $table->char('weight', 16)->nullable();
            $table->string('contact_no')->nullable();
            $table->string('tole')->nullable();
            $table->string('birth_place')->nullable();
            $table->string('premature_birth')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('father_name')->nullable();
            $table->string('baby_alive')->nullable();
            $table->string('baby_status')->nullable();
            $table->string('others')->nullable();
            $table->string('advice')->nullable();
            $table->char('hp_code', 16);
            $table->integer('ward_no');
            $table->integer('birth_certificate_reg_no')->nullable();
            $table->string('date_of_birth_reg', 32)->nullable();
            $table->integer('family_record_form_no')->nullable();
            $table->string('child_information_by')->nullable();
            $table->string('grand_father_name')->nullable();
            $table->string('grand_mother_name')->nullable();
            $table->string('father_citizenship_no')->nullable();
            $table->string('mother_citizenship_no')->nullable();
            $table->string('local_registrar_fullname')->nullable();
            $table->string('card_no')->nullable();
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
        Schema::dropIfExists('baby_details');
    }
}
