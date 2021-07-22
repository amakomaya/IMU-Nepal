<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCictContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cict_contacts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('case_id', 12);
            $table->string('token', 50);
            $table->string('parent_case_id', 12);
            $table->string('woman_token', 50)->nullable();
            $table->string('hp_code', 16)->nullable();
            $table->string('checked_by', 50)->nullable();

            $table->string('name', 64)->nullable();
            $table->tinyInteger('age')->nullable();
            $table->enum('age_unit', ['0', '1', '2', '3'])->nullable();
            $table->enum('sex', ['1', '2', '3'])->nullable();
            $table->string('emergency_contact_one', 10)->nullable();
            $table->string('emergency_contact_two', 10)->nullable();
            $table->string('nationality', 4)->nullable();
            $table->string('nationality_other', 30)->nullable();
            $table->string('guardian_name', 64)->nullable();
            $table->tinyInteger('province_id')->nullable();
            $table->tinyInteger('district_id')->nullable();
            $table->integer('municipality_id')->nullable();
            $table->string('tole', 50)->nullable();
            $table->string('ward', 10)->nullable();
            $table->string('informant_name', 64)->nullable();
            $table->string('informant_relation', 3)->nullable();
            $table->string('informant_relation_other', 64)->nullable();
            $table->string('informant_phone', 10)->nullable();
            
            $table->enum('symptoms_recent', ['0', '1'])->nullable();
            $table->string('date_of_onset_of_first_symptom', 10)->nullable();
            $table->string('symptoms', 100)->nullable();
            $table->string('symptoms_specific', 64)->nullable();
            $table->string('symptoms_comorbidity', 100)->nullable();
            $table->string('symptoms_comorbidity_specific', 64)->nullable();
            $table->string('occupation', 5)->nullable();
            $table->string('occupaton_other', 64)->nullable();
            $table->string('healthworker_title', 64)->nullable();
            $table->string('healthworker_workplace', 64)->nullable();
            $table->string('healthworker_station', 5)->nullable();
            $table->string('healthworker_station_other', 64)->nullable();
            $table->string('healthworker_ppe', 3)->nullable();
            $table->string('healthworker_last_date', 10)->nullable();
            $table->text('healthworker_narrative')->nullable();

            $table->string('travelled_14_days', 3)->nullable();
            $table->string('date_14_days', 10)->nullable();
            $table->string('travel_type', 3)->nullable();
            $table->string('modes_of_travel', 3)->nullable();
            $table->string('modes_of_travel_other', 64)->nullable();
            $table->string('travel_place', 64)->nullable();

            $table->string('contact_status', 3)->nullable();
            $table->string('contact_last_date', 10)->nullable();
            $table->string('contact_social_status', 3)->nullable();
            $table->string('contact_social_last_date', 10)->nullable();
            
            $table->enum('sars_cov2_vaccinated', ['0', '1', '2'])->nullable();
            $table->string('dose_one_name', 64)->nullable();
            $table->string('dose_one_date', 10)->nullable();
            $table->string('dose_two_name', 64)->nullable();
            $table->string('dose_two_date', 10)->nullable();

            $table->string('measures_taken', 3)->nullable();
            $table->string('measures_taken_other', 64)->nullable();
            $table->string('measures_referral_date', 10)->nullable();
            $table->string('measures_hospital_name', 10)->nullable();
            
            $table->string('test_status', 3)->nullable();
            $table->string('collection_date', 10)->nullable();
            $table->string('test_type', 3)->nullable();
            $table->string('result_date', 10)->nullable();
            $table->string('completion_date', 10)->nullable();
            
            $table->softDeletes();
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
        Schema::dropIfExists('cict_contacts');
    }
}
