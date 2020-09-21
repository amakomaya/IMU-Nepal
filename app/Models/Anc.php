<?php

namespace App\Models;

use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Yagiten\Nepalicalendar\Calendar;

class Anc extends Model
{
    use Notifiable;
    use SoftDeletes;

    protected $table = 'ancs';

    protected $fillable = ['token', 'woman_token', 'service_for', 'visit_date_np', 'visit_date', 'weight', 'anemia', 'swelling', 'blood_pressure', 'uterus_height', 'baby_presentation', 'baby_heart_beat', 'other', 'iron_pills', 'worm_medicine', 'td_vaccine', 'checked_by', 'hp_code', 'status', 

    'current_address', 'current_province', 'current_district', 'current_municipality', 'current_ward', 'current_tole', 'rdt_test', 'rdt_result', 'rdt_test_date', 'pcr_test', 'pcr_result', 'pcr_test_date', 'problem_suggestion', 'checked_by_name', 


    'sample_type', 'sample_type_specific', 'sample_case', 'sample_case_specific', 'sample_identification_type', 'service_type', 'result',

    'created_at', 'updated_at', 'situation']; // 24-28 weeks from lmp_date_en

    protected $dates = ['deleted_at'];

    public function woman()
    {
        return $this->belongsTo('App\Models\Woman', 'woman_token', 'token');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function getFillable()
    {
        return $this->fillable;
    }

    public function scopeFromToDate($query, $from, $to)
    {
        return $query->whereBetween('visit_date', [$from, $to]);
    }

    public function labreport()
    {
        return $this->hasOne('App\Models\LabTest', 'token', 'token');
    }
}
