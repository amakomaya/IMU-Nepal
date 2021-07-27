<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCictTracingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cict_tracings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('case_id', 50);
            $table->string('token', 50);
            $table->string('woman_token', 50)->nullable();
            $table->string('hp_code', 16)->nullable();
            $table->string('checked_by', 50)->nullable();

            $table->enum('case_what', ['1', '2'])->nullable();
            $table->string('name', 64)->nullable();
            $table->tinyInteger('age')->nullable();
            $table->enum('age_unit', ['0', '1', '2', '3'])->nullable();
            $table->enum('sex', ['1', '2', '3'])->nullable();
            $table->string('emergency_contact_one', 10)->nullable();
            $table->string('emergency_contact_two', 10)->nullable();
            $table->string('nationality', 4)->nullable();
            $table->string('nationality_other', 30)->nullable();
            $table->tinyInteger('province_id')->nullable();
            $table->tinyInteger('district_id')->nullable();
            $table->integer('municipality_id')->nullable();
            $table->string('tole', 50)->nullable();
            $table->string('ward', 10)->nullable();
            $table->string('informant_name', 64)->nullable();
            $table->string('informant_relation', 3)->nullable();
            $table->string('informant_relation_other', 64)->nullable();
            $table->string('informant_phone', 10)->nullable();
            $table->string('case_managed_at', 3)->nullable();
            $table->string('case_managed_at_other', 64)->nullable();
            $table->string('case_managed_at_hospital', '3')->nullable();
            $table->string('case_managed_at_hospital_date', '10')->nullable();
            
            $table->enum('symptoms_recent', ['0', '1'])->nullable();
            $table->enum('symptoms_two_weeks', ['0', '1'])->nullable();
            $table->string('date_of_onset_of_first_symptom', 10)->nullable();
            $table->string('symptoms', 100)->nullable();
            $table->string('symptoms_specific', 64)->nullable();
            $table->string('symptoms_comorbidity', 100)->nullable();
            $table->string('symptoms_comorbidity_specific', 64)->nullable();
            $table->string('high_exposure', 64)->nullable();
            $table->string('high_exposure_other', 64)->nullable();
            $table->string('travelled_14_days', 64)->nullable();
            $table->text('travelled_14_days_details')->nullable();
            $table->string('exposure_ref_period_from_np', 10)->nullable();
            $table->string('exposure_ref_period_to_np', 10)->nullable();
            $table->enum('same_household', ['0', '1', '2'])->nullable();
            $table->text('same_household_details')->nullable();
            $table->enum('close_contact', ['0', '1', '2'])->nullable();
            $table->text('close_contact_details')->nullable();
            $table->enum('direct_care', ['0', '1', '2'])->nullable();
            $table->text('direct_care_details')->nullable();
            $table->enum('attend_social', ['0', '1', '2'])->nullable();
            $table->text('attend_social_details')->nullable();
            
            $table->enum('sars_cov2_vaccinated', ['0', '1', '2'])->nullable();
            $table->string('dose_one_name', 64)->nullable();
            $table->string('dose_one_date', 10)->nullable();
            $table->string('dose_two_name', 64)->nullable();
            $table->string('dose_two_date', 10)->nullable();
            $table->string('close_ref_period_from_np', 10)->nullable();
            $table->string('close_ref_period_to_np', 10)->nullable();
            $table->tinyInteger('household_count')->nullable();
            $table->text('household_details')->nullable();
            $table->enum('travel_vehicle', ['0', '1', '2'])->nullable();
            $table->text('travel_vehicle_details')->nullable();
            $table->enum('other_direct_care', ['0', '1', '2'])->nullable();
            $table->text('other_direct_care_details')->nullable();
            $table->enum('other_attend_social', ['0', '1', '2'])->nullable();
            $table->text('other_attend_social_details')->nullable();

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
        Schema::dropIfExists('cict_tracings');
    }
}
