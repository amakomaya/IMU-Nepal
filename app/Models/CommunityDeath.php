<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Support\Dataviewer;


class CommunityDeath extends Model
{
    use Dataviewer;

    protected $fillable = [
        'name', 'age', 'age_unit', 'gender', 'phone', 'address', 'guardian_name', 'province_id', 'district_id', 'municipality_id', 'ward', 'tole',
        'hp_code', 'method_of_diagnosis','comorbidity', 'other_comorbidity', 'pregnant_status',
        'complete_vaccination', 'vaccine_type', 'other_vaccine_type','date_of_positive_en', 'date_of_positive_np', 
        'date_of_outcome_en', 'date_of_outcome_np','cause_of_death', 'other_death_cause', 'time_of_death', 'remarks'
    ];
}
