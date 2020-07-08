<?php

namespace App\Models\VaccinationRecord;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Ward;
use App\Models\Healthpost;
use App\Models\HealthWorker;
use App\Models\Province;
use App\Models\Municipality;
use App\Models\District;
use App\Helpers\DateHelper;
use Yagiten\NepaliCalendar\Calendar;
use App\Helpers\ViewHelper;
use App\Models\Woman;
use App\Models\Anc;
use App\Models\VaccineVialStock;

use Auth;

class VaccinationRecord extends Model
{
    protected $table = 'vaccination_records';
    public $timestamps = false;

    public function report($request)
    {
        $requests = $this->reportRequest($request);

        foreach ($requests as $key => $value) {
            $$key = $value;
        }

        $immunizedChild = $this->immunizedChild($province_id,$district_id,$municipality_id,$ward_no,$hp_code,$from_date,$to_date);

        $vailStock = (new VaccineVialStock)->receivedExpenseDose($province_id,$district_id,$municipality_id,$ward_no,$hp_code,$from_date,$to_date);    

        // $aefiCases = (new Aefi)->aefiCases($province_id,$district_id,$municipality_id,$ward_no,$hp_code,$from_date,$to_date);   

        $data = compact('immunizedChild', 'vailStock');

        $response[] = $data;
        $response[] = $requests;

        return $response;
    }

    public function reportRequest($request){
        $province_id = $request->get('province_id'); 
        $district_id = $request->get('district_id'); 
        $municipality_id = $request->get('municipality_id'); 
        $ward_id = $request->get('ward_id'); 
        $ward_no = Ward::getWardNo($ward_id);
        $hp_code = $request->get('hp_code');
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');
        $loggedInToken = Auth::user()->token;


        if(Auth::user()->role=="province"){
            $province_id = Province::modelProvinceInfo($loggedInToken)->province_id;
            $provinces = Province::where('id', $province_id)->orderBy('province_name', 'asc')->get();
            $districts = District::where('province_id', $province_id)->orderBy('district_name', 'asc')->get();
            $municipalities = Municipality::where('district_id', $district_id)->orderBy('municipality_name', 'asc')->get();
            $wards = Ward::where('municipality_id',$municipality_id)->orderBy('ward_no', 'asc')->get();     
            $healthposts = Healthpost::where([['ward_no',$ward_no],['municipality_id',$municipality_id]])->orderBy('name', 'asc')->get();
        }elseif(Auth::user()->role=="dho"){
            $district_id = District::modelDistrictInfo($loggedInToken)->district_id;
            $province_id = District::provinceIdByDistrictId($district_id);
            $provinces = Province::where('id', $province_id)->orderBy('province_name', 'asc')->get();
            $districts = District::where('id', $district_id)->orderBy('district_name', 'asc')->get();
            $municipalities = Municipality::where('district_id', $district_id)->orderBy('municipality_name', 'asc')->get();
            $wards = Ward::where('municipality_id',$municipality_id)->orderBy('ward_no', 'asc')->get();     
            $healthposts = Healthpost::where([['ward_no',$ward_no],['municipality_id',$municipality_id]])->orderBy('name', 'asc')->get();
        }elseif(Auth::user()->role=="municipality"){

            $municipality_id = Municipality::modelMunicipalityInfo($loggedInToken)->municipality_id;
            $district_id = Municipality::modelMunicipalityInfo($loggedInToken)->district_id;
            $province_id = Municipality::modelMunicipalityInfo($loggedInToken)->province_id;
            $provinces = Province::where('id', $province_id)->orderBy('province_name', 'asc')->get();
            $districts = District::where('id', $district_id)->orderBy('district_name', 'asc')->get();
            $municipalities = Municipality::where('id', $municipality_id)->orderBy('municipality_name', 'asc')->get();
            $wards = Ward::where('municipality_id',$municipality_id)->orderBy('ward_no', 'asc')->get();     
            $healthposts = Healthpost::where([['ward_no',$ward_no],['municipality_id',$municipality_id]])->orderBy('name', 'asc')->get();
        }elseif(Auth::user()->role=="ward"){
            $ward_id = Ward::modelWard($loggedInToken)->id;
            $ward_no = Ward::getWardNo($ward_id);
            $municipality_id = Ward::modelWard($loggedInToken)->municipality_id;
            $district_id = Ward::modelWard($loggedInToken)->district_id;
            $province_id = Ward::modelWard($loggedInToken)->province_id;
            $provinces = Province::where('id', $province_id)->orderBy('province_name', 'asc')->get();
            $districts = District::where('id', $district_id)->orderBy('district_name', 'asc')->get();
            $municipalities = Municipality::where('id', $municipality_id)->orderBy('municipality_name', 'asc')->get();
            $wards = Ward::where('id',$ward_id)->orderBy('ward_no', 'asc')->get();     
            $healthposts = Healthpost::where([['ward_no',$ward_no],['municipality_id',$municipality_id]])->orderBy('name', 'asc')->get();

        }elseif(Auth::user()->role=="healthpost"){
            $healthpost_id = Healthpost::modelHealthpost($loggedInToken)->id;
            $hp_code = Healthpost::modelHealthpost($loggedInToken)->hp_code;
            $ward_no = Healthpost::modelHealthpost($loggedInToken)->ward_no;
            $municipality_id = Healthpost::modelHealthpost($loggedInToken)->municipality_id;
            $district_id = Healthpost::modelHealthpost($loggedInToken)->district_id;
            $province_id = Healthpost::modelHealthpost($loggedInToken)->province_id;
            $provinces = Province::where('id', $province_id)->orderBy('province_name', 'asc')->get();
            $districts = District::where('id', $district_id)->orderBy('district_name', 'asc')->get();
            $municipalities = Municipality::where('id', $municipality_id)->orderBy('municipality_name', 'asc')->get();
            $wards = Ward::where([['ward_no',$ward_no],['municipality_id',$municipality_id]])->orderBy('ward_no', 'asc')->get();
            $healthposts = Healthpost::where('id',$healthpost_id)->orderBy('name', 'asc')->get();

        }elseif(Auth::user()->role=="healthworker"){
            $hp_code = HealthWorker::where('token', $loggedInToken)->get()->first()->hp_code;
            $healthpost = Healthpost::where('hp_code',$hp_code)->get()->first();
            $ward_no = $healthpost->ward_no;
            $municipality_id = $healthpost->municipality_id;
            $district_id = $healthpost->district_id;
            $province_id = $healthpost->province_id;
            $provinces = Province::where('id', $province_id)->orderBy('province_name', 'asc')->get();
            $districts = District::where('id', $district_id)->orderBy('district_name', 'asc')->get();
            $municipalities = Municipality::where('id', $municipality_id)->orderBy('municipality_name', 'asc')->get();
            $wards = Ward::where([['ward_no',$ward_no],['municipality_id',$municipality_id]])->orderBy('ward_no', 'asc')->get();
            $ward= Ward::where([['ward_no',$ward_no],['municipality_id',$municipality_id]])->orderBy('ward_no', 'asc')->get()->first();
            if(count($ward)>0){
                $ward_id = $ward->id;
            }
            $healthposts = Healthpost::where('hp_code',$hp_code)->orderBy('name', 'asc')->get();

        }else{
            $provinces = Province::all();
            $districts = District::where('province_id', $province_id)->orderBy('district_name', 'asc')->get();
            $municipalities = Municipality::where('district_id', $district_id)->orderBy('municipality_name', 'asc')->get();
            $wards = Ward::where('municipality_id',$municipality_id)->orderBy('ward_no', 'asc')->get();     
            $healthposts = Healthpost::where([['ward_no',$ward_no],['municipality_id',$municipality_id]])->orderBy('name', 'asc')->get();
        }

        $request = compact('provinces','districts','municipalities','wards','healthposts','province_id','district_id','municipality_id','ward_id','ward_no','hp_code','from_date','to_date');

        return $request;
    }

    public function immunizedChild($province_id,$district_id,$municipality_id,$ward_no,$hp_code,$from_date,$to_date){
        $bcgFirst = 0;
        $pvFirst = 0;
        $pvSecond = 0;
        $pvThird = 0;
        $opvFirst = 0;
        $opvSecond = 0;
        $opvThird = 0;
        $pcvFirst = 0;
        $pcvSecond = 0;
        $pcvThird = 0;
        $fipvFirst = 0;
        $fipvSecond = 0;
        $mrFirst = 0;
        $mrSecond = 0;
        $jeFirst = 0;
        $rvFirst = 0;
        $rvSecond = 0;
        $pvThirdAndOpvThirdAfeterOneYear = 0;

        $imuunizedRecord = DB::table('baby_details')
            ->select(DB::raw("baby_details.dob_en, vaccination_records.vaccinated_date_en, vaccination_records.vial_image,MAX(IF(vaccination_records.vaccine_name = 'BCG' AND vaccine_period='Birth', vaccinated_date_en, NULL)) AS bcgFirst, MAX(IF(vaccination_records.vaccine_name = 'Pentavalent' AND vaccine_period='6W', vaccinated_date_en, NULL)) AS pvFirst, MAX(IF(vaccination_records.vaccine_name = 'Pentavalent' AND vaccine_period='10W', vaccinated_date_en, NULL)) AS pvSecond, MAX(IF(vaccination_records.vaccine_name = 'Pentavalent' AND vaccine_period='14W', vaccinated_date_en, NULL)) AS pvThird, MAX(IF(vaccination_records.vaccine_name = 'OPV' AND vaccine_period='6W', vaccinated_date_en, NULL)) AS opvFirst, MAX(IF(vaccination_records.vaccine_name = 'OPV' AND vaccine_period='10W', vaccinated_date_en, NULL)) AS opvSecond, MAX(IF(vaccination_records.vaccine_name = 'OPV' AND vaccine_period='14W', vaccinated_date_en, NULL)) AS opvThird, MAX(IF(vaccination_records.vaccine_name = 'PCV' AND vaccine_period='6W', vaccinated_date_en, NULL)) AS pcvFirst, MAX(IF(vaccination_records.vaccine_name = 'PCV' AND vaccine_period='10W', vaccinated_date_en, NULL)) AS pcvSecond, MAX(IF(vaccination_records.vaccine_name = 'PCV' AND vaccine_period='9M', vaccinated_date_en, NULL)) AS pcvThird, MAX(IF(vaccination_records.vaccine_name = 'FIPV' AND vaccine_period='6W', vaccinated_date_en, NULL)) AS fipvFirst, MAX(IF(vaccination_records.vaccine_name = 'FIPV' AND vaccine_period='14W', vaccinated_date_en, NULL)) AS fipvSecond, MAX(IF(vaccination_records.vaccine_name = 'MR' AND vaccine_period='9M', vaccinated_date_en, NULL)) AS mrFirst, MAX(IF(vaccination_records.vaccine_name = 'MR' AND vaccine_period='15M', vaccinated_date_en, NULL)) AS mrSecond, MAX(IF(vaccination_records.vaccine_name = 'JE' AND vaccine_period='12M', vaccinated_date_en, NULL)) AS jeFirst, MAX(IF(vaccination_records.vaccine_name = 'RV' AND vaccine_period='6W', vaccinated_date_en, NULL)) AS rvFirst, MAX(IF(vaccination_records.vaccine_name = 'RV' AND vaccine_period='10W', vaccinated_date_en, NULL)) AS rvSecond"))
            ->leftjoin('vaccination_records','baby_details.token', '=', 'vaccination_records.baby_token')
            ->leftjoin('vaccine_vials', 'vaccination_records.vial_image', '=', 'vaccine_vials.vial_image')
            ->groupBy("vaccination_records.vaccinated_date_en","vaccination_records.vial_image","baby_details.dob_en");
        //echo $imuunizedRecord->toSql(); exit;

        if($hp_code!=""){
            $imuunizedRecord->where('vaccination_records.hp_code', $hp_code);
        }elseif($municipality_id!="" && $ward_no!=""){
            $imuunizedRecord->join('healthposts', 'healthposts.hp_code','=','vaccination_records.hp_code')
                 ->where([['healthposts.municipality_id', $municipality_id],['healthposts.ward_no',$ward_no]]);
        }elseif($municipality_id!=""){
            $imuunizedRecord->join('healthposts', 'healthposts.hp_code','=','vaccination_records.hp_code')
                 ->where('healthposts.municipality_id', $municipality_id);
        }elseif($district_id!=""){
            $imuunizedRecord->join('healthposts', 'healthposts.hp_code','=','vaccination_records.hp_code')
                 ->where('healthposts.district_id', $district_id);
        }elseif($province_id!=""){
            $imuunizedRecord->join('healthposts', 'healthposts.hp_code','=','vaccination_records.hp_code')
                 ->where('healthposts.province_id', $province_id);
        }


        if($from_date!="" && $to_date!=""){
            $from_date_array = explode("-", $from_date);
            $from_date_eng = Calendar::nep_to_eng($from_date_array[0],$from_date_array[1],$from_date_array[2])->getYearMonthDay();
            $to_date_array = explode("-", $to_date);
            $to_date_eng = Calendar::nep_to_eng($to_date_array[0],$to_date_array[1],$to_date_array[2])->getYearMonthDay();
            $imuunizedRecord->whereBetween('vaccination_records.created_at', [$from_date_eng, $to_date_eng]);
        }

        $imuunizedRecord = $imuunizedRecord->get();

        foreach ($imuunizedRecord as $data) {
            if($data->vial_image!=""){
            
                if($data->bcgFirst!==NULL){
                    $bcgFirst++;
                }

                if($data->pvFirst!==NULL){
                    $pvFirst++;
                }

                if($data->pvSecond!==NULL){
                    $pvSecond++;
                }

                if($data->pvThird!==NULL){
                    $pvThird++;
                    if((new DateHelper)->days_between($data->dob_en, $data->vaccinated_date_en)>365){
                        $pvThirdAndOpvThirdAfeterOneYear++;
                    }
                }

                if($data->opvFirst!==NULL){
                    $opvFirst++;
                }

                if($data->opvSecond!==NULL){
                    $opvSecond++;
                }

                 if($data->opvThird!==NULL){
                    $opvThird++;
                    if((new DateHelper)->days_between($data->dob_en, $data->vaccinated_date_en)>365){
                        $pvThirdAndOpvThirdAfeterOneYear++;
                    }
                }

                 if($data->pcvFirst!==NULL){
                    $pcvFirst++;
                }

                 if($data->pcvSecond!==NULL){
                    $pcvSecond++;
                }

                 if($data->pcvThird!==NULL){
                    $pcvThird++;
                }

                 if($data->fipvFirst!==NULL){
                    $fipvFirst++;
                }

                 if($data->fipvSecond!==NULL){
                    $fipvSecond++;
                }

                 if($data->mrFirst!==NULL){
                    $mrFirst++;
                }

                 if($data->mrSecond!==NULL){
                    $mrSecond++;
                }

                 if($data->jeFirst!==NULL){
                    $jeFirst++;
                }

                 if($data->rvFirst!==NULL){
                    $rvFirst++;
                }

                 if($data->rvSecond!==NULL){
                    $rvSecond++;
                }
            }
        }

        $immunizedTdVaccineWoman = (new Anc)->immunizedTdVaccineWoman($province_id,$district_id,$municipality_id,$ward_no,$hp_code,$from_date,$to_date);

        foreach ($immunizedTdVaccineWoman as $key => $value) {
            $$key = $value;
        }

            return [
            'bcgFirst' => $bcgFirst,
            'pvFirst' => $pvFirst,
            'pvSecond' => $pvSecond,
            'pvThird' => $pvThird,
            'opvFirst' => $opvFirst,
            'opvSecond' => $opvSecond,
            'opvThird' => $opvThird,
            'pcvFirst' => $pcvFirst,
            'pcvSecond' => $pcvSecond,
            'pcvThird' => $pcvThird,
            'fipvFirst' => $fipvFirst,
            'fipvSecond' => $fipvSecond,
            'mrFirst' => $mrFirst,
            'mrSecond' => $mrSecond,
            'jeFirst' => $jeFirst,
            'rvFirst' => $rvFirst,
            'rvSecond' => $rvSecond,
            'pvThirdAndOpvThirdAfeterOneYear'=>$pvThirdAndOpvThirdAfeterOneYear,
            'tdFirst'=>$tdFirst,
            'tdSecond'=>$tdSecond,
            'tdThird'=>$tdThird
        ];
    }

    public function immunizedBCGVaccine($province_id,$district_id,$municipality_id,$ward_no,$hp_code,$from_date,$to_date)
    {
        $imuunizedRecord = DB::table('baby_details')
            ->select(DB::raw("baby_details.dob_en, vaccination_records.vaccinated_date_en, MAX(IF(vaccination_records.vaccine_name = 'BCG' AND vaccine_period='Birth', vaccinated_date_en, NULL)) AS bcgFirst"))
            ->leftjoin('vaccination_records','baby_details.token', '=', 'vaccination_records.baby_token')
            ->leftjoin('vaccine_vials', 'vaccination_records.vial_image', '=', 'vaccine_vials.vial_image')
            ->groupBy("vaccination_records.vaccinated_date_en","vaccination_records.vial_image","baby_details.dob_en")->count();
    }

}