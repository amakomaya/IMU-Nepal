<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Yagiten\NepaliCalendar\Calendar;

class VaccineVial extends Model
{
    protected $fillable = ['token', 'hp_code', 'vaccine_name','maximum_doses', 'vial_image', 'status', 'created_at', 'updated_at'];
    public function vaccineDetailList($province_id,$district_id,$municipality_id,$ward_no,$hp_code,$from_date,$to_date){

    	$imuunizedChild = DB::table('baby_details')
            ->select(DB::raw("baby_details.token, baby_details.baby_name, baby_details.caste, baby_details.gender, baby_details.mother_name, baby_details.father_name, baby_details.contact_no, baby_details.tole, baby_details.dob_en, vaccination_records.vaccinated_date_en, IF(vaccination_records.vaccine_name = 'BCG' AND vaccine_period='Birth', vaccinated_date_en, NULL) AS bcgFirst, IF(vaccination_records.vaccine_name = 'Pentavalent' AND vaccine_period='6W', vaccinated_date_en, NULL) AS pvFirst, IF(vaccination_records.vaccine_name = 'Pentavalent' AND vaccine_period='10W', vaccinated_date_en, NULL) AS pvSecond, IF(vaccination_records.vaccine_name = 'Pentavalent' AND vaccine_period='14W', vaccinated_date_en, NULL) AS pvThird, IF(vaccination_records.vaccine_name = 'OPV' AND vaccine_period='6W', vaccinated_date_en, NULL) AS opvFirst, IF(vaccination_records.vaccine_name = 'OPV' AND vaccine_period='10W', vaccinated_date_en, NULL) AS opvSecond, IF(vaccination_records.vaccine_name = 'OPV' AND vaccine_period='14W', vaccinated_date_en, NULL) AS opvThird, IF(vaccination_records.vaccine_name = 'PCV' AND vaccine_period='6W', vaccinated_date_en, NULL) AS pcvFirst, IF(vaccination_records.vaccine_name = 'PCV' AND vaccine_period='10W', vaccinated_date_en, NULL) AS pcvSecond, IF(vaccination_records.vaccine_name = 'PCV' AND vaccine_period='9M', vaccinated_date_en, NULL) AS pcvThird, IF(vaccination_records.vaccine_name = 'FIPV' AND vaccine_period='6W', vaccinated_date_en, NULL) AS fipvFirst, IF(vaccination_records.vaccine_name = 'FIPV' AND vaccine_period='14W', vaccinated_date_en, NULL) AS fipvSecond, IF(vaccination_records.vaccine_name = 'MR' AND vaccine_period='9M', vaccinated_date_en, NULL) AS mrFirst, IF(vaccination_records.vaccine_name = 'MR' AND vaccine_period='15M', vaccinated_date_en, NULL) AS mrSecond, IF(vaccination_records.vaccine_name = 'JE' AND vaccine_period='12M', vaccinated_date_en, NULL) AS jeFirst, IF(vaccination_records.vaccine_name = 'RV' AND vaccine_period='6W', vaccinated_date_en, NULL) AS rvFirst, IF(vaccination_records.vaccine_name = 'RV' AND vaccine_period='10W', vaccinated_date_en, NULL) AS rvSecond"))
            ->leftjoin('vaccination_records','baby_details.token', '=', 'vaccination_records.token');

        //echo $imuunizedChild->toSql(); exit;
        if($hp_code!=""){
            $imuunizedChild->where('baby_details.hp_code', $hp_code);
        }elseif($municipality_id!="" && $ward_no!=""){
            $imuunizedChild->join('healthposts', 'healthposts.hp_code','=','baby_details.hp_code')
                 ->where([['healthposts.municipality_id', $municipality_id],['healthposts.ward_no',$ward_no]]);
        }elseif($municipality_id!=""){
            $imuunizedChild->join('healthposts', 'healthposts.hp_code','=','baby_details.hp_code')
                 ->where('healthposts.municipality_id', $municipality_id);
        }elseif($district_id!=""){
            $imuunizedChild->join('healthposts', 'healthposts.hp_code','=','baby_details.hp_code')
                 ->where('healthposts.district_id', $district_id);
        }elseif($province_id!=""){
            $imuunizedChild->join('healthposts', 'healthposts.hp_code','=','baby_details.hp_code')
                 ->where('healthposts.province_id', $province_id);
        }


        if($from_date!="" && $to_date!=""){
            $from_date_array = explode("-", $from_date);
            $from_date_eng = Calendar::nep_to_eng($from_date_array[0],$from_date_array[1],$from_date_array[2])->getYearMonthDay();
            $to_date_array = explode("-", $to_date);
            $to_date_eng = Calendar::nep_to_eng($to_date_array[0],$to_date_array[1],$to_date_array[2])->getYearMonthDay();
            $imuunizedChild->whereBetween('baby_details.created_at', [$from_date_eng, $to_date_eng]);
        }

        $imuunizedChild = $imuunizedChild->get();

        $token = "";
        $result = array();
        $totalRecord = count($imuunizedChild);
        $i=0;
        $baby_name = NULL;
        $caste = NULL;
        $gender = NULL;
        $mother_name = NULL;
        $father_name = NULL;
        $contact_no = NULL;
        $tole = NULL;
        $dob_en = NULL;
        $bcgFirst = NULL;
        $pvFirst = NULL;
        $pvSecond = NULL;
        $pvThird = NULL;
        $opvFirst = NULL;
        $opvSecond = NULL;
        $opvThird = NULL;
        $pcvFirst = NULL;
        $pcvSecond = NULL;
        $pcvThird = NULL;
        $fipvFirst = NULL;
        $fipvSecond = NULL;
        $mrFirst = NULL;
        $mrSecond = NULL;
        $jeFirst = NULL;
        $rvFirst = NULL;
        $rvSecond = NULL;
        $pvThirdAndOpvThirdAfeterOneYear = NULL;
        foreach ($imuunizedChild as $record) {

            $i++;
            if($token==""){

                $token = $record->token;

                $elements = $this->getFields($record , $baby_name, $caste, $gender, $mother_name, $father_name, $contact_no, $tole, $dob_en, $bcgFirst, $pvFirst, $pvSecond, $pvThird, $opvFirst, $opvSecond, $opvThird, $pcvFirst, $pcvSecond, $pcvThird, $fipvFirst, $fipvSecond, $mrFirst, $mrSecond, $jeFirst, $rvFirst, $rvSecond, $pvThirdAndOpvThirdAfeterOneYear);

                foreach ($elements as $key => $value) {
                    $$key = $value;
                }
                
            }elseif($token!=$record->token){
                $result[] = [
                    'token' => $token,
                    'baby_name' => $baby_name,
                    'caste' => $caste,
                    'gender' => $gender,
                    'mother_name' => $mother_name,
                    'father_name' => $father_name,
                    'contact_no' => $contact_no,
                    'tole' => $tole,
                    'dob_en' => $dob_en,
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
                    'rvFirst' => $rvFirst,
                    'rvSecond' => $rvSecond,
                    'pvThirdAndOpvThirdAfeterOneYear'=>$pvThirdAndOpvThirdAfeterOneYear
                ];

                $baby_name = NULL;
                $caste = NULL;
                $gender = NULL;
                $mother_name = NULL;
                $father_name = NULL;
                $contact_no = NULL;
        		$tole = NULL;
                $dob_en = NULL;
                $bcgFirst = NULL;
                $pvFirst = NULL;
                $pvSecond = NULL;
                $pvThird = NULL;
                $opvFirst = NULL;
                $opvSecond = NULL;
                $opvThird = NULL;
                $pcvFirst = NULL;
                $pcvSecond = NULL;
                $pcvThird = NULL;
                $fipvFirst = NULL;
                $fipvSecond = NULL;
                $mrFirst = NULL;
                $mrSecond = NULL;
                $jeFirst = NULL;
                $rvFirst = NULL;
                $rvSecond = NULL;

                
                $elements = $this->getFields($record , $baby_name, $caste, $gender, $mother_name, $father_name, $contact_no,  $tole, $dob_en, $bcgFirst, $pvFirst, $pvSecond, $pvThird, $opvFirst, $opvSecond, $opvThird, $pcvFirst, $pcvSecond, $pcvThird, $fipvFirst, $fipvSecond, $mrFirst, $mrSecond, $jeFirst, $rvFirst, $rvSecond, $pvThirdAndOpvThirdAfeterOneYear);

                $token = $record->token;
                

                //Fill Variables
                foreach ($elements as $key => $value) {
                    $$key = $value;
                }
            }else{

                $elements = $this->getFields($record , $baby_name, $caste, $gender, $mother_name, $father_name, $contact_no, $tole, $dob_en, $bcgFirst, $pvFirst, $pvSecond, $pvThird, $opvFirst, $opvSecond, $opvThird, $pcvFirst, $pcvSecond, $pcvThird, $fipvFirst, $fipvSecond, $mrFirst, $mrSecond, $jeFirst, $rvFirst, $rvSecond, $pvThirdAndOpvThirdAfeterOneYear);

                foreach ($elements as $key => $value) {
                    $$key = $value;
                }
            }

            if($i==$totalRecord){

                $elements = $this->getFields($record , $baby_name, $caste, $gender, $mother_name, $father_name, $contact_no, $tole, $dob_en, $bcgFirst, $pvFirst, $pvSecond, $pvThird, $opvFirst, $opvSecond, $opvThird, $pcvFirst, $pcvSecond, $pcvThird, $fipvFirst, $fipvSecond, $mrFirst, $mrSecond, $jeFirst, $rvFirst, $rvSecond, $pvThirdAndOpvThirdAfeterOneYear);

                //Fill Variables
                foreach ($elements as $key => $value) {
                    $$key = $value;
                }
                $result[] = [
                    'token' => $token,
                    'baby_name' => $baby_name,
                    'caste' => $caste,
                    'gender' => $gender,
                    'mother_name' => $mother_name,
                    'father_name' => $father_name,
                    'contact_no' => $contact_no,
                    'tole' => $tole,
                    'dob_en' => $dob_en,
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
                    'rvFirst' => $rvFirst,
                    'rvSecond' => $rvSecond,
                    'pvThirdAndOpvThirdAfeterOneYear'=>$pvThirdAndOpvThirdAfeterOneYear
                ];
            }

            
        }// foreach

        return $result;

            
    }

    public function getFields($record , $baby_name, $caste, $gender, $mother_name, $father_name, $contact_no, $tole, $dob_en, $bcgFirst, $pvFirst, $pvSecond, $pvThird, $opvFirst, $opvSecond, $opvThird, $pcvFirst, $pcvSecond, $pcvThird, $fipvFirst, $fipvSecond, $mrFirst, $mrSecond, $jeFirst, $rvFirst, $rvSecond, $pvThirdAndOpvThirdAfeterOneYear){
        
        
        if($record->baby_name!==NULL){
            $baby_name = $record->baby_name;
        }  
        if($record->caste!==NULL){
            $caste = $record->caste;
        }  
        if($record->gender!==NULL){
            $gender = $record->gender;
        }  
        if($record->mother_name!==NULL){
            $mother_name = $record->mother_name;
        }  
        if($record->father_name!==NULL){
            $father_name = $record->father_name;
        }  
        if($record->contact_no!==NULL){
            $contact_no = $record->contact_no;
        }   
        if($record->tole!==NULL){
            $tole = $record->tole;
        } 
        if($record->dob_en!==NULL){
            $dob_en = $record->dob_en;
        }  
        if($record->bcgFirst!==NULL){
            $bcgFirst = $record->bcgFirst;
        }  
        if($record->pvFirst!==NULL){
            $pvFirst = $record->pvFirst;
        }  
        if($record->pvSecond!==NULL){
            $pvSecond = $record->pvSecond;
        }  
        if($record->pvThird!==NULL){
            $pvThird = $record->pvThird;

            if(Yii::$app->datecomponent->days_between($record->vaccinated_date_en, $record->dob_en)>360){
                if($pvThirdAndOpvThirdAfeterOneYear!==NULL){
                    if(Yii::$app->datecomponent->days_between($record->vaccinated_date_en, $record->dob_en)>Yii::$app->datecomponent->days_between($pvThirdAndOpvThirdAfeterOneYear, $record->dob_en)){
                        $pvThirdAndOpvThirdAfeterOneYear = $record->pcvThird;
                    }
                }else{
                    $pvThirdAndOpvThirdAfeterOneYear = $record->pcvThird;
                }
            }
        }  
        if($record->opvFirst!==NULL){
            $opvFirst = $record->opvFirst;
        }  
        if($record->opvSecond!==NULL){
            $opvSecond = $record->opvSecond;
        }  
        if($record->opvThird!==NULL){
            $opvThird = $record->opvThird;
            if(Yii::$app->datecomponent->days_between($record->vaccinated_date_en, $record->dob_en)>360){
                if($pvThirdAndOpvThirdAfeterOneYear!==NULL){
                    if(Yii::$app->datecomponent->days_between($record->vaccinated_date_en, $record->dob_en)>Yii::$app->datecomponent->days_between($pvThirdAndOpvThirdAfeterOneYear, $record->dob_en)){
                        $pvThirdAndOpvThirdAfeterOneYear = $record->opvThird;
                    }
                }else{
                    $pvThirdAndOpvThirdAfeterOneYear = $record->opvThird;
                }
            }
        }  
        if($record->pcvFirst!==NULL){
            $pcvFirst = $record->pcvFirst;
        }  
        if($record->pcvSecond!==NULL){
            $pcvSecond = $record->pcvSecond;
        }  
        if($record->pcvThird!==NULL){
            $pcvThird = $record->pcvThird;
        }  
        if($record->fipvFirst!==NULL){
            $fipvFirst = $record->fipvFirst;
        }  
        if($record->fipvSecond!==NULL){
            $fipvSecond = $record->fipvSecond;
        }  
        if($record->mrFirst!==NULL){
            $mrFirst = $record->mrFirst;
        }  
        if($record->mrSecond!==NULL){
            $mrSecond = $record->mrSecond;
        }  
        if($record->jeFirst!==NULL){
            $jeFirst = $record->jeFirst;
        }  
        if($record->rvFirst!==NULL){
            $rvFirst = $record->rvFirst;
        }   
        if($record->rvSecond!==NULL){
            $rvSecond = $record->rvSecond;
        } 

        return [
                    'baby_name' => $baby_name,
                    'caste' => $caste,
                    'gender' => $gender,
                    'mother_name' => $mother_name,
                    'father_name' => $father_name,
                    'contact_no' => $contact_no,
                    'tole' => $tole,
                    'dob_en' => $dob_en,
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
                    'rvFirst' => $rvFirst,
                    'rvSecond' => $rvSecond,
                ];
    }
}
