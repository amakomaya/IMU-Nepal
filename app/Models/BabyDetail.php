<?php

namespace App\Models;

use App\Support\Dataviewer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Auth;
use Yagiten\NepaliCalendar\Calendar;
use App\Helpers\ViewHelper;

class BabyDetail extends Model
{
    use Dataviewer;
    use SoftDeletes;

    protected $table = 'baby_details';

    // protected $fillable = ['token','delivery_token','gender','weight', 'dob_np','premature_birth','baby_alive','baby_status','advice','hp_code','date_of_birth_reg','birth_certificate_reg_no','family_record_form_no','baby_name','child_information_by','grand_father_name','grand_mother_name','father_citizenship_no','mother_citizenship_no','local_registrar_fullname','status','updated_at'];

    protected $fillable = ['token', 'baby_name', 'delivery_token', 'gender', 'caste', 'dob_en', 'dob_np', 'weight', 'contact_no',
                            'birth_place', 'premature_birth', 'mother_name', 'father_name', 'baby_alive', 'baby_status',
                            'others', 'advice', 'hp_code','ward_no', 'birth_certificate_reg_no', 'date_of_birth_reg',
                            'family_record_form_no','child_information_by',
                            'grand_father_name','grand_mother_name','father_citizenship_no',
                            'mother_citizenship_no','local_registrar_fullname','status', 'created_at',
                            'updated_at', 'tole','orc_id'
                        ];

    protected $allowedFilters = [
        'token', 'baby_name', 'delivery_token', 'gender', 'caste', 'dob_np', 'dob_en', 'weight', 'contact_no',
        'birth_place', 'mother_name', 'father_name', 'ward_no',


        // nested
        'vaccinations.vaccinated_date_np',
        'vaccinations.vaccinated_date_en'
    ];

    protected $orderable = ['baby_name','dob_en','dob_np','ward_no','created_at'];

    protected $supportedRelations = ['vaccinations', 'weights', 'aefis','organizationalInformation'];

    public function getFillable()
    {
        return $this->fillable;
    }

    public function scopeWithAll($query)
    {
        return $query->with($this->supportedRelations);
    }

    public function scopeFromToDate($query, $from, $to)
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function delivery()
    {
        return $this->belongsTo('App\Models\Delivery', 'delivery_token', 'token');
    }

    public function vaccinations()
    {
        return $this->hasMany('App\Models\VaccinationRecord', 'baby_token', 'token');
    }

    public function weights()
    {
        return $this->hasMany('App\Models\BabyWeight', 'token', 'baby_token');
    }

    public function aefis()
    {
        return $this->hasMany('App\Models\Aefi', 'token', 'baby_token');
    }

    public function organizationalInformation(){
        return $this->hasOne('App\Models\Healthpost', 'hp_code', 'hp_code');
    }

    public static function checkValidId($id){
        $loggedInToken = Auth::user()->token;
        $municipality = MunicipalityInfo::where('token', $loggedInToken)->get()->first();
            if(count($municipality)>0){     
                $loggedInMunicipalityId = $municipality->municipality_id;
            
            $healthposts = Healthpost::where('municipality_id', $loggedInMunicipalityId)->get();
            $hpCodes = array();
            foreach ($healthposts as $healthpost) {
                $hpCodes[] = $healthpost->hp_code;
            }
            $babyDetails = BabyDetail::where('baby_alive','Alive')->whereIn('hp_code', $hpCodes)->get();
            $i=0;
            foreach ($babyDetails as $baby) {
                if($baby->id==$id){
                    $i++;
                }
            }
            if($i>0){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function getMotherName($delivery_token){
        $delivery = Delivery::where('token', $delivery_token)->get()->first();
        if(count($delivery)>0){
            $woman = Woman::where('token', $delivery->woman_token)->get()->first();
            if(count($woman)>0){
                return $woman->name;
            }
        }
    }



    public function getDob($delivery_token)
    {
        $delivery = Delivery::where('token', $delivery_token)->get()->first();
        if(count($delivery)>0){
            //echo $delivery->delivery_date;
            return $delivery->delivery_date;
        }
    }

    public function getChildBirthPlace($delivery_token)
    {
        $delivery = Delivery::where('token', $delivery_token)->get()->first();
        if(count($delivery)>0){
            return $delivery->delivery_place;
        }
    }

    public function getFatherName($delivery_token){
        $delivery = Delivery::where('token', $delivery_token)->get()->first();
        if(count($delivery)>0){
            $woman = Woman::where('token', $delivery->woman_token)->get()->first();
            if(count($woman)>0){
                return $woman->husband_name;
            }
        }
    }



    public function getMotherWardNo($delivery_token){
        $delivery = Delivery::where('token', $delivery_token)->get()->first();
        if(count($delivery)>0){
            $woman = Woman::where('token', $delivery->woman_token)->get()->first();
            if(count($woman)>0){
                return $woman->ward;
            }
        }
    }





    public function getMotherTole($delivery_token){
        $delivery = Delivery::where('token', $delivery_token)->get()->first();
        if(count($delivery)>0){
            $woman = Woman::where('token', $delivery->woman_token)->get()->first();
            if(count($woman)>0){
                return $woman->tole;
            }
        }
    }

    public function getContactNumber($delivery_token){
        $delivery = Delivery::where('token', $delivery_token)->get()->first();
        if(count($delivery)>0){
            $woman = Woman::where('token', $delivery->woman_token)->get()->first();
            if(count($woman)>0){
                return $woman->phone;
            }
        }
    }
    
    public function getCaste($delivery_token){
        $delivery = Delivery::where('token', $delivery_token)->get()->first();
        if(count($delivery)>0){
            $woman = Woman::where('token', $delivery->woman_token)->get()->first();
            if(count($woman)>0){
                return $woman->caste;
            }
        }
    }
    
    public function getTole($delivery_token){
        $delivery = Delivery::where('token', $delivery_token)->get()->first();
        if(count($delivery)>0){
            $woman = Woman::where('token', $delivery->woman_token)->get()->first();
            if(count($woman)>0){
                return $woman->tole;
            }
        }
    }
    
    public static function womanNameByChildToken($childToken){
        $baby = BabyDetail::where('token', $childToken)->get()->first();
        if(count($baby)>0){
            $delivery = Delivery::where('token', $baby->delivery_token)->get()->first();
            if(count($delivery)>0){
                $woman = Woman::where('token', $delivery->woman_token)->get()->first();
                if(count($woman)>0){
                    echo "<a href='".route('woman.show', $woman->id)."'>".$woman->name."</a>";
                }
            }
        }
    }

    public function babyAliveDetails($woman_token, $request){

        $hp_code = $request->hp_code;
        $municipality_id = $request->municipality_id;
        $ward_no = $request->ward_no;
        $district_id = $request->district_id;
        $province_id = $request->province_id;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        

        $babyDetails = DB::table('baby_details')
            ->select('baby_details.baby_alive')
            ->join('deliveries','baby_details.delivery_token','=','deliveries.token')
            ->join('women','deliveries.woman_token','=','women.token')
            ->where([['deliveries.woman_token', $woman_token],['deliveries.status', 1]]);

        if($hp_code!=""){
            $babyDetails->where('women.hp_code', $hp_code);
        }elseif($municipality_id!="" && $ward_no!=""){
            $babyDetails->join('healthposts', 'healthposts.hp_code','=','women.hp_code')
                 ->where([['healthposts.municipality_id', $municipality_id],['healthposts.ward_no',$ward_no]]);
        }elseif($municipality_id!=""){
            $babyDetails->join('healthposts', 'healthposts.hp_code','=','women.hp_code')
                 ->where('healthposts.municipality_id', $municipality_id);
        }elseif($district_id!=""){
            $babyDetails->join('healthposts', 'healthposts.hp_code','=','women.hp_code')
                 ->where('healthposts.district_id', $district_id);
        }elseif($province_id!=""){
            $babyDetails->join('healthposts', 'healthposts.hp_code','=','women.hp_code')
                 ->where('healthposts.province_id', $province_id);
        }

        $babyDetails = $babyDetails->get();


        $babyAlive = '';
        $babyDeath = '';
        foreach ($babyDetails as $babyDetail){
            if($babyDetail->baby_alive == 'Alive'){
                $babyAlive = 1;
            }
            if($babyDetail->baby_alive != 'Alive'){
                $babyDeath = 1;
            }
        }

        return [
            'babyAlive'=>$babyAlive,
            'babyDeath'=>$babyDeath
        ];


    }

    public function registeredChild($request){       

        $request = (new Woman)->womanRequestFiscalYear($request);
        $fiscal_year = $request['fiscal_year'];
        $monthlyreport = array(); 
            
        list($year,$month,$day) = explode('-', date('Y-m-d'));
        $currentNepaliDate = Calendar::eng_to_nep($year,$month,$day)->getYearMonthDay();
        list($currentNepaliYear,$currentNepaliMonth,$currentNepaliDay) = explode('-', $currentNepaliDate);
        if($currentNepaliYear==$fiscal_year){
            $nepaliFiscalMonth = 4;
            $fiscalMonthExact = "";
            $showMonthUpto = "";
            for ($i=0; $i <12 ; $i++) {
                if($nepaliFiscalMonth<=12){
                    $fiscalMonthExact = $nepaliFiscalMonth;
                }else{
                    $fiscalMonthExact = $nepaliFiscalMonth - 12;
                }


                if($currentNepaliMonth == $fiscalMonthExact){
                    $showMonthUpto = $i;
                }

                $nepaliFiscalMonth++;
            }
        }else{
            $showMonthUpto = 11;
        }

        //echo $showMonthUpto."<br>";

        $monthlyreport = array();
        $nepaliFiscalMonth = 4;
        $fiscalMonthExact = "";
        for ($i=0; $i <=$showMonthUpto ; $i++) {
            if($nepaliFiscalMonth<=12){
                $fiscalMonthExact = $nepaliFiscalMonth;
            }else{
                $fiscalMonthExact = $nepaliFiscalMonth - 12;
            }
            $nepaliFiscalMonth++;
            if($fiscalMonthExact<4){
                $fiscal_year_for_compare = $fiscal_year+1;
            }else{
                $fiscal_year_for_compare = $fiscal_year;
            }
            $fromNepali = $fiscal_year_for_compare."-".$fiscalMonthExact."-".'01';
            $from_date = ViewHelper::convertNepaliToEnglish($fromNepali); 




            $nextFiscalMonth = $fiscalMonthExact + 1;

            if($nextFiscalMonth>12)
            {
                $nepaliFiscalMonth = $nextFiscalMonth-12;
            }

            $toNepali = $fiscal_year_for_compare."-".$nextFiscalMonth."-".'01';

            $eng_date = ViewHelper::convertNepaliToEnglish($toNepali); 

            list($eng_year, $eng_month, $eng_day) = explode('-', $eng_date);

            $toDate = $eng_day - 1;

            $to_date = $eng_year."-".$eng_month."-".$toDate;

            $registeredReport =  $this->registeredChildMonthWise($request, $from_date, $to_date);

            $monthlyreport[] =  $registeredReport;

        }


        $response[] = $monthlyreport;
        $response[] = $request;

        return $response;
    }

    protected function registeredChildMonthWise($request, $from_date, $to_date){

        $data = $this->registeredChildRecords($request, $from_date, $to_date);
        $count = count($data);

        return[
            'total_child'=>$count
        ];
    }

    protected function registeredChildRecords($requests, $from_date, $to_date){

        foreach ($requests as $key => $value) {
            $$key = $value;
        }

        $babyDetails = DB::table('baby_details')
                        ->select('baby_details.token')
                        ->where([['baby_details.baby_alive', 'Alive'],['baby_details.status','1']])
                        ->whereBetween('baby_details.created_at', [$from_date, $to_date]);
        if($hp_code!=""){
            $babyDetails->where('baby_details.hp_code', $hp_code);
        }elseif($municipality_id!="" && $ward_no!=""){
            $babyDetails->join('healthposts', 'healthposts.hp_code','=','baby_details.hp_code')
                 ->where([['healthposts.municipality_id', $municipality_id],['healthposts.ward_no',$ward_no]]);
        }elseif($municipality_id!=""){
            $babyDetails->join('healthposts', 'healthposts.hp_code','=','baby_details.hp_code')
                 ->where('healthposts.municipality_id', $municipality_id);
        }elseif($district_id!=""){
            $babyDetails->join('healthposts', 'healthposts.hp_code','=','baby_details.hp_code')
                 ->where('healthposts.district_id', $district_id);
        }elseif($province_id!=""){
            $babyDetails->join('healthposts', 'healthposts.hp_code','=','baby_details.hp_code')
                 ->where('healthposts.province_id', $province_id);
        }
        
        $babyDetails = $babyDetails->get();

        return $babyDetails;
    }

    public function gethpName($hp_code)
    {
        $hp = Healthpost::where('hp_code', $hp_code)->get()->first();
        if(count($hp)>0){
            if(count($hp)>0){
                return $hp->name;
            }
        }
    }

    public function getBCG($token){
        $bcg = VaccinationRecord::where(['baby_token'=> $token, 'vaccine_name'=>'BCG'])->get()->first();
        if(count($bcg)>0){
            return $bcg->vaccinated_date_np;
        }
    }

    public function getPentavalent($token){
        $pentavalent = VaccinationRecord::where(['baby_token'=> $token, 'vaccine_name'=>'Pentavalent'])->orderBy('vaccinated_date_np')->get()->all();
        if(count($pentavalent)>0){
            return $pentavalent;
        }
    }

    public function getOPV($token){
        $opv = VaccinationRecord::where(['baby_token'=> $token, 'vaccine_name'=>'OPV'])->orderBy('vaccinated_date_np')->get()->all();
        if(count($opv)>0){
            return $opv;
        }
    }

    public function getPCV6W($token){

        $pcv = VaccinationRecord::where(['baby_token'=> $token, 'vaccine_name'=>'PCV', 'vaccine_period'=>'6W'])->orderBy('vaccinated_date_np')->get()->first();
        if(count($pcv)>0){
            if(count($pcv)>0){
                return \Carbon\Carbon::parse($pcv->vaccinated_date_np)->format('d/m/Y');
            }
        }
    }

    public function getPCV10W($token){

        $pcv = VaccinationRecord::where(['baby_token'=> $token, 'vaccine_name'=>'PCV', 'vaccine_period'=>'10W'])->orderBy('vaccinated_date_np')->get()->first();
        if(count($pcv)>0){
            if(count($pcv)>0){
                return \Carbon\Carbon::parse($pcv->vaccinated_date_np)->format('d/m/Y');
            }
        }
    }

    public function getPCV9M($token){
        $pcv = VaccinationRecord::where(['baby_token'=> $token, 'vaccine_name'=>'PCV', 'vaccine_period'=>'9M'])->orderBy('vaccinated_date_np')->get()->first();
        if(count($pcv)>0){
            if(count($pcv)>0){
                return \Carbon\Carbon::parse($pcv->vaccinated_date_np)->format('d/m/Y');
            }
        }
    }

    public function getFIPV6W($token){
        $fipv = VaccinationRecord::where(['baby_token'=> $token, 'vaccine_period'=>'6W'])
                                    ->whereIn('vaccine_name', ['FIPV','IPV'])
                                    ->orderBy('vaccinated_date_np')->get()->first();
        if(count($fipv)>0){
            if(count($fipv)>0){
                return \Carbon\Carbon::parse($fipv->vaccinated_date_np)->format('d/m/Y');
            }
        }
    }

    public function getIPV14W($token){
        $ipv = VaccinationRecord::where(['baby_token'=> $token, 'vaccine_period'=>'14W'])
                                    ->whereIn('vaccine_name', ['FIPV','IPV'])
                                    ->orderBy('vaccinated_date_np')->get()->first();
        if(count($ipv)>0){
            if(count($ipv)>0){
                return \Carbon\Carbon::parse($ipv->vaccinated_date_np)->format('d/m/Y');
            }
        }
    }

    public function getRV6W($token){
        $rv = VaccinationRecord::where(['baby_token'=> $token, 'vaccine_name'=>'RV', 'vaccine_period'=>'6W'])->orderBy('vaccinated_date_np')->get()->first();
        if(count($rv)>0){
            if(count($rv)>0){
                return \Carbon\Carbon::parse($rv->vaccinated_date_np)->format('d/m/Y');
            }
        }
    }

    public function getRV10W($token){
        $rv = VaccinationRecord::where(['baby_token'=> $token, 'vaccine_name'=>'RV', 'vaccine_period'=>'10W'])->orderBy('vaccinated_date_np')->get()->first();
        if(count($rv)>0){
            if(count($rv)>0){
                return \Carbon\Carbon::parse($rv->vaccinated_date_np)->format('d/m/Y');
            }
        }
    }


    public function getMR9M($token){
        $mr = VaccinationRecord::where(['baby_token'=> $token, 'vaccine_name'=>'MR', 'vaccine_period'=>'9M'])->orderBy('vaccinated_date_np')->get()->first();
        if(count($mr)>0){
            if(count($mr)>0){
                return \Carbon\Carbon::parse($mr->vaccinated_date_np)->format('d/m/Y');
            }
        }
    }

    public function getMR15M($token){
        $mr = VaccinationRecord::where(['baby_token'=> $token, 'vaccine_name'=>'MR', 'vaccine_period'=>'15M'])->orderBy('vaccinated_date_np')->get()->first();
        if(count($mr)>0){
            if(count($mr)>0){
                return \Carbon\Carbon::parse($mr->vaccinated_date_np)->format('d/m/Y');
            }
        }
    }

    public function getJE12M($token){
        $je = VaccinationRecord::where(['baby_token'=> $token, 'vaccine_name'=>'JE', 'vaccine_period'=>'12M'])->orderBy('vaccinated_date_np')->get()->first();
        if(count($je)>0){
            if(count($je)>0){
                return \Carbon\Carbon::parse($je->vaccinated_date_np)->format('d/m/Y');
            }
        }
    }

    public function babyVaccineDetails()
    {
        return $this->hasMany('App\Models\VaccinationRecord', 'baby_token', 'baby_token');
    }

    public function babyWeights()
    {
        return $this->hasMany('App\Models\BabyWeight', 'baby_token', 'token'); 
    }
    
    public function aefisBaby()
    {
        return $this->hasMany('App\Models\Aefi', 'baby_token', 'token'); 
    }

    public function scopeIsAlive($query)
    {
        return $query->where('baby_alive', 'Alive');
    }

    public function scopeIsDeadMacerated($query)
    {
        return $query->where('baby_alive', 'Dead-Macerated');
    }

    public function scopeIsDeadFresh($query)
    {
        return $query->where('baby_alive', 'Dead-Fresh');
    }

    public function scopewithInDobThreeYears($query){
        return $query->where('dob_en', '>', Carbon::now()->subYears(3));
    }

    public function scopewithInDobTwoYears($query){
        return $query->where('dob_en', '>', Carbon::now()->subYears(2));
    }
}