<?php

namespace App\Models\VialDetail;

use Illuminate\Database\Eloquent\Model;
use App\Models\Woman;
use App\Models\Anc;
use App\Models\VaccinationRecord;
use App\Models\VaccineVialStock;
use App\Models\Aefi;
use DB;
use Yagiten\Nepalicalendar\Calendar;

class VialDetail extends Model
{
    protected $table = 'vial_details';
    protected $fillable = ['hp_code','vaccine_name', 'maximum_doses','vial_damaged_reason','vial_image', 'vial_used_doses', 'vial_wastage_doses', 'vial_opened_date', 'status', 'created_at', 'updated_at' ];
    public $timestamps = false;
    
    public function vaccinationProgramReport($request){

        $requests = (new Woman)->womanRequest($request);
    
        foreach ($requests as $key => $value) {
            $$key = $value;
        }
    
        $immunizedChild = $this->immunizedChild($province_id,$district_id,$municipality_id,$ward_no,$hp_code,$from_date,$to_date);
    
        $vailStock = (new VaccineVialStock)->receivedExpenseDose($province_id,$district_id,$municipality_id,$ward_no,$hp_code,$from_date,$to_date);    
    
        $aefiCases = (new Aefi)->aefiCases($province_id,$district_id,$municipality_id,$ward_no,$hp_code,$from_date,$to_date);   

        $data = compact('immunizedChild', 'vailStock', 'aefiCases');
    
        $response[] = $data;
        $response[] = $requests;
    
        return $response;
    
        }

        public function immunizedChild($province_id,$district_id,$municipality_id,$ward_no,$hp_code,$from_date,$to_date){
                
            $imuunizedRecord = DB::table('vaccination_records')->where('vial_image','!=','');
       
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
    
            // dd($imuunizedRecord->toSql());
            if($from_date!=""){
                $from_date_array = explode("-", $from_date);
                $from_date_eng = Calendar::nep_to_eng($from_date_array[0],$from_date_array[1],$from_date_array[2])->getYearMonthDay();
                if($to_date != ''){
                    $to_date_array = explode("-", $to_date);
                    $to_date_eng = Calendar::nep_to_eng($to_date_array[0],$to_date_array[1],$to_date_array[2])->getYearMonthDay();
                    $imuunizedRecord->whereBetween('vaccination_records.created_at', [$from_date_eng, $to_date_eng]);
                }
                $to = date("Y-m-d");
                $imuunizedRecord->whereBetween('vaccination_records.created_at', [$from_date_eng, $to]);
            }
    
            $imuunizedRecord = $imuunizedRecord->get();
    
            $bcgFirst = $imuunizedRecord->where('vaccine_name','BCG')->where('vaccine_period','Birth')->count();
            $pvFirst = $imuunizedRecord->where('vaccine_name','Pentavalent')->where('vaccine_period','6W')->count();
            $pvSecond = $imuunizedRecord->where('vaccine_name','Pentavalent')->where('vaccine_period','10W')->count();
            $pvThird = $imuunizedRecord->where('vaccine_name','Pentavalent')->where('vaccine_period','14W')->count();
            $opvFirst = $imuunizedRecord->where('vaccine_name','OPV')->where('vaccine_period','6W')->count();
            $opvSecond = $imuunizedRecord->where('vaccine_name','OPV')->where('vaccine_period','10W')->count();
            $opvThird = $imuunizedRecord->where('vaccine_name','OPV')->where('vaccine_period','14W')->count();
            $pcvFirst = $imuunizedRecord->where('vaccine_name','PCV')->where('vaccine_period','6W')->count();
            $pcvSecond = $imuunizedRecord->where('vaccine_name','PCV')->where('vaccine_period','10W')->count();
            $pcvThird = $imuunizedRecord->where('vaccine_name','PCV')->where('vaccine_period','9M')->count();
            $mrFirst = $imuunizedRecord->where('vaccine_name','MR')->where('vaccine_period','9M')->count();
            $mrSecond = $imuunizedRecord->where('vaccine_name','MR')->where('vaccine_period','15M')->count();
            $jeFirst = $imuunizedRecord->where('vaccine_name','JE')->where('vaccine_period','12M')->count();

            $rotaFirst = $imuunizedRecord->where('vaccine_name','Rota')->where('vaccine_period','6W')->count();
            $rotaSecond = $imuunizedRecord->where('vaccine_name','Rota')->where('vaccine_period','10W')->count();

            $fipvFirst = $imuunizedRecord->where('vaccine_name','FIPV')->where('vaccine_period','6W')->count();
            $fipvSecond = $imuunizedRecord->where('vaccine_name','FIPV')->where('vaccine_period','14W')->count();

            // dd($bcgFirst);  
            
    
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
                'mrFirst' => $mrFirst,
                'mrSecond' => $mrSecond,
                'jeFirst' => $jeFirst,
                'tdFirst'=>$tdFirst,
                'tdSecond'=>$tdSecond,
                'tdThird'=>$tdThird,
                'rotaFirst' => $rotaFirst,
                'rotaSecond' => $rotaSecond,
                'fipvFirst' => $fipvFirst,
                'fipvSecond' => $fipvSecond
            ];
        }


}