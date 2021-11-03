<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Support\Dataviewer;


class CommunityDeath extends Model
{
    use Dataviewer;

    protected $fillable = [
        'name', 'age', 'age_unit', 'gender', 'phone', 'address', 'guardian_name', 'province_id', 'district_id', 'municipality_id', 'ward', 'tole',
        'org_code', 'method_of_diagnosis','comorbidity', 'other_comorbidity', 'pregnant_status',
        'complete_vaccination', 'vaccine_type', 'other_vaccine_type','date_of_positive_en', 'date_of_positive_np', 
        'date_of_outcome_en', 'date_of_outcome_np','cause_of_death', 'other_death_cause', 'time_of_death', 'remarks',
        'created_at', 'updated_at'
    ];

    protected $orderable = ['name', 'age', 'created_at'];

    protected $allowedFilters = [
        'name',
        'age',
        'created_at',
        'phone',
        'date_of_outcome_en'
    ];

    protected $supportedRelations = ['municipality', 'organization'];

    public function scopeWithAll($query)
    {
        return $query->with($this->supportedRelations);
    }
  
    public function organization()
    {
        return $this->hasOne('App\Models\Organization', 'org_code', 'org_code');
    }

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
}
