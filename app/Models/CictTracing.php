<?php

namespace App\Models;

use App\Support\Dataviewer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CictTracing extends Model
{
    use SoftDeletes;
    use Dataviewer;

    protected $fillable = [
        'case_id', 'token', 'woman_token', 'hp_code', 'checked_by', 'case_what', 'name', 'age', 'age_unit', 'sex',
        'emergency_contact_one', 'emergency_contact_two', 'nationality', 'nationality_other', 
        'province_id', 'district_id', 'municipality_id', 'tole', 'ward',
        'informant_name', 'informant_relation', 'informant_relation_other', 'informant_phone', 'case_managed_at', 'case_managed_at_other',
        'symptoms_recent', 'symptoms_two_weeks', 'date_of_onset_of_first_symptom', 'symptoms', 'symptoms_specific', 
        'symptoms_comorbidity', 'symptoms_comorbidity_specific', 'high_exposure', 'high_exposure_other',
        'travelled_14_days', 'travelled_14_details', 'travelled_14_days_details', 
        'exposure_ref_period_from_np', 'exposure_ref_period_to_np', 'same_household', 'same_household_details',
        'close_contact', 'close_contact_details', 'direct_care', 'direct_care_details',
        'attend_social', 'attend_social_details',
        'sars_cov2_vaccinated', 'dose_one_name', 'dose_one_date', 'dose_two_name', 'dose_two_date',
        'close_ref_period_from_np', 'close_ref_period_to_np', 'household_count', 'household_details',
        'travel_vehicle', 'travel_vehicle_details', 'other_direct_care', 'other_direct_care_details',
        'other_attend_social', 'other_attend_social_details',
        'completion_date'
    ];

    protected $allowedFilters = [
        'name', 'age',
        'created_at',
        'emergency_contact_one'
    ];

    protected $orderable = ['name', 'age', 'created_at'];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function municipality()
    {
        return $this->belongsTo(Municipality::class);
    }

    public function suspectedCase()
    {
        return $this->belongsTo(SuspectedCase::class, 'woman_token', 'token');
    }

}