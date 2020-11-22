<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactDetail extends Model
{
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
        'update_status', 'status', 'created_at', 'updated_at', 'checked_by_name'
    ];
}