<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vaccine extends Model
{
    // BCG vaccine name in database
    public  $BCG_VACCINE = "BCG";

    // Pentavalent vaccine name in database
    public  $PENTAVALENT_VACCINE = "Pentavalent";

    // OPV vaccine name in database
    public  $OPV_VACCINE = "OPV";

    // PCV vaccine name in database
    public  $PCV_VACCINE = "PCV";

    // IPV vaccine name in database
    public  $IPV_VACCINE = "IPV";

    // MR vaccine name in database
    public  $MR_VACCINE = "MR";

    // JE vaccine name in database
    public  $JE_VACCINE = "JE";

    //Birth vaccine period
    public  $BIRTH_PERIOD = "Birth";

    //Six Week vaccine period
    public  $SIX_WEEK_PERIOD = "6W";

    //10 Week vaccine period
    public  $TEN_WEEK_PERIOD = "10W";

    //14 Week vaccine period
    public  $FOURTEEN_WEEK_PERIOD = "14W";

    //9 Month vaccine period
    public  $NINE_MONTH_PERIOD = "9M";

    //12 Month vaccine period
    public  $TWELVE_MONTH_PERIOD = "12M";

    //15 Month vaccine period
    public $FIFTEEN_MONTH_PERIOD = "15M";
}
