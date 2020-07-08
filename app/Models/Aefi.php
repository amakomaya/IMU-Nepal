<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Yagiten\NepaliCalendar\Calendar;

class Aefi extends Model
{
    use SoftDeletes;

    protected $fillable = ['token','baby_token','hp_code','vaccine', 'vaccinated_date','aefi_date','problem','advice', 'created_at', 'status','updated_at'];

    public function aefiCases ($province_id,$district_id,$municipality_id,$ward_no,$hp_code,$from_date,$to_date){

        $aefis = DB::table('aefis');
    	//echo $aefis->toSql(); exit;
        if($hp_code!=""){
            $aefis->where('aefis.hp_code', $hp_code);
        }elseif($municipality_id!="" && $ward_no!=""){
            $aefis->join('healthposts', 'healthposts.hp_code','=','aefis.hp_code')
                 ->where([['healthposts.municipality_id', $municipality_id],['healthposts.ward_no',$ward_no]]);
        }elseif($municipality_id!=""){
            $aefis->join('healthposts', 'healthposts.hp_code','=','aefis.hp_code')
                 ->where('healthposts.municipality_id', $municipality_id);
        }elseif($district_id!=""){
            $aefis->join('healthposts', 'healthposts.hp_code','=','aefis.hp_code')
                 ->where('healthposts.district_id', $district_id);
        }elseif($province_id!=""){
            $aefis->join('healthposts', 'healthposts.hp_code','=','aefis.hp_code')
                 ->where('healthposts.province_id', $province_id);
        }

        if($from_date!=""){
            $from_date_array = explode("-", $from_date);
            $from_date_eng = Calendar::nep_to_eng($from_date_array[0],$from_date_array[1],$from_date_array[2])->getYearMonthDay();
            if($to_date != ''){
                $to_date_array = explode("-", $to_date);
                $to_date_eng = Calendar::nep_to_eng($to_date_array[0],$to_date_array[1],$to_date_array[2])->getYearMonthDay();
                $aefis->whereBetween('aefis.created_at', [$from_date_eng, $to_date_eng]);
            }
            $to = date("Y-m-d");
            $aefis->whereBetween('aefis.created_at', [$from_date_eng, $to]);
        }
        // $aefis = $aefis->get();
        // dd($aefis);
        
        $aefi_bcg = $aefis->where('vaccine','like','%BCG%')->count();
        // dd($aefi_bcg);

		$aefi_pentavalent = $aefis->where('vaccine','like','%Pentavalent%')->count();
		$aefi_opv = $aefis->where('vaccine','like','%OPV%')->count();
		$aefi_pcv = $aefis->where('vaccine','like','%PCV%')->count();
		$aefi_mr = $aefis->where('vaccine','like','%MR%')->count();
		$aefi_je = $aefis->where('vaccine','like','%JE%')->count();
		$aefi_fipv = $aefis->where('vaccine','like','%FIPV%')->count();
		$aefi_rota = $aefis->where('vaccine','like','%RV%')->count();


        $aefi_td = $this->tdAefiCase($province_id,$district_id,$municipality_id,$ward_no,$hp_code,$from_date,$to_date);

    	return [
                'aefi_bcg' => $aefi_bcg,
                'aefi_pentavalent' => $aefi_pentavalent,
                'aefi_opv' => $aefi_opv,
                'aefi_pcv' => $aefi_pcv,
                'aefi_mr' => $aefi_mr,
                'aefi_je' => $aefi_je,
                'aefi_fipv' => $aefi_fipv,
                'aefi_rota' => $aefi_rota,
                'aefi_td'=>$aefi_td
            ];

    }
    public function tdAefiCase($province_id,$district_id,$municipality_id,$ward_no,$hp_code,$from_date,$to_date){
        $countAefi = 0;
        $aefis = DB::table('aefis')
                                ->select(DB::raw('count(aefis.token) as td_vaccine_no'))
                                ->where('aefis.vaccine','TD');
        
        if($hp_code!=""){
            $aefis->where('aefis.hp_code', $hp_code);
        }elseif($municipality_id!="" && $ward_no!=""){
            $aefis->join('healthposts', 'healthposts.hp_code','=','aefis.hp_code')
                 ->where([['healthposts.municipality_id', $municipality_id],['healthposts.ward_no',$ward_no]]);
        }elseif($municipality_id!=""){
            $aefis->join('healthposts', 'healthposts.hp_code','=','aefis.hp_code')
                 ->where('healthposts.municipality_id', $municipality_id);
        }elseif($district_id!=""){
            $aefis->join('healthposts', 'healthposts.hp_code','=','aefis.hp_code')
                 ->where('healthposts.district_id', $district_id);
        }elseif($province_id!=""){
            $aefis->join('healthposts', 'healthposts.hp_code','=','aefis.hp_code')
                 ->where('healthposts.province_id', $province_id);
        }


        if($from_date!="" && $to_date!=""){
            $from_date_array = explode("-", $from_date);
            $from_date_eng = Calendar::nep_to_eng($from_date_array[0],$from_date_array[1],$from_date_array[2])->getYearMonthDay();
            $to_date_array = explode("-", $to_date);
            $to_date_eng = Calendar::nep_to_eng($to_date_array[0],$to_date_array[1],$to_date_array[2])->getYearMonthDay();
            $aefis->whereBetween('aefis.created_at', [$from_date_eng, $to_date_eng]);
        }
        $aefis = $aefis->get();

        foreach ($aefis as $record) {
            $countAefi = $record->td_vaccine_no;
        }

        return $countAefi;


    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
