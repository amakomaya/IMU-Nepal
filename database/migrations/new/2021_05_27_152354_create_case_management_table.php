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
            $table->string('hp_code', 50)->nullable();
            $table->string('status', 5)->nullable();
            $table->string('contact_with_covid_place', 191)->nullable();
            $table->string('contact_travel', 191)->nullable();
            $table->string('name', 191)->nullable();
            $table->string('relation', 50)->nullable();
            $table->string('last_meet_date', 20)->nullable();
            $table->string('covid_infect_place', 191)->nullable();
            $table->string('case_gone_festival', 191)->nullable();
            $table->string('case_gone_festival_info', 191)->nullable();
            $table->string('case_contact_same_illness', 191)->nullable();
            $table->string('case_contact_same_illness_info', 191)->nullable();
            $table->string('case_gone_institution', 5)->nullable();
            $table->string('case_gone_institution_info', 191)->nullable();
            $table->string('case_additional_info', 191)->nullable();
            $table->string('checked_by', 50)->nullable();
            $table->string('regdev', 191)->nullable();
            $table->string('checked_by_name', 55)->nullable();
            $table->string('reference_date_from', 20)->nullable();
            $table->string('reference_date_to', 20)->nullable();
            $table->string('anyother_member_household', 5)->nullable();
            $table->string('total_member', 20)->nullable();
            $table->string('service_at_home', 5)->nullable();
            $table->string('service_by_health_provider', 5)->nullable();
            $table->string('public_travel', 5)->nullable();
            $table->string('travel_medium', 191)->nullable();
            $table->string('travel_medium_other', 191)->nullable();
            $table->string('travel_date', 20)->nullable();
            $table->string('travel_route', 191)->nullable();
            $table->string('vehicle_no', 50)->nullable();
            $table->string('vehicle_seat_no', 55)->nullable();
            $table->string('taxi_no', 55)->nullable();
            $table->string('taxi_location', 191)->nullable();
            $table->string('flight_no', 55)->nullable();
            $table->string('flight_seat_no', 55)->nullable();
            $table->string('departure', 191)->nullable();
            $table->string('destination', 191)->nullable();
            $table->string('school_office', 5)->nullable();
            $table->string('school_office_address', 191)->nullable();
            $table->string('school_office_phone', 191)->nullable();
            $table->string('school_office_name', 191)->nullable();
            $table->string('close_env', 5)->nullable();
            $table->string('close_env_where', 191)->nullable();
            $table->string('social_contact', 5)->nullable();
            $table->string('event', 5)->nullable();
            $table->string('event_name', 55)->nullable();
            $table->string('event_address', 55)->nullable();
            $table->string('event_phone', 20)->nullable();
            $table->string('event_date', 20)->nullable();
            $table->string('mention_detail', 191)->nullable();
            $table->string('case_direct_care', 5)->nullable();
            $table->string('case_direct_care_info', 55)->nullable();
            $table->string('high_exposure', 5)->nullable();
            $table->string('high_exposure_specific', 191)->nullable();
            $table->string('sars_cov2_vaccinated', 5)->nullable();
            $table->string('first_dose', 5)->nullable();
            $table->string('first_product_name', 55)->nullable();
            $table->string('first_date_vaccination', 20)->nullable();
            $table->string('first_source_info', 191)->nullable();
            $table->string('first_source_info_specific', 191)->nullable();
            $table->string('second_dose', 5)->nullable();
            $table->string('second_product_name', 55)->nullable();
            $table->string('second_date_vaccination', 20)->nullable();
            $table->string('second_source_info', 191)->nullable();
            $table->string('second_source_info_specific', 55)->nullable();
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
