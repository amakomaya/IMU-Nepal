<?php


namespace App\Reports;


use Carbon\Carbon;
use Yagiten\Nepalicalendar\Calendar;

class DateFromToRequest
{
    public static function dateFromTo($request)
    {
        if (empty($request['select_year']) or empty($request['select_month'])) {
            $now = Carbon::now();
            $now_in_nepali = Calendar::eng_to_nep($now->year, $now->month,$now->day);
            $request['select_year'] = $now_in_nepali->getYear();
            $request['select_month'] = $now_in_nepali->getMonth();
        }

        $from_date_eng = Calendar::nep_to_eng($request['select_year'], $request['select_month'], 1)->getDayMonthYearNepToEng();
        $to_date_eng = Calendar::nep_to_eng($request['select_year'], $request['select_month']+1, 1)->getDayMonthYearNepToEng();

        $from_date = Carbon::parse(strtotime(str_replace('/', '-', $from_date_eng)))->addDay();
        $to_date = Carbon::parse(strtotime(str_replace('/', '-', $to_date_eng)));

        return [
            'from_date' => $from_date,
            'to_date' => $to_date
        ];
    }
}