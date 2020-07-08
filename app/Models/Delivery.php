<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use App\Helpers\ViewHelper; 
use Yagiten\Nepalicalendar\Calendar;
use Auth;

class Delivery extends Model
{
    use SoftDeletes;
    protected $table='deliveries';
    protected $dates =['deleted_at'];
    protected $fillable = ['token','woman_token','delivery_date','delivery_time','delivery_place','presentation','delivery_type','compliexicty','other_problem','advice','miscarriage_status','hp_code','delivery_by_token','status', 'created_at','updated_at'];

    public function woman(){
        return $this->belongsTo('App\Models\Woman', 'woman_token', 'token');
    }

    public function baby()
    {
        return $this->hasMany('App\Models\BabyDetail', 'delivery_token', 'token');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeFromToDate($query, $from, $to)
    {
        return $query->whereBetween('delivery_date', [$from, $to]);
    }

    public function getFillable()
    {
        return $this->fillable;
    }

    public static function getLoggedInHealthpost($womanToken){
        $woman = Woman::where('token', $womanToken)->get()->first();
        if(count($woman)>0)
            return $woman->hp_code;
    }
    

    public static function getHealthpost($hp_code)
    {
        $healthpost = Healthpost::where('hp_code',$hp_code)->get()->first();
        if(count($healthpost)>0){
            return $healthpost->name;
        }
    }

    public function safeMaternityProgramDelivery($province_id,$district_id,$municipality_id,$ward_no,$hp_code, $from_date, $to_date){

        $data = DB::table('deliveries')
                        ->select('deliveries.delivery_place', 'deliveries.token', 'baby_details.weight as baby_weight', 'women.token as woman_token','baby_details.token as baby_token', 'baby_details.gender', 'health_workers.role as healthworker_role','deliveries.delivery_type','deliveries.presentation','baby_details.baby_alive')
                        ->join('women','deliveries.woman_token','=','women.token')
                        ->join('baby_details','deliveries.token','=','delivery_token')
                        ->leftjoin('health_workers','deliveries.delivery_by','=','health_workers.token')
                        ->where([['deliveries.status','1'],['baby_details.status','1']]);

        if($hp_code!=""){
            $data->where('women.hp_code', $hp_code);
        }elseif($municipality_id!="" && $ward_no!=""){
            $data->join('healthposts', 'healthposts.hp_code','=','women.hp_code')
                 ->where([['healthposts.municipality_id', $municipality_id],['healthposts.ward_no',$ward_no]]);
        }elseif($municipality_id!=""){
            $data->join('healthposts', 'healthposts.hp_code','=','women.hp_code')
                 ->where('healthposts.municipality_id', $municipality_id);
        }elseif($district_id!=""){
            $data->join('healthposts', 'healthposts.hp_code','=','women.hp_code')
                 ->where('healthposts.district_id', $district_id);
        }elseif($province_id!=""){
            $data->join('healthposts', 'healthposts.hp_code','=','women.hp_code')
                 ->where('healthposts.province_id', $province_id);
        }

        if($from_date!=""){
            $from_date_array = explode("-", $from_date);
            $from_date_eng = Calendar::nep_to_eng($from_date_array[0],$from_date_array[1],$from_date_array[2])->getYearMonthDay();
			if($to_date != ''){
				$to_date_array = explode("-", $to_date);
				$to_date_eng = Calendar::nep_to_eng($to_date_array[0],$to_date_array[1],$to_date_array[2])->getYearMonthDay();
				$data->whereBetween('women.created_at', [$from_date_eng, $to_date_eng]);
			}
			$to = date("Y-m-d");
			$data->whereBetween('women.created_at', [$from_date_eng, $to]);
        }

        $deliveries = $data->get();

        
        $deliveryTokens = array();
        $womenDeliveriedWithFchvAtHome = 0;
        $womenDeliveriedWithDoctorAtHome = count($deliveries->filter(function ($delivery) {
            return $delivery->healthworker_role == 'healthworker' && $delivery->delivery_place=="Home";
        }));
        $womenDeliveriedWithFchvAtHealthFacility = 0;
        $womenDeliveriedWithDoctorAtHealthFacility = count($deliveries->filter(function ($delivery) {
            return $delivery->healthworker_role == 'healthworker' && $delivery->delivery_place=="Health-Organization";
        }));

        $babyAliveMother = array();

        ##presentation && delivery_type
        $cephalicNormal = count($deliveries->filter(function ($delivery) {
            return $delivery->delivery_type=='Normal' && $delivery->presentation == 'Cephalic';
        }));
        $cephalicVacuum_forcep = count($deliveries->filter(function ($delivery) {
            return $delivery->delivery_type=='Vacuum/Forcep' && $delivery->presentation == 'Cephalic';
        }));
        $cephalicCS = count($deliveries->filter(function ($delivery) {
            return $delivery->delivery_type=='C/S' && $delivery->presentation == 'Cephalic';
        }));
        $breechNormal = count($deliveries->filter(function ($delivery) {
            return $delivery->delivery_type=='Normal' && $delivery->presentation == 'Breech';
        }));
        $breechVacuum_forcep = count($deliveries->filter(function ($delivery) {
            return $delivery->delivery_type=='Vacuum/Forcep' && $delivery->presentation == 'Breech';
        }));
        $breechCS = count($deliveries->filter(function ($delivery) {
            return $delivery->delivery_type=='C/S' && $delivery->presentation == 'Breech';
        }));
        $shoulderNormal = count($deliveries->filter(function ($delivery) {
            return $delivery->delivery_type=='Normal' && $delivery->presentation == 'Shoulder';
        }));
        $shoulderVacuum_forcep = count($deliveries->filter(function ($delivery) {
            return $delivery->delivery_type=='Vacuum/Forcep' && $delivery->presentation == 'Shoulder';
        }));
        $shoulderCS = count($deliveries->filter(function ($delivery) {
            return $delivery->delivery_type=='C/S' && $delivery->presentation == 'Shoulder';
        }));
        $deadMacerated = count($deliveries->filter(function ($delivery) {
            return $delivery->baby_alive=='Dead-Macerated';
        }));
        $deadFresh = count($deliveries->filter(function ($delivery) {
            return $delivery->baby_alive=='Dead-Fresh';
        }));
        $babyAliveMother = $deliveries->filter(function ($delivery) {
            return $babyAliveMother[] = $delivery->woman_token;
        });

         // dd($deliveries);
        
        $weightLess200gmBaby = count($deliveries->filter(function ($delivery) {
            return $delivery->baby_weight < 2000;
        }));
        $weightLess200to250gmBaby = count($deliveries->filter(function ($delivery) {
            return $delivery->baby_weight >= 2000 && $delivery->baby_weight < 2500;
        }));
        $weightMore250gmBaby = count($deliveries->filter(function ($delivery) {
            return $delivery->baby_weight >= 2500;
        }));

        // $childs = [];
       
        $collection =  $data->addSelect(DB::raw('count(*) as count'))->groupBy('token');

        $record = $collection->get();
 
        //dd($record);
        $singleChildMother = count($record->filter(function ($delivery) {
            return $delivery->count == 1;
        }));
        $doubleChildMother = count($record->filter(function ($delivery) {
            return $delivery->count == 2;
        }));
        $tripleMoreChildMother = count($record->filter(function ($delivery) {
            return $delivery->count >2;
        }));

        $singleMaleChild = count($record->filter(function ($delivery) {
            return $delivery->count == 1 && $delivery->gender =='Male';
        }));  
        $singleFemaleChild = count($record->filter(function ($delivery) {
            return $delivery->count == 1 && $delivery->gender =='Female';
        }));     
        // dd($singleFemaleChild);
        $doubleMaleChild = 0;
        $doubleFemaleChild = 0;
        $tripleMoreMaleChild = 0;
        $tripleMoreFemaleChild = 0;

        $deliveries = compact(
                            'singleChildMother',
                            'doubleChildMother',
                            'tripleMoreChildMother',
                            'womenDeliveriedWithFchvAtHome',
                            'womenDeliveriedWithDoctorAtHome',
                            'womenDeliveriedWithFchvAtHealthFacility',
                            'womenDeliveriedWithDoctorAtHealthFacility',
                            'presentationDelivery_type',
                            'cephalicNormal',
                            'cephalicVacuum_forcep',
                            'cephalicCS',
                            'breechNormal',
                            'breechVacuum_forcep',
                            'breechCS',
                            'shoulderNormal',
                            'shoulderVacuum_forcep',
                            'shoulderCS',
                            'deadMacerated',
                            'deadFresh'
                        );


        $childAlive = compact(
                            'weightLess200gmBaby',
                            'weightLess200to250gmBaby',
                            'weightMore250gmBaby',
                            'singleMaleChild',
                            'singleFemaleChild',
                            'doubleMaleChild',
                            'doubleFemaleChild',
                            'tripleMoreMaleChild',
                            'tripleMoreFemaleChild'
                        );

        $data = array_merge($deliveries, $childAlive);

        return $data;
    }

    public function deliveryServiceAccordingToCastes($request){

        $request = (new Woman)->womanRequestFiscalYear($request);

        $fiscalYear = $request['fiscal_year'];

        if($fiscalYear==""){
            $currentEnglishDate = date('Y-m-d');
            $nepaliDate  = ViewHelper::convertEnglishToNepali($currentEnglishDate);
            list($nepaliYear, $nepaliMonth, $nepaliDay) = explode('-', $nepaliDate);
        }else{
            $nepaliYear = $fiscalYear;
        }
        
        $startingOfFiscalYearMonth = $nepaliYear.'-'.'04-01';
        $fiscalYearMonthInEnglish = ViewHelper::convertNepaliToEnglish($startingOfFiscalYearMonth);

        $deliveries =array();
        for ($i=0; $i <12; $i++) { 
            $fiscalYearMonthInEnglishNext = date('Y-m-d', strtotime($fiscalYearMonthInEnglish. ' + 1 months'));
            $deliveries[] = $this->deliveryServiceAccordingToCastesMonthWise($request, $fiscalYearMonthInEnglish, $fiscalYearMonthInEnglishNext);
            $fiscalYearMonthInEnglish = $fiscalYearMonthInEnglishNext;
        }

         

        $response[] = $deliveries;
        $response[] = $request;

        return $response;

    }

    protected function deliveryServiceAccordingToCastesMonthWise($requests, $from_date , $to_date){

        foreach ($requests as $key => $value) {
            $$key = $value;
        }

        if(Auth::user()->role=="healthpost"){
            $hp_code="";
            $ward_no= "";
            $healthpost = Healthpost::where('token',Auth::user()->token)->get()->first();
            if(count($hp_code)>0){
                $hp_code = $healthpost->hp_code; 
                $ward_no = $healthpost->ward_no; 
            }
        }elseif(Auth::user()->role=="healthworker"){
            $hp_code="";
            $ward_no= "";
            $healthworker = HealthWorker::where('token', Auth::user()->token)->get()->first();
            if(count($healthworker)>0){
                $hp_code = $healthworker->hp_code; 
                $ward_no = $healthworker->ward; 
            }
        }

        $deliveries = DB::table('deliveries')
                    ->select('women.token','women.caste')
                    ->join('women','deliveries.woman_token','=','women.token')
                    ->where('deliveries.status','1')
                    ->whereBetween('deliveries.delivery_date', [$from_date, $to_date]);

        if($hp_code!=""){
            $deliveries->where('women.hp_code', $hp_code);
        }elseif($municipality_id!="" && $ward_no!=""){
            $deliveries->join('healthposts', 'healthposts.hp_code','=','women.hp_code')
                 ->where([['healthposts.municipality_id', $municipality_id],['healthposts.ward_no',$ward_no]]);
        }elseif($municipality_id!=""){
            $deliveries->join('healthposts', 'healthposts.hp_code','=','women.hp_code')
                 ->where('healthposts.municipality_id', $municipality_id);
        }elseif($district_id!=""){
            $deliveries->join('healthposts', 'healthposts.hp_code','=','women.hp_code')
                 ->where('healthposts.district_id', $district_id);
        }elseif($province_id!=""){
            $deliveries->join('healthposts', 'healthposts.hp_code','=','women.hp_code')
                 ->where('healthposts.province_id', $province_id);
        }
                    

        $deliveries = $deliveries
                        ->groupBy('women.caste','women.token')
                        ->get();

        return $deliveries;
    }

    public function delete()
    {
        $this->baby()->delete();
        return parent::delete(); // TODO: Change the autogenerated stub
    }

}
