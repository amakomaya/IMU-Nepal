<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\GetHealthpostCodes;
use App\Models\Organization;
use App\Models\VaccinationRecord;
use App\Reports\FilterRequest;
use App\VialStockDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VaccinationReportsController extends Controller
{
    public function index(Request $request){
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
        $data = array();
        $data['vaccination_count'] = VaccinationRecord::whereIn('hp_code', $hpCodes)->count();
        if(auth()->user()->role=="main") {
            $klbValleyHpCodes = Organization::whereIn('district_id', [25, 26, 27])->pluck('hp_code');
            $data['klb_valley_data'] = VaccinationRecord::whereIn('hp_code', $klbValleyHpCodes)->count();
        }

//        $data['used'] = VaccinationRecord::whereIn('hp_code', $hpCodes)->count();
//        $data['stock'] = VialStockDetail::whereIn('hp_code', $hpCodes)->count();
        return view('health-professional.report', ['data' => $data]);
    }
}
