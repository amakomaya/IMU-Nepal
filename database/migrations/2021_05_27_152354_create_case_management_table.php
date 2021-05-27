<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCaseManagementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('case_management', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('token', 50)->nullable();
            $table->string('woman_token', 50)->nullable();
            $table->string('hp_code', 20)->nullable();
            $table->boolean('status')->nullable();
            $table->string('contact_with_covid_place', 20)->nullable();
            $table->string('contact_travel', 20)->nullable();
            $table->string('name', 20)->nullable();
            $table->string('relation', 20)->nullable();
            $table->string('last_meet_date', 20)->nullable();
            $table->string('covid_infect_place', 20)->nullable();
            $table->string('case_gone_festival', 20)->nullable();
            $table->string('case_gone_festival_info', 20)->nullable();
            $table->string('case_contact_same_illness', 20)->nullable();
            $table->string('case_contact_same_illness_info', 20)->nullable();
            $table->string('case_gone_institution', 20)->nullable();
            $table->string('case_gone_institution_info', 20)->nullable();
            $table->string('case_additional_info', 20)->nullable();
            $table->string('checked_by', 20)->nullable();
            $table->string('regdev', 20)->nullable();
            $table->string('checked_by_name', 20)->nullable();
            $table->string('reference_date_from', 20)->nullable();
            $table->string('reference_date_to', 20)->nullable();
            $table->string('anyother_member_household', 20)->nullable();
            $table->string('total_member', 20)->nullable();
            $table->string('service_at_home', 20)->nullable();
            $table->string('service_by_health_provider', 20)->nullable();
            $table->string('public_travel', 20)->nullable();
            $table->string('travel_medium', 20)->nullable();
            $table->string('travel_medium_other', 20)->nullable();
            $table->string('travel_date', 20)->nullable();
            $table->string('travel_route', 20)->nullable();
            $table->string('vehicle_no', 20)->nullable();
            $table->string('vehicle_seat_no', 20)->nullable();
            $table->string('taxi_no', 20)->nullable();
            $table->string('taxi_location', 20)->nullable();
            $table->string('flight_no', 20)->nullable();
            $table->string('flight_seat_no', 20)->nullable();
            $table->string('departure', 20)->nullable();
            $table->string('destination', 20)->nullable();
            $table->string('school_office', 20)->nullable();
            $table->string('school_office_address', 20)->nullable();
            $table->string('school_office_phone', 20)->nullable();
            $table->string('school_office_name', 20)->nullable();
            $table->string('close_env', 20)->nullable();
            $table->string('close_env_where', 20)->nullable();
            $table->string('social_contact', 20)->nullable();
            $table->string('event', 20)->nullable();
            $table->string('event_name', 20)->nullable();
            $table->string('event_address', 20)->nullable();
            $table->string('event_phone', 20)->nullable();
            $table->string('event_date', 20)->nullable();
            $table->string('mention_detail', 20)->nullable();
            $table->string('case_direct_care', 20)->nullable();
            $table->string('case_direct_care_info', 20)->nullable();
            $table->string('high_exposure', 20)->nullable();
            $table->string('high_exposure_specific', 20)->nullable();
            $table->string('sars_cov2_vaccinated', 20)->nullable();
            $table->string('first_dose', 20)->nullable();
            $table->string('first_product_name', 20)->nullable();
            $table->string('first_date_vaccination', 20)->nullable();
            $table->string('first_source_info', 20)->nullable();
            $table->string('first_source_info_specific', 20)->nullable();
            $table->string('second_dose', 20)->nullable();
            $table->string('second_product_name', 20)->nullable();
            $table->string('second_date_vaccination', 20)->nullable();
            $table->string('second_source_info', 20)->nullable();
            $table->string('second_source_info_specific', 20)->nullable();
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
        Schema::dropIfExists('case_management');
    }
}
