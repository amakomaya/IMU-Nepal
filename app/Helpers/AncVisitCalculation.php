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

        $ancs = $collection->ancs->last();

        return $ancs;
    }
}