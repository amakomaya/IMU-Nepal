<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Yagiten\Nepalicalendar\Calendar;

Class ViewHelper {
	public static function convertEnglishToNepali($date){
		$date_array = explode("-", $date);
		if(count($date_array)==3){
			$date = Calendar::eng_to_nep($date_array[0],$date_array[1],$date_array[2])->getYearMonthDay();
			return $date;
		}
	}

	public static function convertNepaliToEnglish($date){
		$date_array = explode("-", $date);
		if(count($date_array)==3){
			$date = Calendar::nep_to_eng($date_array[0],$date_array[1],$date_array[2])->getYearMonthDayNepToEng();
			return $date;
		}
	}
}