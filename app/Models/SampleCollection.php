<?php

namespace App\Models;

use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Yagiten\Nepalicalendar\Calendar;

class SampleCollection extends Model
{
    use Notifiable;
    use SoftDeletes;

    protected $table = 'ancs';

    protected $fillable = ['token', 'woman_token', 'service_for', 'checked_by', 'hp_code', 'status',

    'checked_by_name', 'sample_type', 'sample_type_specific', 'sample_case',
        'sample_case_specific', 'sample_identification_type',
        'service_type', 'result','infection_type', 'regdev',
        'created_at', 'updated_at', 'situation',
        'received_by', 'received_date_en', 'received_date_np', 'collection_date_en', 'collection_date_np',
        'sample_test_date_en', 'sample_test_date_np','sample_test_time', 'lab_token', 'received_by_hp_code',

        'reporting_date_np', 'reporting_date_en'
        ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $appends = ['formatted_result'];

    public function woman()
    {
        return $this->belongsTo('App\Models\SuspectedCase', 'woman_token', 'token');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function getFillable()
    {
        return $this->fillable;
    }


    public function labreport()
    {
        return $this->hasOne('App\Models\LabTest', 'sample_token', 'token');
    }

    public function infectionType($data){
        switch ($data){
            case '1':
                return 'Symptomatic';
            case '2':
                return 'Asymptomatic';
        }

    }

    public function getFormattedResultAttribute(){
        switch($this->result){
            case 2:
                return 'Pending';
            case 3:
                return 'Positive';
            case 4:
                return 'Negative';
            case 9:
                return 'Received';
            default:
                return 'Don\'t Know';
        }
    }

    public function sampleCollectionType($data){
        $contains_nasopharyngeal = Str::contains($data, ['1']);
        $contains_oropharyngeal = Str::contains($data, ['2']);

        if ($contains_nasopharyngeal && $contains_oropharyngeal){
            return 'Nasopharyngeal, Oropharyngeal';
        }

        if ($contains_nasopharyngeal){
            return 'Nasopharyngeal';
        }

        if ($contains_oropharyngeal){
            return 'Oropharyngeal';
        }

    }
}