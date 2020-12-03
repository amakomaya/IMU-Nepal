<?php

namespace App\Models;

use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Yagiten\Nepalicalendar\Calendar;

class SampleCollection extends Model
{
    use Notifiable;
    use SoftDeletes;

    protected $table = 'ancs';

    protected $fillable = ['token', 'woman_token', 'service_for', 'checked_by', 'hp_code', 'status',

    'checked_by_name', 'sample_type', 'sample_type_specific', 'sample_case',
        'sample_case_specific', 'sample_identification_type',
        'service_type', 'result','infection_type',

    'created_at', 'updated_at', 'situation'];

    protected $dates = ['deleted_at'];

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
}