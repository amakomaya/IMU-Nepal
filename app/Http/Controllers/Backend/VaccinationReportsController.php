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
        if(auth()->user()->role=="main" || auth()->user()->role == 'center') {
            $klbValleyHpCodes = Organization::whereIn('district_id', [25, 26, 27])->pluck('hp_code');
            $data['klb_valley_data'] = VaccinationRecord::whereIn('hp_code', $klbValleyHpCodes)->count();

            $provincial_hp_codes = Organization::get()->groupBy('province_id');
            $district_hp_codes = Organization::get()->groupBy('district_id');

            $data['provincial_data'] = [];
            foreach ($provincial_hp_codes as $key => $hp_code){
                $hpCodes = $hp_code->pluck('hp_code');
                $data['provincial_data'] += [
                    $key => VaccinationRecord::whereIn('hp_code', $hpCodes)->count()
                ];

            }

            $data['district_data'] = [];
            foreach ($district_hp_codes as $key => $hp_code){
                $hpCodes = $hp_code->pluck('hp_code');
                $data['district_data'] += [
                    $key => VaccinationRecord::whereIn('hp_code', $hpCodes)->count()
                ];
            }

            $data['organization_data'] = VaccinationRecord::
                select('hp_code', \DB::raw('count(*) as total'))
                ->groupBy('hp_code')
                ->get();
        }

//        $data['used'] = VaccinationRecord::whereIn('hp_code', $hpCodes)->count();
//        $data['stock'] = VialStockDetail::whereIn('hp_code', $hpCodes)->count();
        return view('health-professional.report', ['data' => $data]);
    }
}
