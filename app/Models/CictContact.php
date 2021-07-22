<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CictContact extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'case_id', 'token', 'parent_case_id', 'woman_token', 'hp_code', 'checked_by', 'name', 'age', 'age_unit', 'sex',
        'emergency_contact_one', 'emergency_contact_two', 'nationality', 'nationality_other', 
        'realtionship', 'province_id', 'district_id', 'municipality_id', 'tole', 'ward',
        'informant_name', 'informant_relation', 'informant_relation_other', 'informant_phone',
        'symptoms_recent', 'date_of_onset_of_first_symptom', 'symptoms', 'symptoms_specific', 
        'symptoms_comorbidity', 'symptoms_comorbidity_specific', 'occupation', 'occupaton_other',
        'healthworker_title', 'healthworker_workplace', 'healthworker_station', 'healthworker_station_other',
        'healthworker_ppe', 'healthworker_last_date', 'healthworker_narrative',
        'travelled_14_days', 'date_14_days', 'travel_type', 'modes_of_travel', 'modes_of_travel_other', 'travel_place',
        'contact_status', 'contact_last_date', 'contact_social_status', 'contact_social_last_date',
        'sars_cov2_vaccinated', 'dose_one_name', 'dose_one_date', 'dose_two_name', 'dose_two_date',
        'measures_taken', 'measures_taken_other', 'measures_referral_date', 'measures_hospital_name',
        'test_status', 'collection_date', 'test_type', 'result_date',
        'completion_date'
    ];
}
