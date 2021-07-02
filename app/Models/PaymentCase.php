<?php


namespace App\Models;


use App\Support\Dataviewer;
use Illuminate\Database\Eloquent\Model;

class PaymentCase extends Model
{
    use Dataviewer;

    protected $table='payment_cases';
    protected $dates = ['register_date_en'];

    protected $fillable = [
    'id', 'name', 'age', 'gender', 'phone', 'address', 'health_condition', 'is_death',
    'remark', 'lab_name', 'lab_id', 'is_in_imu', 'hp_code', 'created_at', 'updated_at',
        'register_date_en', 'register_date_np', 'hospital_register_id', 'date_of_outcome', 'date_of_outcome_en',
        'health_condition_update' , 'method_of_diagnosis', 'age_unit',
        'guardian_name', 'self_free', 'comorbidity', 'other_comorbidity', 'pregnant_status', 'date_of_positive', 'date_of_positive_np',
        'cause_of_death', 'other_death_cause', 'time_of_death',
        'complete_vaccination', 'province_id', 'district_id', 'municipality_id', 'ward', 'vaccine_type', 'other_vaccine_type', 'tole',
    ];

    protected $orderable = ['name', 'age', 'created_at', 'register_date_en'];

    protected $allowedFilters = [
        'name', 'age',
        'created_at',
        'register_date_en',
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
        return $this->hasOne('App\Models\Organization', 'hp_code', 'hp_code');
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