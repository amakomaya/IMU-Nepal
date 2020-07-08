<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\ViewHelper;
use App\Helpers\BackendHelper;
use Illuminate\Support\Facades\DB;
use Yagiten\Nepalicalendar\Calendar;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Pnc extends Model
{
    use SoftDeletes;
    protected $table='pncs';
    protected $dates = ['deleted_at'];
    protected $fillable = ['token','woman_token','date_of_visit','visit_time','mother_status','baby_status','family_plan','advice','checked_by','hp_code','status','created_at','updated_at'];

    public function scopeFromToDate($query, $from, $to)
    {
        return $query->whereBetween('date_of_visit', [$from, $to]);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function woman()
    {
        return $this->belongsTo('App\Models\Woman');
    }

    public function safeMaternityProgramPnc($province_id,$district_id,$municipality_id,$ward_no,$hp_code, $from_date, $to_date){

        $modelWoman = new Woman;

    	$pncs = DB::table('pncs')
    			->select('pncs.*')
    			->join('women','pncs.woman_token','=','women.token');

    	if($hp_code!=""){
            $pncs->where('women.hp_code', $hp_code);
        }elseif($municipality_id!="" && $ward_no!=""){
            $pncs->join('healthposts', 'healthposts.hp_code','=','women.hp_code')
                 ->where([['healthposts.municipality_id', $municipality_id],['healthposts.ward_no',$ward_no]]);
        }elseif($municipality_id!=""){
            $pncs->join('healthposts', 'healthposts.hp_code','=','women.hp_code')
                 ->where('healthposts.municipality_id', $municipality_id);
        }elseif($district_id!=""){
            $pncs->join('healthposts', 'healthposts.hp_code','=','women.hp_code')
                 ->where('healthposts.district_id', $district_id);
        }elseif($province_id!=""){
            $pncs->join('healthposts', 'healthposts.hp_code','=','women.hp_code')
                 ->where('healthposts.province_id', $province_id);
        }

        if($from_date!=""){
            $from_date_array = explode("-", $from_date);
            $from_date_eng = Calendar::nep_to_eng($from_date_array[0],$from_date_array[1],$from_date_array[2])->getYearMonthDay();
			if($to_date != ''){
				$to_date_array = explode("-", $to_date);
				$to_date_eng = Calendar::nep_to_eng($to_date_array[0],$to_date_array[1],$to_date_array[2])->getYearMonthDay();
				$pncs->whereBetween('women.created_at', [$from_date_eng, $to_date_eng]);
			}
			$to = date("Y-m-d");
			$pncs->whereBetween('women.created_at', [$from_date_eng, $to]);
        }

    	$pncs = $pncs->where('pncs.status','1')
            ->get();

    	$checkIn24hour = array();
    	$pncSecond = array();
    	$pncThird = array();
    	if(count($pncs)>0){
	    	foreach ($pncs as $pnc) {
	    		$deliveryDateTime = (new Woman)->findDeliveryDateTime($pnc->woman_token); 
		        $pncDateTime = $pnc->date_of_visit. " ".$pnc->visit_time.":00"; 
		        $deliveryDateTime = StrToTime ( $deliveryDateTime );
		        $pncDateTime = StrToTime ( $pncDateTime );
		        $diff = $pncDateTime - $deliveryDateTime;
		        $hours = $diff / ( 60 * 60 );
		        $pncDateNepali = ViewHelper::convertEnglishToNepali($pnc->date_of_visit);
		        list($pncYear,$pncMonth,$pncDay) = explode("-",$pncDateNepali);
		        if($hours<=24){
		        	$checkIn24hour[] = $pnc->woman_token;
		        }

	    		$deliveryDateTime = $modelWoman->findDeliveryDateTime($pnc->woman_token); 
		        $pncDateTime = $pnc->date_of_visit. " ".$pnc->visit_time.":00";
		        $deliveryDateTime = StrToTime ( $deliveryDateTime );
	            $pncDateTime = StrToTime ( $pncDateTime );
	            $diff = $pncDateTime - $deliveryDateTime;
	            $days = $diff / ( 60 * 60 * 24 );
	            $days = number_format((float)$days, 0, '.', '');
	            if($days>1 && $days <=3 ){
	            	$pncSecond[] = $pnc->woman_token;
	            }elseif($days>3 && $days <=7 ){
	            	$pncThird[] = $pnc->woman_token;
	            }
	    	}
	    }

	    $checkIn24hour = array_unique($checkIn24hour);
	    $pncSecond = array_unique($pncSecond);
	    $pncThird = array_unique($pncThird);

	    $pncAll = array_intersect($checkIn24hour,$pncSecond);
	    $pncAll = array_intersect($pncAll, $pncThird);

	    $data = compact('checkIn24hour','pncAll');

	    return $data;
    	
    }

    public function safeMaternityProgramPncCount($province_id,$district_id,$municipality_id,$ward_no,$hp_code, $from_date, $to_date){
    	$data = $this->safeMaternityProgramPnc($province_id,$district_id,$municipality_id,$ward_no,$hp_code, $from_date, $to_date);
        $data = (new BackendHelper)->arraysAsKeyValueCount($data);
        return $data;
    }

    public function pncVisitForReport($womanToken, $request){
        $hp_code = $request->hp_code;
        $municipality_id = $request->municipality_id;
        $ward_no = $request->ward_no;
        $district_id = $request->district_id;
        $province_id = $request->province_id;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $pncs = DB::table('pncs')
                ->select('pncs.*')
                ->join('women','pncs.woman_token','=','women.token')
                ->where([['pncs.status','1'],['pncs.woman_token',$womanToken]]);

        if($hp_code!=""){
            $pncs->where('women.hp_code', $hp_code);
        }elseif($municipality_id!="" && $ward_no!=""){
            $pncs->join('healthposts', 'healthposts.hp_code','=','women.hp_code')
                 ->where([['healthposts.municipality_id', $municipality_id],['healthposts.ward_no',$ward_no]]);
        }elseif($municipality_id!=""){
            $pncs->join('healthposts', 'healthposts.hp_code','=','women.hp_code')
                 ->where('healthposts.municipality_id', $municipality_id);
        }elseif($district_id!=""){
            $pncs->join('healthposts', 'healthposts.hp_code','=','women.hp_code')
                 ->where('healthposts.district_id', $district_id);
        }elseif($province_id!=""){
            $pncs->join('healthposts', 'healthposts.hp_code','=','women.hp_code')
                 ->where('healthposts.province_id', $province_id);
        }

        $pncs = $pncs->get();

        $checkIn24hour = '';
        $pncSecond = '';
        $pncThird = '';
        $pncOther = '';
        if(count($pncs)>0){
            foreach ($pncs as $pnc) {
                $deliveryDateTime = (new Woman)->findDeliveryDateTime($pnc->woman_token); 
                $pncDateTime = $pnc->date_of_visit. " ".$pnc->visit_time.":00"; 
                $deliveryDateTime = StrToTime ( $deliveryDateTime );
                $pncDateTime = StrToTime ( $pncDateTime );
                $diff = $pncDateTime - $deliveryDateTime;
                $hours = $diff / ( 60 * 60 );
                $pncDateNepali = ViewHelper::convertEnglishToNepali($pnc->date_of_visit);
                list($pncYear,$pncMonth,$pncDay) = explode("-",$pncDateNepali);
                if($hours<=24){
                    $checkIn24hour = 1;
                }

                $deliveryDateTime = (new Woman)->findDeliveryDateTime($pnc->woman_token); 
                $pncDateTime = $pnc->date_of_visit. " ".$pnc->visit_time.":00";
                $deliveryDateTime = StrToTime ( $deliveryDateTime );
                $pncDateTime = StrToTime ( $pncDateTime );
                $diff = $pncDateTime - $deliveryDateTime;
                $days = $diff / ( 60 * 60 * 24 );
                $days = number_format((float)$days, 0, '.', '');
                if($days>1 && $days <=3 ){
                    $pncSecond = 1;
                }
                if($days>3 && $days <=7 ){
                    $pncThird = 1;
                }


                if($days>7 ){
                    $pncOther = 1;
                }
            }
        }

        return[
            'pnc1day'=>$checkIn24hour,
            'pnc3day'=>$pncSecond,
            'pnc7day'=>$pncThird,
            'pncOther'=>$pncOther
        ];
    }
}

