<?php

namespace App\Models;

use App\Support\Dataviewer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CictTracingOld extends Model
{
    use SoftDeletes;
    use Dataviewer;

    protected $connection = 'mysqldump';

    protected $table = 'cict_tracings';
    protected $fillable = [
        'case_id', 'token', 'woman_token', 'hp_code', 'checked_by', 'regdev',
        'case_what', 'case_received_date', 'cict_initiated_date',
        'name', 'age', 'age_unit', 'sex',
        'emergency_contact_one', 'emergency_contact_two', 'nationality', 'nationality_other', 
        'province_id', 'district_id', 'municipality_id', 'tole', 'ward',
        'informant_name', 'informant_relation', 'informant_relation_other', 'informant_phone', 
        'case_managed_at', 'case_managed_at_other', 'case_managed_at_hospital', 'case_managed_at_hospital_date',
        'symptoms_recent', 'symptoms_two_weeks', 'date_of_onset_of_first_symptom', 'symptoms', 'symptoms_specific', 
        'symptoms_comorbidity', 'symptoms_comorbidity_specific', 'high_exposure', 'high_exposure_other',
        'travelled_14_days', 'travelled_14_details', 'travelled_14_days_details', 
        'exposure_ref_period_from_np', 'exposure_ref_period_to_np', 'same_household', 'same_household_details',
        'close_contact', 'close_contact_details', 'direct_care', 'direct_care_details',
        'attend_social', 'attend_social_details',
        'sars_cov2_vaccinated', 'dose_one_name', 'dose_one_date', 'dose_two_name', 'dose_two_date',
        'close_ref_period_from_np', 'close_ref_period_to_np', 'household_count',
        'travel_vehicle', 'other_direct_care',
        'other_attend_social', 'other_attend_social_details',
        'completion_date', 'created_at', 'updated_at'
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

	public function contact()
    {
		return $this->hasMany('App\Models\CictContact', 'parent_case_id', 'case_id');
	}

	public function closeContacts()
    {
		return $this->hasMany('App\Models\CictCloseContact', 'cict_id');
	}

    public function checkedBy()
    {
        return $this->belongsTo(OrganizationMember::class, 'checked_by', 'token');
    }

    public function vaccine()
    {
        return $this->belongsTo(Vaccine::class, 'dose_one_name', 'id');
    }

}
