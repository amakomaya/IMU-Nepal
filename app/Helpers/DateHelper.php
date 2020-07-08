<?php 
namespace App\Helpers;


Class DateHelper {
	function days_until($date){
		    return (isset($date)) ? floor((time() - strtotime($date))/60/60/24) : FALSE;
	}

	function days_between($date1, $date2){
		return floor((strtotime($date2) - strtotime($date1))/60/60/24);
	}

	function hours_between($date1, $date2){
		return floor((strtotime($date2) - strtotime($date1))/60/60);
	}

	public static function convertDaysMonthYearFormat($date){
	    return date('d/m/Y' , strtotime($date));
    }

    public static function convertToNepaliAndDaysMonthYearFormat($date){
        return date('d/m/Y' , strtotime(ViewHelper::convertEnglishToNepali($date)));
    }
}