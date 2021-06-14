<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactDetailOld extends Model
{
    protected $connection = 'mysqldump';

    protected $table='contact_detail';
    protected $fillable = [
        'token', 'contact_tracing_token', 'name', 'age', 'age_unit', 'caste',
        'sex', 'province_id', 'district_id', 'municipality_id', 'ward', 'tole',
        'emergency_contact_one', 'emergency_contact_two', 'travelled', 'travelled_date',
        'travel_detail', 'travelled_where', 'travel_medium', 'contact_corona_infected',
        'contact_corona_infected_date', 'occupation', 'organization_name', 'station',
        'station_other', 'ppe', 'ppe_specific', 'contact_location',
        'contact_location_specific', 'first_meet_date', 'last_meet_date',
        'relevant_thing', 'contact_classification', 'symptoms_recent',
        'symptoms_within_four_week', 'symptoms_date', 'case_reason',
        'temperature', 'symptoms', 'symptoms_specific', 'symptoms_comorbidity',
        'symptoms_comorbidity_specific', 'checked_by', 'hp_code', 'regdev', 'sync',
        'update_status', 'status', 'created_at', 'updated_at', 'checked_by_name',

        'social_gathering', 'social_gathering_date', 'sars_cov2_vaccinated', 'first_dose',
        'first_product_name', 'first_date_vaccination', 'first_source_info', 'first_source_info_specific',
        'second_dose', 'second_product_name', 'second_date_vaccination', 'second_source_info',
        'second_source_info_specific', 'contact_tested', 'test_conducted', 'date_swab_collection',
        'test_result', 'date_test_result', 'follow_up', 'measures_taken', 'measures_taken_specific',
        'referral_date', 'quarantine_name', 'quarantine_location'
    ];
}