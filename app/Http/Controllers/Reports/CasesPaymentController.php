<?php

namespace App\Http\Controllers\Reports;

use App\Reports\FilterRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yagiten\Nepalicalendar\Calendar;

class CasesPaymentController extends Controller
{
    public function overview(Request $request){

        $response = FilterRequest::filter($request);
        foreach ($response as $key => $value) {
            $$key = $value;
        }

        $data = $response;
        return view('backend.cases.report.overview', compact('data','provinces','districts','municipalities','healthposts','province_id','district_id','municipality_id','hp_code','from_date','to_date', 'select_year', 'select_month'));


    }
}
