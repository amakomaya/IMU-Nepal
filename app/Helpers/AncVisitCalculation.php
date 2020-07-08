<?php


namespace App\Helpers;


use Carbon\Carbon;

class AncVisitCalculation
{
    public static function get($collection)
    {
        return self::visits($collection);
    }

    public static function visits($collection)
    {

        $ancs = $collection->ancs;

//        $plucked = $ancs->pluck('visit_date');

        $array = array();
        foreach ($ancs as $anc){
            if ($anc->service_for == null || $anc->service_for == 0){
                $visit_date = Carbon::parse($anc->visit_date);
                if ($visit_date <= Carbon::parse($collection->lmp_date_en)->addMonths(4)) {
                    array_push($array, 1);
                }
                if ($visit_date > Carbon::parse($collection->lmp_date_en)->addMonths(4) && $visit_date <= Carbon::parse($collection->lmp_date_en)->addMonths(6)) {
                    array_push($array, 2);
                }
                if ($visit_date > Carbon::parse($collection->lmp_date_en)->addMonths(6) && $visit_date <= Carbon::parse($collection->lmp_date_en)->addMonths(8)) {
                    array_push($array, 3);
                }
                if ($visit_date > Carbon::parse($collection->lmp_date_en)->addMonths(8)) {
                    array_push($array, 4);
                }
            }else {
                array_push($array, $anc->service_for);
            }
        }
        return array_values(array_unique($array));
    }
}