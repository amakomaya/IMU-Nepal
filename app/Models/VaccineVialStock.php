<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Yagiten\NepaliCalendar\Calendar;

class VaccineVialStock extends Model
{
	protected $fillable = ['token','hp_code','name','new_dose','dose_in_stock','reuseable_dose','vaccination_ending_at','created_at', 'status','updated_at', 'return_date_np', 'return_date_en', 'return_dose'];

    public function receivedExpenseDose($province_id,$district_id,$municipality_id,$ward_no,$hp_code,$from_date,$to_date){

    	$receivedExpenseDose = DB::table('vaccine_vial_stocks');
        
        if($hp_code!=""){
            $receivedExpenseDose->where('vaccine_vial_stocks.hp_code', $hp_code);
        }elseif($municipality_id!="" && $ward_no!=""){
            $receivedExpenseDose->join('healthposts', 'healthposts.hp_code','=','vaccine_vial_stocks.hp_code')
                 ->where([['healthposts.municipality_id', $municipality_id],['healthposts.ward_no',$ward_no]]);
        }elseif($municipality_id!=""){
            $receivedExpenseDose->join('healthposts', 'healthposts.hp_code','=','vaccine_vial_stocks.hp_code')
                 ->where('healthposts.municipality_id', $municipality_id)->select(DB::raw('vaccine_vial_stocks.*, healthposts.*,vaccine_vial_stocks.name as name'));
        }elseif($district_id!=""){
            $receivedExpenseDose->join('healthposts', 'healthposts.hp_code','=','vaccine_vial_stocks.hp_code')
                 ->where('healthposts.district_id', $district_id)->select(DB::raw('vaccine_vial_stocks.*, healthposts.*,vaccine_vial_stocks.name as name'));
        }elseif($province_id!=""){
            $receivedExpenseDose->join('healthposts', 'healthposts.hp_code','=','vaccine_vial_stocks.hp_code')
                 ->where('healthposts.province_id', $province_id)->select(DB::raw('vaccine_vial_stocks.*, healthposts.*,vaccine_vial_stocks.name as name'));
        }


        if($from_date!=""){
            $from_date_array = explode("-", $from_date);
            $from_date_eng = Calendar::nep_to_eng($from_date_array[0],$from_date_array[1],$from_date_array[2])->getYearMonthDay();
			if($to_date != ''){
				$to_date_array = explode("-", $to_date);
				$to_date_eng = Calendar::nep_to_eng($to_date_array[0],$to_date_array[1],$to_date_array[2])->getYearMonthDay();
				$receivedExpenseDose->whereBetween('vaccine_vial_stocks.created_at', [$from_date_eng, $to_date_eng]);
			}
			$to = date("Y-m-d");
			$receivedExpenseDose->whereBetween('vaccine_vial_stocks.created_at', [$from_date_eng, $to]);
        }

		$receivedExpenseDose = $receivedExpenseDose->get();
		
		$bcgReceived = $receivedExpenseDose->where('name','BCG')->sum('new_dose');
		$pentavalentReceived = $receivedExpenseDose->where('name','Pentavalent')->sum('new_dose');
		$opvReceived = $receivedExpenseDose->where('name','OPV')->sum('new_dose');
		$pcvReceived = $receivedExpenseDose->where('name','PCV')->sum('new_dose');
		$mrReceived = $receivedExpenseDose->where('name','MR')->sum('new_dose');
		$jeReceived = $receivedExpenseDose->where('name','JE')->sum('new_dose');
		$fipvReceived = $receivedExpenseDose->where('name','FIPV')->sum('new_dose');
		$rotaReceived = $receivedExpenseDose->where('name','RV')->sum('new_dose');
		
		
		$vaccine_expense = $this->expenseDose($province_id,$district_id,$municipality_id,$ward_no,$hp_code,$from_date,$to_date);
		
		$bcgExpense = $vaccine_expense->where('vaccine_name','BCG')->sum('vial_used_doses');
		$pentavalentExpense = $vaccine_expense->where('vaccine_name','Pentavalent')->sum('vial_used_doses');
		$opvExpense = $vaccine_expense->where('vaccine_name','OPV')->sum('vial_used_doses');
		$pcvExpense = $vaccine_expense->where('vaccine_name','PCV')->sum('vial_used_doses');
		$mrExpense = $vaccine_expense->where('vaccine_name','MR')->sum('vial_used_doses');
		$jeExpense = $vaccine_expense->where('vaccine_name','JE')->sum('vial_used_doses');
		$fipvExpense = $vaccine_expense->where('name','FIPV')->sum('vial_used_doses');
		$rotaExpense = $vaccine_expense->where('name','RV')->sum('vial_used_doses');

    	return compact('bcgExpense','bcgReceived', 'pentavalentExpense', 'pentavalentReceived','opvExpense', 'opvReceived', 'pcvExpense', 'pcvReceived', 'mrExpense', 'mrReceived', 'jeExpense', 'jeReceived', 'fipvExpense', 'fipvReceived', 'rotaExpense', 'rotaReceived');
    	
	}
	
	// Expense means Used Dose
	public function expenseDose($province_id,$district_id,$municipality_id,$ward_no,$hp_code,$from_date,$to_date)
	{
		$vial_details = DB::table('vial_details');
								
		if($hp_code!=""){
			$vial_details->where('vial_details.hp_code', $hp_code);
		}elseif($municipality_id!="" && $ward_no!=""){
			$vial_details->join('healthposts', 'healthposts.hp_code','=','vial_details.hp_code')
					->where([['healthposts.municipality_id', $municipality_id],['healthposts.ward_no',$ward_no]]);
		}elseif($municipality_id!=""){
			$vial_details->join('healthposts', 'healthposts.hp_code','=','vial_details.hp_code')
					->where('healthposts.municipality_id', $municipality_id);
		}elseif($district_id!=""){
			$vial_details->join('healthposts', 'healthposts.hp_code','=','vial_details.hp_code')
					->where('healthposts.district_id', $district_id);
		}elseif($province_id!=""){
			$vial_details->join('healthposts', 'healthposts.hp_code','=','vial_details.hp_code')
					->where('healthposts.province_id', $province_id);
		}

        if($from_date!=""){
            $from_date_array = explode("-", $from_date);
            $from_date_eng = Calendar::nep_to_eng($from_date_array[0],$from_date_array[1],$from_date_array[2])->getYearMonthDay();
			if($to_date != ''){
				$to_date_array = explode("-", $to_date);
				$to_date_eng = Calendar::nep_to_eng($to_date_array[0],$to_date_array[1],$to_date_array[2])->getYearMonthDay();
				$vial_details->whereBetween('vial_details.created_at', [$from_date_eng, $to_date_eng]);
			}
			$to = date("Y-m-d");
			$vial_details->whereBetween('vial_details.created_at', [$from_date_eng, $to]);
        }
		return $vial_details->get();
	}
}