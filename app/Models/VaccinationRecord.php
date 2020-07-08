<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use App\Helpers\DateHelper;
use Yagiten\NepaliCalendar\Calendar;
use App\Helpers\ViewHelper;
use App\Models\Woman;
use App\Models\Anc;


class VaccinationRecord extends Model
{
    use SoftDeletes;

    protected $fillable = ['token','baby_token','vaccine_name','vaccine_period','vaccinated_date_np','vaccinated_date_en','vaccinated_address','vial_image', 'hp_code','status','created_at','updated_at'];


    public function getFillable()
    {
        return $this->fillable;
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

        $imuunizedRecord = DB::table('vaccination_records')->where('vial_image','!=','');

        // dd($imuunizedRecord);

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

        if($from_date!="" && $to_date!=""){
            $from_date_array = explode("-", $from_date);
            $from_date_eng = Calendar::nep_to_eng($from_date_array[0],$from_date_array[1],$from_date_array[2])->getYearMonthDay();
            $to_date_array = explode("-", $to_date);
            $to_date_eng = Calendar::nep_to_eng($to_date_array[0],$to_date_array[1],$to_date_array[2])->getYearMonthDay();
            $imuunizedRecord->whereBetween('vaccination_records.created_at', [$from_date_eng, $to_date_eng]);
        }


        $imuunizedRecord = $imuunizedRecord->get();

        //  dd($imuunizedRecord);

        foreach ($imuunizedRecord as $data) {
            if($data->vaccine_name == 'BCG'){ $bcgFirst++; }
            if($data->vaccine_name == 'Pentavalent' && $data->vaccine_period == '6W') { $pvFirst++; }
            if($data->vaccine_name == 'Pentavalent' && $data->vaccine_period == '10W') { $pvSecond++; }
            if($data->vaccine_name == 'Pentavalent' && $data->vaccine_period == '14W') { $pvThird++; }
            if($data->vaccine_name == 'OPV' && $data->vaccine_period == '6W') { $opvFirst++; }
            if($data->vaccine_name == 'OPV' && $data->vaccine_period == '10W') { $opvSecond++; }
            if($data->vaccine_name == 'OPV' && $data->vaccine_period == '14W') { $opvThird++; }
            if($data->vaccine_name == 'PCV' && $data->vaccine_period == '6W') { $pcvFirst++; }
            if($data->vaccine_name == 'PCV' && $data->vaccine_period == '10W') { $pcvSecond++; }
            if($data->vaccine_name == 'PCV' && $data->vaccine_period == '9M') { $pcvThird++; }
            if($data->vaccine_name == 'FIPV' && $data->vaccine_period == '14W') { $fipvFirst++; }
            // if($data->vaccine_name == 'FIPV' && $data->vaccine_period == '14W') { $fipvSecond++; }
            if($data->vaccine_name == 'MR' && $data->vaccine_period == '9M') { $mrFirst++; }
            if($data->vaccine_name == 'MR' && $data->vaccine_period == '15M') { $mrSecond++; }
            if($data->vaccine_name == 'JE') { $jeFirst++; }
            
        }

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

    public function scopeVaccinatedFromToDate($query, $from, $to)
    {
        return $query->whereBetween('vaccinated_date_en', [$from, $to]);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeHasVialImage($query){
        return $query->where('vial_image', '!=','');
    }

    public function scopeVaccineName($query, $vaccine_name)
    {
        return $query->where('vaccine_name', $vaccine_name);
    }

    public function scopePeriod($query, $period)
    {
        return $query->where('vaccine_period', $period);
    }

    public function vaccineReceivedUsageWastage($request){
        $request = (new Woman)->womanRequestFiscalYear($request);
        $fiscal_year = $request['fiscal_year'];
        $monthlyreport = ""; 
            
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

            $registeredReport =  $this->vaccineReceivedUsageWastageMonthWise($request, $from_date, $to_date);

            $monthlyreport[] =  $registeredReport;

        }

        $response[] = $monthlyreport;
        $response[] = $request;

        return $response;

    }

    protected function vaccineReceivedUsageWastageMonthWise($requests, $from_date, $to_date){
        
        foreach ($requests as $key => $value) {
            $$key = $value;
        }

        $vaccinationRecords = DB::table('vaccination_records')
                            ->select(DB::raw("vaccination_records.vaccine_name, vaccination_records.baby_token, vaccination_records.vial_image, null as dose_in_stock, null as new_dose, null as reuseable_dose"))
                            ->join('vaccine_vials',"vaccination_records.vial_image","=","vaccine_vials.vial_image")
                            ->whereBetween('vaccination_records.vaccinated_date_en', [$from_date, $to_date]);
        if($hp_code!=""){
            $vaccinationRecords->where('vaccination_records.hp_code', $hp_code);
        }elseif($municipality_id!="" && $ward_no!=""){
            $vaccinationRecords->join('healthposts', 'healthposts.hp_code','=','vaccination_records.hp_code')
                 ->where([['healthposts.municipality_id', $municipality_id],['healthposts.ward_no',$ward_no]]);
        }elseif($municipality_id!=""){
            $vaccinationRecords->join('healthposts', 'healthposts.hp_code','=','vaccination_records.hp_code')
                 ->where('healthposts.municipality_id', $municipality_id);
        }elseif($district_id!=""){
            $vaccinationRecords->join('healthposts', 'healthposts.hp_code','=','vaccination_records.hp_code')
                 ->where('healthposts.district_id', $district_id);
        }elseif($province_id!=""){
            $vaccinationRecords->join('healthposts', 'healthposts.hp_code','=','vaccination_records.hp_code')
                 ->where('healthposts.province_id', $province_id);
        }
        
        $data = DB::table('vaccine_vial_stocks')
                    ->select(DB::raw("vaccine_vial_stocks.name as vaccine_name, null as baby_token, null as vial_image, vaccine_vial_stocks.dose_in_stock, vaccine_vial_stocks.new_dose, vaccine_vial_stocks.reuseable_dose"))
                    ->whereBetween('vaccination_ending_at', [$from_date, $to_date]);
        if($hp_code!=""){
            $data->where('vaccine_vial_stocks.hp_code', $hp_code);
        }elseif($municipality_id!="" && $ward_no!=""){
            $data->join('healthposts', 'healthposts.hp_code','=','vaccine_vial_stocks.hp_code')
                 ->where([['healthposts.municipality_id', $municipality_id],['healthposts.ward_no',$ward_no]]);
        }elseif($municipality_id!=""){
            $data->join('healthposts', 'healthposts.hp_code','=','vaccine_vial_stocks.hp_code')
                 ->where('healthposts.municipality_id', $municipality_id);
        }elseif($district_id!=""){
            $data->join('healthposts', 'healthposts.hp_code','=','vaccine_vial_stocks.hp_code')
                 ->where('healthposts.district_id', $district_id);
        }elseif($province_id!=""){
            $data->join('healthposts', 'healthposts.hp_code','=','vaccine_vial_stocks.hp_code')
                 ->where('healthposts.province_id', $province_id);
        }
        
        $data = $data->union($vaccinationRecords)
                    ->get();

        //BCG
        $immunized_child_bcg = array();
        $new_available_dose_bcg = 0;
        $lost_dose_bcg = 0;

        //Pentavalent
        $immunized_child_pentavalent = array();
        $new_available_dose_pentavalent = 0;
        $lost_dose_pentavalent = 0;

        //OPV
        $immunized_child_opv = array();
        $new_available_dose_opv = 0;
        $lost_dose_opv = 0;

        //PCV
        $immunized_child_pcv = array();
        $new_available_dose_pcv = 0;
        $lost_dose_pcv = 0;

        //FIPV
        $immunized_child_fipv = array();
        $new_available_dose_fipv = 0;
        $lost_dose_fipv = 0;

        //MR
        $immunized_child_mr = array();
        $new_available_dose_mr = 0;
        $lost_dose_mr =0;

        //JE
        $immunized_child_je = array();
        $new_available_dose_je = 0;
        $lost_dose_je = 0;

        //RV
        $immunized_child_rv = array();
        $new_available_dose_rv = 0;
        $lost_dose_rv = 0;

        //TD
        $immunized_child_td = array();
        $new_available_dose_td = 0;
        $lost_dose_td = 0;

        foreach($data as $record){
            if($record->vaccine_name=="BCG"){
                    if($record->baby_token!=Null){
                        array_push($immunized_child_bcg, $record->baby_token);
                    }
                    
                    
                    $new_available_dose_bcg+= $record->new_dose;                    

                    $lost_dose_bcg+= $record->dose_in_stock+$record->new_dose-$record->reuseable_dose;

            }

            if($record->vaccine_name=="Pentavalent"){
                    if($record->baby_token!=Null){
                        array_push($immunized_child_pentavalent, $record->baby_token);
                    }
                    
                    
                    $new_available_dose_pentavalent+= $record->new_dose;                    

                    $lost_dose_pentavalent+= $record->dose_in_stock+$record->new_dose-$record->reuseable_dose;

            }

            if($record->vaccine_name=="OPV"){
                    if($record->baby_token!=Null){
                        array_push($immunized_child_opv, $record->baby_token);
                    }
                    
                    
                    $new_available_dose_opv+= $record->new_dose;                    

                    $lost_dose_opv+= $record->dose_in_stock+$record->new_dose-$record->reuseable_dose;

            }

            if($record->vaccine_name=="PCV"){
                    if($record->baby_token!=Null){
                        array_push($immunized_child_pcv, $record->baby_token);
                    }
                    
                    
                    $new_available_dose_pcv+= $record->new_dose;                    

                    $lost_dose_pcv+= $record->dose_in_stock+$record->new_dose-$record->reuseable_dose;

            }

            if($record->vaccine_name=="FIPV"){
                    if($record->baby_token!=Null){
                        array_push($immunized_child_fipv, $record->baby_token);
                    }
                    
                    
                    $new_available_dose_fipv+= $record->new_dose;                    

                    $lost_dose_fipv+= $record->dose_in_stock+$record->new_dose-$record->reuseable_dose;

            }

            if($record->vaccine_name=="MR"){
                    if($record->baby_token!=Null){
                        array_push($immunized_child_mr, $record->baby_token);
                    }
                    
                    
                    $new_available_dose_mr+= $record->new_dose;                    

                    $lost_dose_mr+= $record->dose_in_stock+$record->new_dose-$record->reuseable_dose;

            }

            if($record->vaccine_name=="JE"){
                    if($record->baby_token!=Null){
                        array_push($immunized_child_je, $record->baby_token);
                    }
                    
                    
                    $new_available_dose_je+= $record->new_dose;                    

                    $lost_dose_je+= $record->dose_in_stock+$record->new_dose-$record->reuseable_dose;

            }

            if($record->vaccine_name=="RV"){
                    if($record->baby_token!=Null){
                        array_push($immunized_child_rv, $record->baby_token);
                    }
                    
                    
                    $new_available_dose_rv+= $record->new_dose;                    

                    $lost_dose_rv+= $record->dose_in_stock+$record->new_dose-$record->reuseable_dose;

            }

            if($record->vaccine_name=="TD"){
                    if($record->baby_token!=Null){
                        array_push($immunized_child_td, $record->baby_token);
                    }
                    
                    
                    $new_available_dose_td+= $record->new_dose;                    

                    $lost_dose_td+= $record->dose_in_stock+$record->new_dose-$record->reuseable_dose;

            }
        }

        //BCG
        $immunized_child_bcg =  count($immunized_child_bcg);
        $lost_dose_bcg = $lost_dose_bcg-$immunized_child_bcg;

        //Pentavalent
        $immunized_child_pentavalent =  count($immunized_child_pentavalent);
        $lost_dose_pentavalent = $lost_dose_pentavalent-$immunized_child_pentavalent;

        //OPV
        $immunized_child_opv =  count($immunized_child_opv);
        $lost_dose_opv = $lost_dose_opv-$immunized_child_opv;

        //PCV
        $immunized_child_pcv =  count($immunized_child_pcv);
        $lost_dose_pcv = $lost_dose_pcv-$immunized_child_pcv;        
        
        //FIPV
        $immunized_child_fipv =  count($immunized_child_fipv);
        $lost_dose_fipv = $lost_dose_fipv-$immunized_child_fipv;
        
        //MR
        $immunized_child_mr =  count($immunized_child_mr);
        $lost_dose_mr = $lost_dose_mr-$immunized_child_mr;
        
        //JE
        $immunized_child_je =  count($immunized_child_je);
        $lost_dose_je = $lost_dose_je-$immunized_child_je;
        
        //RV
        $immunized_child_rv =  count($immunized_child_rv);
        $lost_dose_rv = $lost_dose_rv-$immunized_child_rv;

        //TD
        $immunized_child_td =  count($immunized_child_td);
        $lost_dose_td = $lost_dose_td-$immunized_child_td;

        return [
            'new_available_dose_bcg'=>$new_available_dose_bcg,
            'immunized_child_bcg'=>$immunized_child_bcg,
            'lost_dose_bcg'=>$lost_dose_bcg,
            'new_available_dose_pentavalent'=>$new_available_dose_pentavalent,
            'immunized_child_pentavalent'=>$immunized_child_pentavalent,
            'lost_dose_pentavalent'=>$lost_dose_pentavalent,
            'new_available_dose_opv'=>$new_available_dose_opv,
            'immunized_child_opv'=>$immunized_child_opv,
            'lost_dose_opv'=>$lost_dose_opv,
            'new_available_dose_pcv'=>$new_available_dose_pcv,
            'immunized_child_pcv'=>$immunized_child_pcv,
            'lost_dose_pcv'=>$lost_dose_pcv,
            'new_available_dose_fipv'=>$new_available_dose_fipv,
            'immunized_child_fipv'=>$immunized_child_fipv,
            'lost_dose_fipv'=>$lost_dose_fipv,
            'new_available_dose_mr'=>$new_available_dose_mr,
            'immunized_child_mr'=>$immunized_child_mr,
            'lost_dose_mr'=>$lost_dose_mr,
            'new_available_dose_je'=>$new_available_dose_je,
            'immunized_child_je'=>$immunized_child_je,
            'lost_dose_je'=>$lost_dose_je,
            'new_available_dose_rv'=>$new_available_dose_rv,
            'immunized_child_rv'=>$immunized_child_rv,
            'lost_dose_rv'=>$lost_dose_rv,
            'new_available_dose_td'=>$new_available_dose_td,
            'immunized_child_td'=>$immunized_child_td,
            'lost_dose_td'=>$lost_dose_td,
        ];
                

    }

    public function immunizedChildRawFormat($request){
        $request = (new Woman)->womanRequestFiscalYear($request);
        $fiscal_year = $request['fiscal_year'];
        $monthlyreport = ""; 
            
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

            $imuunizedRecord =  $this->immunizedChildRawFormatMonthWise($request, $from_date, $to_date);

            $monthlyreport[] =  $imuunizedRecord;

        }

        $response[] = $monthlyreport;
        $response[] = $request;

        return $response;
        
    }

    public function immunizedChildRawFormatMonthWise($requests, $from_date, $to_date){

        foreach ($requests as $key => $value) {
            $$key = $value;
        }

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
            ->select(DB::raw("baby_details.dob_en, baby_details.token, vaccination_records.vaccinated_date_en,MAX(IF(vaccination_records.vaccine_name = 'BCG' AND vaccine_period='Birth', vaccinated_date_en, NULL)) AS bcgFirst, MAX(IF(vaccination_records.vaccine_name = 'Pentavalent' AND vaccine_period='6W', vaccinated_date_en, NULL)) AS pvFirst, MAX(IF(vaccination_records.vaccine_name = 'Pentavalent' AND vaccine_period='10W', vaccinated_date_en, NULL)) AS pvSecond, MAX(IF(vaccination_records.vaccine_name = 'Pentavalent' AND vaccine_period='14W', vaccinated_date_en, NULL)) AS pvThird, MAX(IF(vaccination_records.vaccine_name = 'OPV' AND vaccine_period='6W', vaccinated_date_en, NULL)) AS opvFirst, MAX(IF(vaccination_records.vaccine_name = 'OPV' AND vaccine_period='10W', vaccinated_date_en, NULL)) AS opvSecond, MAX(IF(vaccination_records.vaccine_name = 'OPV' AND vaccine_period='14W', vaccinated_date_en, NULL)) AS opvThird, MAX(IF(vaccination_records.vaccine_name = 'PCV' AND vaccine_period='6W', vaccinated_date_en, NULL)) AS pcvFirst, MAX(IF(vaccination_records.vaccine_name = 'PCV' AND vaccine_period='10W', vaccinated_date_en, NULL)) AS pcvSecond, MAX(IF(vaccination_records.vaccine_name = 'PCV' AND vaccine_period='9M', vaccinated_date_en, NULL)) AS pcvThird, MAX(IF(vaccination_records.vaccine_name = 'FIPV' AND vaccine_period='6W', vaccinated_date_en, NULL)) AS fipvFirst, MAX(IF(vaccination_records.vaccine_name = 'FIPV' AND vaccine_period='14W', vaccinated_date_en, NULL)) AS fipvSecond, MAX(IF(vaccination_records.vaccine_name = 'MR' AND vaccine_period='9M', vaccinated_date_en, NULL)) AS mrFirst, MAX(IF(vaccination_records.vaccine_name = 'MR' AND vaccine_period='15M', vaccinated_date_en, NULL)) AS mrSecond, MAX(IF(vaccination_records.vaccine_name = 'JE' AND vaccine_period='12M', vaccinated_date_en, NULL)) AS jeFirst, MAX(IF(vaccination_records.vaccine_name = 'RV' AND vaccine_period='6W', vaccinated_date_en, NULL)) AS rvFirst, MAX(IF(vaccination_records.vaccine_name = 'RV' AND vaccine_period='10W', vaccinated_date_en, NULL)) AS rvSecond"))
            ->leftjoin('vaccination_records','baby_details.token', '=', 'vaccination_records.baby_token')
            ->whereBetween('vaccination_records.vaccinated_date_en', [$from_date, $to_date])
            ->groupBy("vaccination_records.vaccinated_date_en","baby_details.dob_en","baby_details.token");

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

        $imuunizedRecord = $imuunizedRecord->get();

        foreach ($imuunizedRecord as $data) {
            
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

    return    [
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
            'pvThirdAndOpvThirdAfeterOneYear'=>$pvThirdAndOpvThirdAfeterOneYear
        ];
    }

    public function droupoutChild($request, $exceed_time)
    {
        $request = (new Woman)->womanRequestFiscalYear($request);
        $fiscal_year = $request['fiscal_year'];
        $monthlyreport = ""; 
            
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

            $imuunizedRecord =  $this->droupoutChildMonthWise($request, $to_date, $exceed_time);

            $monthlyreport[] =  $imuunizedRecord;

        }

        $response[] = $monthlyreport;
        $response[] = $request;

        return $response;
    }

    public function droupoutChildMonthWise($requests, $to_date, $exceed_time){
        foreach ($requests as $key => $value) {
            $$key = $value;
        }

        $datas = DB::table('baby_details')
            ->select(DB::raw("baby_details.dob_en, baby_details.token, vaccination_records.vaccinated_date_en,MAX(IF(vaccination_records.vaccine_name = 'BCG' AND vaccine_period='Birth', vaccinated_date_en, NULL)) AS bcgFirst, MAX(IF(vaccination_records.vaccine_name = 'Pentavalent' AND vaccine_period='6W', vaccinated_date_en, NULL)) AS pvFirst, MAX(IF(vaccination_records.vaccine_name = 'Pentavalent' AND vaccine_period='10W', vaccinated_date_en, NULL)) AS pvSecond, MAX(IF(vaccination_records.vaccine_name = 'Pentavalent' AND vaccine_period='14W', vaccinated_date_en, NULL)) AS pvThird, MAX(IF(vaccination_records.vaccine_name = 'OPV' AND vaccine_period='6W', vaccinated_date_en, NULL)) AS opvFirst, MAX(IF(vaccination_records.vaccine_name = 'OPV' AND vaccine_period='10W', vaccinated_date_en, NULL)) AS opvSecond, MAX(IF(vaccination_records.vaccine_name = 'OPV' AND vaccine_period='14W', vaccinated_date_en, NULL)) AS opvThird, MAX(IF(vaccination_records.vaccine_name = 'PCV' AND vaccine_period='6W', vaccinated_date_en, NULL)) AS pcvFirst, MAX(IF(vaccination_records.vaccine_name = 'PCV' AND vaccine_period='10W', vaccinated_date_en, NULL)) AS pcvSecond, MAX(IF(vaccination_records.vaccine_name = 'PCV' AND vaccine_period='9M', vaccinated_date_en, NULL)) AS pcvThird, MAX(IF(vaccination_records.vaccine_name = 'FIPV' AND vaccine_period='6W', vaccinated_date_en, NULL)) AS fipvFirst, MAX(IF(vaccination_records.vaccine_name = 'FIPV' AND vaccine_period='14W', vaccinated_date_en, NULL)) AS fipvSecond, MAX(IF(vaccination_records.vaccine_name = 'MR' AND vaccine_period='9M', vaccinated_date_en, NULL)) AS mrFirst, MAX(IF(vaccination_records.vaccine_name = 'MR' AND vaccine_period='15M', vaccinated_date_en, NULL)) AS mrSecond, MAX(IF(vaccination_records.vaccine_name = 'JE' AND vaccine_period='12M', vaccinated_date_en, NULL)) AS jeFirst, MAX(IF(vaccination_records.vaccine_name = 'RV' AND vaccine_period='6W', vaccinated_date_en, NULL)) AS rvFirst, MAX(IF(vaccination_records.vaccine_name = 'RV' AND vaccine_period='10W', vaccinated_date_en, NULL)) AS rvSecond"))
            ->leftjoin('vaccination_records','baby_details.token', '=', 'vaccination_records.baby_token')
            ->where('vaccination_records.vaccinated_date_en','<=', $to_date)
            ->groupBy("vaccination_records.vaccinated_date_en","baby_details.dob_en","baby_details.token");

        if($hp_code!=""){
            $datas->where('vaccination_records.hp_code', $hp_code);
        }elseif($municipality_id!="" && $ward_no!=""){
            $datas->join('healthposts', 'healthposts.hp_code','=','vaccination_records.hp_code')
                 ->where([['healthposts.municipality_id', $municipality_id],['healthposts.ward_no',$ward_no]]);
        }elseif($municipality_id!=""){
            $datas->join('healthposts', 'healthposts.hp_code','=','vaccination_records.hp_code')
                 ->where('healthposts.municipality_id', $municipality_id);
        }elseif($district_id!=""){
            $datas->join('healthposts', 'healthposts.hp_code','=','vaccination_records.hp_code')
                 ->where('healthposts.district_id', $district_id);
        }elseif($province_id!=""){
            $datas->join('healthposts', 'healthposts.hp_code','=','vaccination_records.hp_code')
                 ->where('healthposts.province_id', $province_id);
        }

        $datas = $datas->get();

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

        foreach ($datas as $data) {

            $totalDaysFromBirth = (new DateHelper)->days_until($data->dob_en);
            
            $pvFirstDate = 42+28*$exceed_time;
            if(empty($data->pvFirst) && $totalDaysFromBirth >= $pvFirstDate){
                $pvFirst++;
            }

            $pvSecondDate = 70+28*$exceed_time;
            if(empty($data->pvSecond) && $totalDaysFromBirth >= $pvSecondDate){
                if(!empty($data->pvFirst) && (new DateHelper)->days_until($data->pvFirst) >= 28){
                    $pvSecond++;
                }
            }

            $pvThirdDate = 98+28*$exceed_time;
            if(empty($data->pvThird) && $totalDaysFromBirth >= $pvThirdDate){
                if(!empty($data->pvSecond) && (new DateHelper)->days_until($data->pvSecond) >= 28){
                    $pvThird++;
                }
            }

            $opvFirstDate = 42+28*$exceed_time;
            if(empty($data->opvFirst) && $totalDaysFromBirth >= $opvFirstDate){
                $opvFirst++;
            }

            $opvSecondDate = 70+28*$exceed_time;
            if(empty($data->opvSecond) && $totalDaysFromBirth >= $opvSecondDate){
                if(!empty($data->opvFirst) && (new DateHelper)->days_until($data->opvFirst) >= 28){
                    $opvSecond++;
                }
            }

            $opvThirdDate = 98+28*$exceed_time;
            if(empty($data->opvThird) && $totalDaysFromBirth >= $opvThirdDate){
                if(!empty($data->opvSecond) && (new DateHelper)->days_until($data->opvSecond) >= 28){
                    $opvThird++;
                }
            }

            $pcvFirstDate = 42+28*$exceed_time;
            if(empty($data->pcvFirst) && $totalDaysFromBirth >= $pcvFirstDate){
                $pcvFirst++;
            }

            $pcvSecondDate = 70+28*$exceed_time;
            if(empty($data->pcvSecond) && $totalDaysFromBirth >= $pcvSecondDate){
                if(!empty($data->pcvFirst) && (new DateHelper)->days_until($data->pcvFirst) >= 28){
                    $pcvSecond++;
                }
            }

            $pcvThirdDate = 70+28*$exceed_time;
            if(empty($data->pcvThird) && $totalDaysFromBirth >= $pcvThirdDate){
                if(!empty($data->pcvSecond) && (new DateHelper)->days_until($data->pcvSecond) >= 28){
                    $pcvThird++;
                }
            }

            $fipvFirstDate = 70+28*$exceed_time;
            if(empty($data->fipvFirst) && $totalDaysFromBirth >= $fipvFirstDate){
                $fipvFirst++;
            }

            $fipvSecondDate = 70+28*$exceed_time;
            if(empty($data->fipvSecond) && $totalDaysFromBirth >= $fipvSecondDate){
                if(!empty($data->fipvFirst) && (new DateHelper)->days_until($data->fipvFirst) >= 28){
                    $fipvSecond++;
                }
            }

            $mrFirstDate = 270+28*$exceed_time;
            if(empty($data->mrFirst) && $totalDaysFromBirth >= $mrFirstDate){
                $mrFirst++;
            }

            $mrSecondDate = 450+28*$exceed_time;
            if(empty($data->mrSecond) && $totalDaysFromBirth >= $mrSecondDate){
                if(!empty($data->mrFirst) && (new DateHelper)->days_until($data->mrFirst) >= 28){
                    $mrSecond++;
                }
            }

            $jeFirstDate = 360+28*$exceed_time;
            if(empty($data->jeFirst) && $totalDaysFromBirth >= $jeFirstDate){
                $jeFirst++;
            }

            $rvFirstDate = 42+28*$exceed_time;
            if(empty($data->rvFirst) && $totalDaysFromBirth >= $rvFirstDate){
                $rvFirst++;
            }

            $rvSecondDate = 70+28*$exceed_time;
            if(empty($data->rvSecond) && $totalDaysFromBirth >= $rvSecondDate){
                if(!empty($data->rvFirst) && (new DateHelper)->days_until($data->rvFirst) >= 28){
                    $rvSecond++;
                }
            }
        }

        return [
                "pvFirst"=>$pvFirst,
                "pvSecond"=>$pvSecond,
                "pvThird"=>$pvThird,
                "opvFirst"=>$opvFirst,
                "opvSecond"=>$opvSecond,
                "opvThird"=>$opvThird,
                "pcvFirst"=>$pcvFirst,
                "pcvSecond"=>$pcvSecond,
                "pcvThird"=>$pcvThird,
                "fipvFirst"=>$fipvFirst,
                "fipvSecond"=>$fipvSecond,
                "mrFirst"=>$mrFirst,
                "mrSecond"=>$mrSecond,
                "jeFirst"=>$jeFirst,
                "rvFirst"=>$rvFirst,
                "rvSecond"=>$rvSecond
            ];
    }

    public function eligibleChild($request){
        $request = (new Woman)->womanRequestFiscalYear($request);
        $monthlyreport = ""; 
            
        $to_date =  date('Y')."-".date('m')."-01";

        $monthlyreport = array();
        $exceed_time = 0;
        for ($i=0; $i <3 ; $i++) {
            $to_date = date('Y-m-d', strtotime("+1 month", strtotime($to_date)));
            $dropoutRecord = $this->droupoutChildMonthWise($request, $to_date, $exceed_time);
            $monthlyreport[] =  $dropoutRecord;
        }

        $response[] = $monthlyreport;
        $response[] = $request;

        return $response;
    }
}
