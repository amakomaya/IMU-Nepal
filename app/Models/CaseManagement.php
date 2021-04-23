<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseManagement extends Model
{
    protected $table='case_mgmt';
    protected $fillable = [
        'token', 'woman_token', 'contact_with_covid_place', 'contact_travel', 'name',
        'relation', 'last_meet_date', 'covid_infect_place', 'case_gone_festival',
        'case_gone_festival_info', 'case_contact_same_illness',
        'case_contact_same_illness_info', 'case_gone_institution',
        'case_gone_institution_info', 'case_additional_info', 'checked_by',
        'hp_code', 'regdev', 'sync', 'update_status', 'status', 'created_at',
        'updated_at', 'checked_by_name', 'reference_date_from', 'reference_date_to',

        'anyother_member_household', 'total_member', 'service_at_home',
        'service_by_health_provider', 'public_travel', 'travel_medium',
        'travel_medium_other', 'travel_date', 'travel_route', 'vehicle_no',
        'vehicle_seat_no', 'taxi_no', 'taxi_location', 'flight_no',
        'flight_seat_no','departure','destination', 'school_office',
        'school_office_address', 'school_office_phone', 'school_office_name',
        'close_env', 'close_env_where', 'social_contact', 'event',
        'event_name', 'event_address', 'event_phone', 'event_date','mention_detail',

        'high_exposure', 'high_exposure_other',
        'sars_cov2_vaccinated',
        'first_dose' ,
        'first_product_name' , 'first_date_vaccination' ,'first_source_info' ,
        'first_source_info_specific',
        'second_dose', 'second_product_name',
        'second_date_vaccination', 'second_source_info' ,
        'second_source_info_specific'
    ];
}
