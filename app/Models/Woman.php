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

        'occupation', 'emergency_person_relation_phone', 'email', 'nationality', 'country_name', 'passport_no', 'quarantine_type', 'quarantine_specific', 'province_quarantine_id', 'district_quarantine_id', 'municipality_quarantine_id', 'ward_quarantine', 'tole_quarantine', 'pcr_test', 'pcr_test_date','pcr_test_result', 'symptoms_specific', 'symptoms_comorbidity', 'symptoms_comorbidity_specific', 'screening', 'screening_specific'

    ];
    protected $dates = ['deleted_at'];
    protected $allowedFilters = [
        'name', 'phone', 'height', 'age', 'lmp_date_en', 'blood_group', 'province_id', 'district_id',
        'municipality_id', 'hp_code', 'caste', 'anc_status', 'delivery_status', 'labtest_status', 'pnc_status', 'created_by',


        // nested
        'ancs.created_at'

    ];
    protected $appends = ['anc_with_protocol', 'anc_visits'];
    protected $orderable = ['name', 'phone', 'age', 'lmp_date_en', 'created_at'];

    protected $supportedRelations = ['ancs', 'pncs', 'delivery', 'babyDetails', 'lab_tests', 'district', 'municipality', 'vaccinations', 'hIVSyphillisTest','user'];

    public static function getWomanName($womanToken)
    {

        $woman = Woman::where('token', $womanToken)->get()->first();

        if (count($woman) > 0) {
            return $woman->name;
        }

    }

    public static function getHealthpost($hp_code)
    {
        $healthpost = Healthpost::where('hp_code', $hp_code)->get()->first();
        if (count($healthpost) > 0) {
            return $healthpost->name;
        }
    }

    /*relationship added*/

    public static function getHealthPostInfo($hp_code)
    {
        $healthpost = Healthpost::where('hp_code', $hp_code)->get()->first();
        return $healthpost;
    }

    public static function getWard($hp_code)
    {
        $healthpost = Healthpost::where('hp_code', $hp_code)->get()->first();
        return $healthpost->ward_no;
    }

    public static function getDeliveryCount($token)
    {
        return Delivery::where('woman_token', $token)->count() + 1;
    }

    /*end of Relationship added*/

    public static function getHealthWorker($token)
    {
        $data = DB::table('health_workers')->where('token', $token)->get()->first();
        return $data;

    }

    public static function getlmpDateYear($womanToken)
    {
        $woman = Woman::where('token', $womanToken)->get()->first();
        $lmp_year = Carbon::parse($woman->lmp_date_en)->format('Y');
        $lmp_month = Carbon::parse($woman->lmp_date_en)->format('m');
        $lmp_day = Carbon::parse($woman->lmp_date_en)->format('d');
        $lmp_year_in_nepali = Calendar::eng_to_nep($lmp_year, $lmp_month, $lmp_day)->getYear();

        return $lmp_year_in_nepali;
    }

    public static function getLMPNP($date)
    {
        $lmp_year = Carbon::parse($date)->format('Y');
        $lmp_month = Carbon::parse($date)->format('m');
        $lmp_day = Carbon::parse($date)->format('d');
        $lmp_in_nepali = Calendar::eng_to_nep($lmp_year, $lmp_month, $lmp_day)->getYearMonthDayEngToNep();

        return $lmp_in_nepali;
    }

    public static function getEDDfromLMPNP($date)
    {
        $edd_date = date('Y-m-d', strtotime($date . ' + 280 days'));
        $edd_year = Carbon::parse($edd_date)->format('Y');
        $edd_month = Carbon::parse($edd_date)->format('m');
        $edd_day = Carbon::parse($edd_date)->format('d');
        $edd_in_nepali = Calendar::eng_to_nep($edd_year, $edd_month, $edd_day)->getYearMonthDayEngToNep();

        return $edd_in_nepali;
    }

    public static function getlmpDateMonth($womanToken)
    {
        $woman = Woman::where('token', $womanToken)->get()->first();
        $lmp_year = Carbon::parse($woman->lmp_date_en)->format('Y');
        $lmp_month = Carbon::parse($woman->lmp_date_en)->format('m');
        $lmp_day = Carbon::parse($woman->lmp_date_en)->format('d');
        $lmp_month_in_nepali = Calendar::eng_to_nep($lmp_year, $lmp_month, $lmp_day)->getMonth();

        return $lmp_month_in_nepali;
    }

    public static function getlmpDateDay($womanToken)
    {
        $woman = Woman::where('token', $womanToken)->get()->first();
        $lmp_year = Carbon::parse($woman->lmp_date_en)->format('Y');
        $lmp_month = Carbon::parse($woman->lmp_date_en)->format('m');
        $lmp_day = Carbon::parse($woman->lmp_date_en)->format('d');
        $lmp_day_in_nepali = Calendar::eng_to_nep($lmp_year, $lmp_month, $lmp_day)->getDay();

        return $lmp_day_in_nepali;
    }

    public static function geteddYear($womanToken)
    {
        $woman = Woman::where('token', $womanToken)->get()->first();
        $edd_date = date('Y-m-d', strtotime($woman->lmp_date_en . ' + 280 days'));
        $edd_year = Carbon::parse($edd_date)->format('Y');
        $edd_month = Carbon::parse($edd_date)->format('m');
        $edd_day = Carbon::parse($edd_date)->format('d');
        $edd_year_in_nepali = Calendar::eng_to_nep($edd_year, $edd_month, $edd_day)->getYear();

        return $edd_year_in_nepali;
    }

    public static function geteddMonth($womanToken)
    {
        $woman = Woman::where('token', $womanToken)->get()->first();
        $edd_date = date('Y-m-d', strtotime($woman->lmp_date_en . ' + 280 days'));
        $edd_year = Carbon::parse($edd_date)->format('Y');
        $edd_month = Carbon::parse($edd_date)->format('m');
        $edd_day = Carbon::parse($edd_date)->format('d');
        $edd_month_in_nepali = Calendar::eng_to_nep($edd_year, $edd_month, $edd_day)->getMonth();

        return $edd_month_in_nepali;
    }

    public static function geteddDay($womanToken)
    {
        $woman = Woman::where('token', $womanToken)->get()->first();
        $edd_date = date('Y-m-d', strtotime($woman->lmp_date_en . ' + 280 days'));
        $edd_year = Carbon::parse($edd_date)->format('Y');
        $edd_month = Carbon::parse($edd_date)->format('m');
        $edd_day = Carbon::parse($edd_date)->format('d');
        $edd_month_in_nepali = Calendar::eng_to_nep($edd_year, $edd_month, $edd_day)->getDay();

        return $edd_month_in_nepali;
    }

    public static function getANCInformation($token)
    {
        $data = Anc::Where([['woman_token', '=', $token], ['status', '=', '1']])->get();
        return $data;
    }

    public static function getNepaliDate($date)
    {
        $date = date('Y-m-d', strtotime($date));
        $year = Carbon::parse($date)->format('Y');
        $month = Carbon::parse($date)->format('m');
        $day = Carbon::parse($date)->format('d');

        $nepali_date = Calendar::eng_to_nep($year, $month, $day)->getYearMonthDayEngToNep();

        return date('d/m/Y', strtotime($nepali_date));
    }

    public static function checkValidId($id)
    {
        if (Auth::user()->role == "healthpost") {
            $loggedInToken = Auth::user()->token;
            $loggedInhpCode = Healthpost::modelHealthpost($loggedInToken)->hp_code;
            $recoredHpCode = Woman::where('id', $id)->get()->first()->hp_code;
            if ($loggedInhpCode == $recoredHpCode) {
                return true;
            }
            return false;
        }
        return true;
    }

    public function scopeWithAll($query)
    {
        return $query->with($this->supportedRelations);
    }

    public function getFillable()
    {
        return $this->fillable;
    }

    public function lab_tests()
    {
        return $this->hasMany('App\Models\LabTest', 'woman_token', 'token');
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

    public function scopeFromToDate($query, $from, $to)
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }

    public function checkAncVisit($lmp_date, $anc)
    {
        $visit_date = $anc->visit_date;

        if ($anc->service_for === null || $anc->service_for ===  0) {

            if (Carbon::parse($visit_date) <= Carbon::parse($lmp_date)->addMonths(4) && Carbon::parse($visit_date) >= Carbon::parse($lmp_date)->addMonths(3)) {
                return "First";
            }
            if (Carbon::parse($visit_date) <= Carbon::parse($lmp_date)->addMonths(6) && Carbon::parse($visit_date) >= Carbon::parse($lmp_date)->addMonths(5)) {
                return "Second";
            }
            if (Carbon::parse($visit_date) <= Carbon::parse($lmp_date)->addMonths(8) && Carbon::parse($visit_date) >= Carbon::parse($lmp_date)->addMonths(7)) {
                return "Third";
            }
            if (Carbon::parse($visit_date) > Carbon::parse($lmp_date)->addMonths(8)) {
                return "Forth";
            }

        } else {
            $array = array(0 => 'Regular', 1 => 'First', 2 => 'Second', 3 => 'Third', 4 => 'Forth');
            return isset($array[$anc->service_for]) ? $array[$anc->service_for] : 'Regular';
        }
    }

    public function isFirstAnc($woman, $date = null)
    {
        if (null == $date) {
            $date = [
                'to_date' => Carbon::now(),
                'from_date' => Carbon::now()->subYear(20),
            ];
        }
        $ancs = $woman->ancs->filter(function ($anc, $key) use ($woman, $date) {
            if (Carbon::parse($anc->visit_date) < $date['to_date'] ) {
                return $anc;
            }
        });
        $anc_visit_date_min = Carbon::parse($woman->lmp_date_en)->addMonths(3);
        $anc_visit_date_max = Carbon::parse($woman->lmp_date_en)->addMonths(4);

        if (!empty($ancs)) {
            foreach ($ancs as $anc) {
                if ($anc->service_for == 1){
                    return true;
                }
                if (Carbon::parse($anc->visit_date) <= $anc_visit_date_max && Carbon::parse($anc->visit_date) >= $anc_visit_date_min) {
                    return true;
                }
            }
        }
        return false;
    }

    public function isForthMonthAnc($woman, $date = null)
    {
        if (null == $date) {
            $date = [
                'to_date' => Carbon::now(),
                'from_date' => Carbon::now()->subYear(20),
            ];
        }
        $ancs = $woman->ancs->filter(function ($anc, $key) use ($woman, $date) {
            if (Carbon::parse($anc->visit_date) < $date['to_date']) {
                return $anc;
            }
        });

        $anc_visit_date_min = Carbon::parse($woman->lmp_date_en)->addMonths(3);
        $anc_visit_date_max = Carbon::parse($woman->lmp_date_en)->addMonths(4);

        if (!empty($ancs)) {
            foreach ($ancs as $anc) {
                if ($anc->service_for == 1){
                    return true;
                }
                if (Carbon::parse($anc->visit_date) <= $anc_visit_date_max && Carbon::parse($anc->visit_date) >= $anc_visit_date_min) {
                    return true;
                }
            }
        }
        return false;
    }

    public function isSecondAnc($woman, $date = null)
    {
        if (null == $date) {
            $date = [
                'to_date' => Carbon::now(),
                'from_date' => Carbon::now()->subYear(20),
            ];
        }
        $ancs = $woman->ancs->filter(function ($anc, $key) use ($woman, $date) {
            if (Carbon::parse($anc->visit_date) < $date['to_date']) {
                return $anc;
            }
        });

        $anc_visit_date_min = Carbon::parse($woman->lmp_date_en)->addMonths(5);
        $anc_visit_date_max = Carbon::parse($woman->lmp_date_en)->addMonths(6);

        if (!empty($ancs)) {
            foreach ($ancs as $anc) {
                if ($anc->service_for == 2){
                    return true;
                }
                if (Carbon::parse($anc->visit_date) <= $anc_visit_date_max && Carbon::parse($anc->visit_date) >= $anc_visit_date_min) {
                    return true;
                }
            }
        }
        return false;
    }

    public function isThirdAnc($woman, $date = null)
    {
        if (null == $date) {
            $date = [
                'to_date' => Carbon::now(),
                'from_date' => Carbon::now()->subYear(20),
            ];
        }
        $ancs = $woman->ancs->filter(function ($anc, $key) use ($woman, $date) {
            if (Carbon::parse($anc->visit_date) < $date['to_date']) {
                return $anc;
            }
        });

        $anc_visit_date_min = Carbon::parse($woman->lmp_date_en)->addMonths(7);
        $anc_visit_date_max = Carbon::parse($woman->lmp_date_en)->addMonths(8);

        if (!empty($ancs)) {
            foreach ($ancs as $anc) {
                if ($anc->service_for == 3){
                    return true;
                }
                if (Carbon::parse($anc->visit_date) <= $anc_visit_date_max && Carbon::parse($anc->visit_date) >= $anc_visit_date_min) {
                    return true;
                }
            }
        }
        return false;
    }

    public function isForthAnc($woman, $date = null)
    {
        if (null == $date) {
            $date = [
                'to_date' => Carbon::now(),
                'from_date' => Carbon::now()->subYear(20),
            ];
        }
        $ancs = $woman->ancs->filter(function ($anc, $key) use ($woman, $date) {
            if (Carbon::parse($anc->visit_date) < $date['to_date']) {
                return $anc;
            }
        });

        $anc_visit_date_min = Carbon::parse($woman->lmp_date_en)->addMonths(8);
        $anc_visit_date_max = Carbon::parse($woman->lmp_date_en)->addMonths(9);

        if (!empty($ancs)) {
            foreach ($ancs as $anc) {
                if ($anc->service_for == 4){
                    return true;
                }
                if (Carbon::parse($anc->visit_date) < $anc_visit_date_max && Carbon::parse($anc->visit_date) > $anc_visit_date_min) {
                    return true;
                }
            }
        }
        return false;
    }

    public function isFirstTimeAnc($woman, $date = null)
    {
        if (null == $date) {
            $date = [
                'to_date' => Carbon::now(),
                'from_date' => Carbon::now()->subMonth(1),
            ];
        }
        $anc = $woman->ancs()->orderBy('visit_date')->first();

        if (!empty($anc)) {
            if (Carbon::parse($anc->visit_date)->isBetween(Carbon::parse($date['to_date']), Carbon::parse($date['from_date']))) {
                return true;
            }
        }
        return false;
    }

    public function countIronCapsuleBeforeDelivery($woman)
    {
        $from = date($woman->lmp_date_en);
        if (!is_null($woman->delivery_status)) {
            try {
                $to = date($woman->deliveries()->active()->latest()->first()->delivery_date);
            } catch (\Exception $exception) {
                $to = date('Y-m-d');
            }
        } else {
            $to = date('Y-m-d');
        }

        return $woman->vaccinations()->active()->where('vaccine_type', 1)->whereBetween('vaccinated_date_en', [$from, $to])->sum('no_of_pills');
    }

    public function countIronCapsuleAfterDelivery($woman)
    {
        if (!is_null($woman->delivery_status)) {
            try {
                $from = date($woman->deliveries()->active()->latest()->first()->delivery_date);
                $to = date('Y-m-d');
                return $woman->vaccinations()->active()->where('vaccine_type', 1)->whereBetween('vaccinated_date_en', [$from, $to])->sum('no_of_pills');
            } catch (\Exception $exception) {
                return 0;
            }
        } else {
            return 0;
        }
    }

    public function countVitaminAfterDelivery($woman)
    {
        if (!is_null($woman->delivery_status)) {
            try {
                $from = date($woman->deliveries()->active()->latest()->first()->delivery_date);
                $to = date('Y-m-d');
                return $woman->vaccinations()->active()->where('vaccine_type', 3)->whereBetween('vaccinated_date_en', [$from, $to])->first()->vaccinated_date_np;
            } catch (\Exception $exception) {
                return 0;
            }
        } else {
            return 0;
        }
    }

    public function modelDeliveryByDeliveryToken($token)
    {
        $data = Delivery::Where('token', $token)->get()->first();
        return $data;
    }

    public function modelBaby($token)
    {
        $delivery = $this->modelDelivery($token);
        if (count($delivery) > 0) {
            $data = BabyDetail::Where([['delivery_token', '=', $delivery->token], ['status', '=', '1']])->get();
            return $data;
        }

    }

    public function modelDelivery($token)
    {
        $data = Delivery::Where([['woman_token', '=', $token], ['status', '=', '1']])->get()->first();
        return $data;
    }

    public function modelPnc($token)
    {
        $data = Pnc::Where([['woman_token', '=', $token], ['status', '=', '1']])->get();
        return $data;
    }

    public function findDeliveryDateTime($token)
    {
        $delivery = $this->modelDelivery($token);
        return $deliveryDateTime = $delivery['delivery_date'] . " " . $delivery['delivery_time'] . ":00";
    }

    public function modelLabTest($token)
    {
        $data = LabTest::where([['woman_token', '=', $token], ['status', '=', '1']])->get();
        return $data;
    }

    public function casteOptions()
    {
        return [
            '1' => 'दलित',
            '2' => 'जनजाति',
            '3' => 'मधेसी',
            '4' => 'मुस्लिम',
            '5' => 'ब्राह्मण / क्षेत्री',
            '6' => 'अन्य',
        ];
    }

    public function bloodGroupOptions()
    {
        return [
            'Select Blood Group', 'A +ve', 'A -ve', 'B +ve', 'B -ve', 'O +ve', 'O -ve', 'AB +ve', 'AB -ve'
        ];
    }

    public function getBloodGroup($data)
    {
        switch ($data) {
            case 1:
                return 'A +ve';

            case 2:
                return 'A -ve';

            case 3:
                return 'B +ve';

            case 4:
                return 'B -ve';

            case 5:
                return 'O +ve';

            case 6:
                return 'O -ve';

            case 7:
                return 'AB +ve';

            case 8:
                return 'AB -ve';

            default:
                return '';
        }
    }

    public function scopeAncCompletedAtLeastOnce($query)
    {
        return $query->where('anc_status', 1);
    }

    public function getAncWithProtocolAttribute()
    {
        return $this->scopeAncCount();
    }

    public function scopeAncCount()
    {
        return AncCalculation::get($this);
    }

    public function getAncVisitsAttribute()
    {
        return AncVisitCalculation::get($this);
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
        return $this->hasMany('App\Models\Anc', 'woman_token', 'token');
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
}