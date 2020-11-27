<?php

namespace App\Models;

use App\Support\Dataviewer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class SuspectedCase extends Model
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

        'occupation', 'emergency_name_person_phone', 'email', 'nationality', 'country_name', 'passport_no', 'quarantine_type', 'quarantine_specific', 'province_quarantine_id', 'district_quarantine_id', 'municipality_quarantine_id', 'ward_quarantine', 'tole_quarantine', 'pcr_test', 'pcr_test_date','pcr_test_result', 'symptoms_specific', 'symptoms_comorbidity', 'symptoms_comorbidity_specific', 'screening', 'screening_specific', 'age_unit', 'emergency_contact_one', 'emergency_contact_two', 'cases', 'case_where', 'end_case', 'payment', 'result', 'case_id', 'parent_case_id',

        'symptoms_recent', 'symptoms_within_four_week', 'symptoms_date', 'case_reason', 'temperature', 'date_of_onset_of_first_symptom' , 'reson_for_testing',

        'case_type'

    ];
    protected $dates = ['deleted_at'];
    protected $allowedFilters = [
        'name', 'phone', 'height', 'age', 'lmp_date_en', 'blood_group', 'province_id', 'district_id',
        'municipality_id', 'hp_code', 'caste', 'anc_status', 'delivery_status', 'labtest_status', 'pnc_status', 'created_by',
        'created_at',

        'emergency_contact_one',

        // nested
        'ancs.created_at', 'ancs.token',

    ];
    protected $appends = ['formated_age_unit', 'formated_gender'];
    // protected $appends = ['anc_with_protocol', 'anc_visits'];
    protected $orderable = ['name', 'age', 'lmp_date_en', 'created_at'];

    protected $supportedRelations = ['ancs', 'latestAnc', 'healthworker' ,'healthpost', 'district', 'municipality'];

    public static function getWomanName($womanToken)
    {

        $woman = SuspectedCase::where('token', $womanToken)->get()->first();

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
        return $this->hasMany('App\Models\SampleCollection', 'woman_token', 'token')->with('labreport');
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
        return $this->hasOne('App\Models\Organization', 'hp_code', 'hp_code');
    }

    public function healthworker()
    {
        return $this->hasOne('App\Models\OrganizationMember', 'token', 'created_by');
    }

    public function latestAnc()
    {
        return $this->hasOne('App\Models\SampleCollection', 'woman_token', 'token')->with('labreport')->latest();
    }

    public static function getHealthpost($hp_code)
    {
        $healthpost = Organization::where('hp_code', $hp_code)->get()->first();
        if (count($healthpost) > 0) {
            return $healthpost->name;
        }
    }

    public function getFormatedAgeUnitAttribute(){
        return $this->ageUnitCheck($this->age_unit);
    }

    private function ageUnitCheck($data){
        switch($data){
            case '1':
                return 'Months';
            case '2':
                return 'Days';
            default:
                return 'Years';
        }
    }

    public function getFormatedGenderAttribute(){

        switch($this->sex){
            case '1':
                return 'Male';
            case '2':
                return 'Female';
            default:
                return 'Don\'t Know';
        }
    }

    public function scopeActivePatientList($query){
        return $query->whereHas('latestAnc', function ($latest_anc_query) {
            $latest_anc_query->whereNotIn('ancs.result' ,[ 3, 4, 9]);
        })->doesntHave('latestAnc', 'or');
//        return $query->where($this->latestAnc()->first()->result, '4');
    }

    public function scopePassivePatientList($query){
        return $query->whereHas('latestAnc', function ($latest_anc_query) {
            $latest_anc_query->where('result', 4);
        });
    }

    public function scopePositivePatientList($query){
        return $query->whereHas('latestAnc', function ($latest_anc_query) {
            $latest_anc_query->where('result', 3);
        });
    }

    public function scopeLabReceivedList($query){
        return $query->whereHas('latestAnc', function ($latest_anc_query) {
            $latest_anc_query->where('result', 9)->whereHas('labReport');
        });
    }
    public function scopeLabAddReceived($query){
        return $query->whereHas('latestAnc', function ($latest_anc_query) {
            $latest_anc_query->where('result', 9)->whereHas('labReport');
        });
    }

    public function scopeLabAddReceivedNegative($query){
        return $query->whereHas('latestAnc', function ($latest_anc_query) {
            $latest_anc_query->where('result', 4)->whereHas('labReport');
        });
    }

    public function scopeLabAddReceivedPositive($query){
        return $query->whereHas('latestAnc', function ($latest_anc_query) {
            $latest_anc_query->where('result', 3)->whereHas('labReport');
        });
    }


    public function scopeCasesRecoveredList($query){
        return $query->where('end_case', 1);
    }

    public function scopeCasesDeathList($query){
        return $query->where('end_case', 2);
    }

//    public function scopeDashboardSampleCollection($query){
//        return $query->with('latestAnc')->whereHas('latestAnc');
//    }

//    public function scopeDashboardSampleCollectionIn24hrs($query){
//        return $query->with('latestAnc')->whereHas('latestAnc', function ($latest_anc_query) {
//            $latest_anc_query->where('created_at', '>=', Carbon::now()->subDay()->toDateTimeString());
//        });
//    }

//    public function scopeDashboardSampleReceivedInLab($query){
//        return $query->with('latestAnc')->whereHas('latestAnc', function ($latest_anc_query) {
//            $latest_anc_query->whereHas('labReport');
//        });
//    }

    public function scopeDashboardSampleReceivedInLabIn24hrs($query){
        return $query->with('latestAnc')->whereHas('latestAnc', function ($latest_anc_query) {
            $latest_anc_query->whereHas('labReport', function ($lab_query){
                $lab_query->where('created_at', '>=', Carbon::now()->subDay()->toDateTimeString());
            });
        });
    }

    public function scopeDashboardLabReceivedPositive($query){
        return $query->with('latestAnc')->whereHas('latestAnc', function ($latest_anc_query) {
            $latest_anc_query->whereHas('labReport', function ($lab_query){
                $lab_query->where('sample_test_result', 3);
            });
        });
    }

    public function scopeDashboardLabReceivedPositiveIn24hrs($query){
        return $query->with('latestAnc')->whereHas('latestAnc', function ($latest_anc_query) {
            $latest_anc_query->whereHas('labReport', function ($lab_query){
                $lab_query->where('sample_test_result', 3)->where('updated_at', '>=', Carbon::now()->subDay()->toDateTimeString());
            });
        });
    }

    public function scopeDashboardLabReceivedNegative($query){
        return $query->with('latestAnc')->whereHas('latestAnc', function ($latest_anc_query) {
            $latest_anc_query->whereHas('labReport', function ($lab_query){
                $lab_query->where('sample_test_result', 4);
            });
        });
    }

    public function scopeDashboardLabReceivedNegativeIn24hrs($query){
        return $query->with('latestAnc')->whereHas('latestAnc', function ($latest_anc_query) {
            $latest_anc_query->whereHas('labReport', function ($lab_query){
                $lab_query->where('sample_test_result', 4)->where('updated_at', '>=', Carbon::now()->subDay()->toDateTimeString());
            });
        });
    }

    public function scopeDashboardLabAddReceived($query){
        return $query->whereHas('latestAnc', function ($latest_anc_query) {
            $latest_anc_query->whereHas('labReport');
        });
    }

    public function scopeDashboardLabAddReceivedNegative($query){
        return $query->whereHas('latestAnc', function ($latest_anc_query) {
            $latest_anc_query->where('result', 4)->whereHas('labReport');
        });
    }

    public function scopeDashboardLabAddReceivedPositive($query){
        return $query->whereHas('latestAnc', function ($latest_anc_query) {
            $latest_anc_query->where('result', 3)->whereHas('labReport');
        });
    }

    public function scopeDashboardLabAddReceivedIn24hrs($query){
        return $query->with('latestAnc')->whereHas('latestAnc', function ($latest_anc_query) {
            $latest_anc_query->whereHas('labReport', function ($lab_query){
                $lab_query->where('created_at', '>=', Carbon::now()->subDay()->toDateTimeString());
            });
        });
    }

    public function scopeDashboardLabAddReceivedNegativeIn24hrs($query){
        return $query->with('latestAnc')->whereHas('latestAnc', function ($latest_anc_query) {
            $latest_anc_query->whereHas('labReport', function ($lab_query){
                $lab_query->where('sample_test_result', 4)->where('updated_at', '>=', Carbon::now()->subDay()->toDateTimeString());
            });
        });
    }

    public function scopeDashboardLabAddReceivedPositiveIn24hrs($query){
        return $query->with('latestAnc')->whereHas('latestAnc', function ($latest_anc_query) {
            $latest_anc_query->whereHas('labReport', function ($lab_query){
                $lab_query->where('sample_test_result', 3)->where('updated_at', '>=', Carbon::now()->subDay()->toDateTimeString());
            });
        });
    }
}