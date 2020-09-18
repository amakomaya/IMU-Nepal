<?php

namespace App\Models;

use App\Helpers\AncCalculation;
use App\Helpers\AncVisitCalculation;
use App\Support\Dataviewer;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Yagiten\Nepalicalendar\Calendar;

class Woman extends Model
{
    use Notifiable;
    use Dataviewer;
    use SoftDeletes;

    public static $caste = [
        '1' => 'दलित',
        '2' => 'जनजाति',
        '3' => 'मधेसी',
        '4' => 'मुस्लिम',
        '5' => 'ब्राह्मण / क्षेत्री',
        '6' => 'अन्य',
    ];
    protected $table = 'women';
    protected $fillable = ['token', 'name', 'phone', 'height', 'age', 'lmp_date_en', 'lmp_date_np', 'orc_id', 'blood_group', 'mool_darta_no', 'sewa_darta_no', 'orc_darta_no', 'province_id', 'district_id', 'municipality_id', 'hp_code', 'tole', 'ward', 'caste', 'husband_name', 'anc_status', 'delivery_status', 'labtest_status', 'pnc_status', 'registered_device', 'created_by', 'longitude', 'latitude', 'status', 'created_at', 'updated_at',

        // new field
        'sex', 'emergency_name', 'emergency_name_person_relation', 'chronic_illness', 'symptoms', 'travelled', 'travelled_date', 'travel_medium', 'travel_detail', 'travelled_where', 
        //'covid_infect', 'covid_around_you'
        'family_member','family_chronic_illness','family_above_sixty','family_below_ten',

        'occupation', 'emergency_name_person_phone', 'email', 'nationality', 'country_name', 'passport_no', 'quarantine_type', 'quarantine_specific', 'province_quarantine_id', 'district_quarantine_id', 'municipality_quarantine_id', 'ward_quarantine', 'tole_quarantine', 'pcr_test', 'pcr_test_date','pcr_test_result', 'symptoms_specific', 'symptoms_comorbidity', 'symptoms_comorbidity_specific', 'screening', 'screening_specific', 'age_unit'

    ];
    protected $dates = ['deleted_at'];
    protected $allowedFilters = [
        'name', 'phone', 'height', 'age', 'lmp_date_en', 'blood_group', 'province_id', 'district_id',
        'municipality_id', 'hp_code', 'caste', 'anc_status', 'delivery_status', 'labtest_status', 'pnc_status', 'created_by',


        // nested
        'ancs.created_at'

    ];
    // protected $appends = ['anc_with_protocol', 'anc_visits'];
    protected $orderable = ['name', 'phone', 'age', 'lmp_date_en', 'created_at'];

    protected $supportedRelations = ['ancs', 'latestAnc', 'healthworker' ,'healthpost'];

    public static function getWomanName($womanToken)
    {

        $woman = Woman::where('token', $womanToken)->get()->first();

        if (count($woman) > 0) {
            return $woman->name;
        }

    }

    public function scopeWithAll($query)
    {
        return $query->with($this->supportedRelations);
    }

    public function getFillable()
    {
        return $this->fillable;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeGetNotifiableByToken($query, $token)
    {
        return $query->where('token', $token)->first();
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


    public function delete()
    {
        $this->ancs()->delete();
        $this->deliveries()->delete();
        $this->pncs()->delete();
        parent::delete();
    }

    public function ancs()
    {
        return $this->hasMany('App\Models\Anc', 'woman_token', 'token')->with('labreport');
    }

    public function deliveries()
    {
        return $this->hasMany('App\Models\Delivery', 'woman_token', 'token');
    }

    public function pncs()
    {
        return $this->hasMany('App\Models\Pnc', 'woman_token', 'token');
    }

    public function delivery()
    {
        return $this->hasOne('App\Models\Delivery', 'woman_token', 'token');
    }

    public function babyDetails()
    {
        return $this->hasManyThrough(
            'App\Models\BabyDetail',
            'App\Models\Delivery',
            'woman_token',
            'delivery_token',
            'token',
            'token'
        );
    }

    public function vaccinations()
    {
        return $this->hasMany('App\Models\Woman\Vaccination', 'woman_token', 'token');
    }

    public function hivSyphillisTest()
    {
        return $this->hasOne('App\Models\HIVSyphillisTest', 'woman_token', 'token');
    }

    public function user()
    {
        return $this->hasOne('App\User', 'token', 'token');
    }

    public function healthpost()
    {
        return $this->hasOne('App\Models\Healthpost', 'hp_code', 'hp_code');
    }

    public function healthworker()
    {
        return $this->hasOne('App\Models\HealthWorker', 'token', 'created_by');
    }

    public function latestAnc()
    {
        return $this->hasOne('App\Models\Anc', 'woman_token', 'token')->with('labreport')->latest();
    }

    public static function getHealthpost($hp_code)
    {
        $healthpost = Healthpost::where('hp_code', $hp_code)->get()->first();
        if (count($healthpost) > 0) {
            return $healthpost->name;
        }
    }
}