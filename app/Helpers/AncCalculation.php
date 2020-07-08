<?php


namespace App\Helpers;


use Carbon\Carbon;

class AncCalculation
{

    public static function get($collection)
    {
        return self::visitWithProtocol($collection);
    }

    public static function visitWithProtocol($collection)
    {
        $ancs = $collection->ancs;

//        $plucked = $ancs->pluck('visit_date');

        $array = array();
        foreach ($ancs as $anc){

            if ($anc->service_for == null || $anc->service_for == 0){
                $visit_date = Carbon::parse($anc->visit_date);
                if ($visit_date >= Carbon::parse($collection->lmp_date_en)->addMonths(3) && $visit_date <= Carbon::parse($collection->lmp_date_en)->addMonths(4)) {
                    array_push($array, 1);
                }
                if ($visit_date >= Carbon::parse($collection->lmp_date_en)->addMonths(5) && $visit_date <= Carbon::parse($collection->lmp_date_en)->addMonths(6)) {
                    array_push($array, 2);
                }
                if ($visit_date >= Carbon::parse($collection->lmp_date_en)->addMonths(7) && $visit_date <= Carbon::parse($collection->lmp_date_en)->addMonths(8)) {
                    array_push($array, 3);
                }
                if ($visit_date > Carbon::parse($collection->lmp_date_en)->addMonths(8)) {
                    array_push($array, 4);
                }
            } else{
                array_push($array, $anc->service_for);
            }
    };
        return array_values(array_unique($array));
    }

    public static function firstVisitDateFromTo($lmp_date_en){
        $date['from'] = Carbon::parse($lmp_date_en)->addMonths(3)->format('Y-m-d');
        $date['to'] = Carbon::parse($lmp_date_en)->addMonths(4)->format('Y-m-d');
        return $date;
    }

    public static function secondVisitDateFromTo($lmp_date_en){
        $date['from'] = Carbon::parse($lmp_date_en)->addMonths(5)->format('Y-m-d');
        $date['to'] = Carbon::parse($lmp_date_en)->addMonths(6)->format('Y-m-d');
        return $date;
    }

    public static function thirdVisitDateFromTo($lmp_date_en){
        $date['from'] = Carbon::parse($lmp_date_en)->addMonths(7)->format('Y-m-d');
        $date['to'] = Carbon::parse($lmp_date_en)->addMonths(8)->format('Y-m-d');
        return $date;
    }

    public static function forthVisitDateFromTo($lmp_date_en){
        $date['from'] = Carbon::parse($lmp_date_en)->addMonths(8)->format('Y-m-d');
        $date['to'] = Carbon::parse($lmp_date_en)->addMonths(9)->format('Y-m-d');
        return $date;
    }
}