<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\GetHealthpostCodes;
use App\Models\Organization;
use App\Models\VaccinationRecord;
use App\Reports\FilterRequest;
use App\VialStockDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VaccinationReportsController extends Controller
{
    public function index(Request $request){
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
        $data = array();

        if ($request->has('from')){


          $start = Carbon::parse($request->from)->startOfDay();
          $end = Carbon::parse($request->to)->endOfDay();

            $data['vaccination_count'] = VaccinationRecord::whereIn('org_code', $hpCodes)
                ->whereBetween('created_at',[$start, $end])
                ->count();

            if(auth()->user()->role=="main" || auth()->user()->role == 'center') {
                $klbValleyHpCodes = Organization::whereIn('district_id', [25, 26, 27])->pluck('org_code');
                $data['klb_valley_data'] = VaccinationRecord::whereIn('org_code', $klbValleyHpCodes)
                    ->whereBetween('created_at',[$start, $end])
                    ->count();

                $provincial_org_codes = Organization::get()->groupBy('province_id');
                $district_org_codes = Organization::get()->groupBy('district_id');

                $data['provincial_data'] = [];
                foreach ($provincial_org_codes as $key => $org_code){
                    $hpCodes = $org_code->pluck('org_code');
                    $data['provincial_data'] += [
                        $key => VaccinationRecord::whereIn('org_code', $hpCodes)
                            ->whereBetween('created_at',[$start, $end])
                            ->count()
                    ];

                }

                $data['district_data'] = [];
                foreach ($district_org_codes as $key => $org_code){
                    $hpCodes = $org_code->pluck('org_code');
                    $data['district_data'] += [
                        $key => VaccinationRecord::whereIn('org_code', $hpCodes)
                            ->whereBetween('created_at',[$start, $end])
                            ->count()
                    ];
                }

                $data['organization_data'] = VaccinationRecord::
                    whereBetween('created_at',[$start, $end])
                    ->select('org_code', \DB::raw('count(*) as total'))
                    ->groupBy('org_code')
                    ->get();
            }

        }else {

            $data['vaccination_count'] = VaccinationRecord::whereIn('org_code', $hpCodes)->count();
            if (auth()->user()->role == "main" || auth()->user()->role == 'center') {
                $klbValleyHpCodes = Organization::whereIn('district_id', [25, 26, 27])->pluck('org_code');
                $data['klb_valley_data'] = VaccinationRecord::whereIn('org_code', $klbValleyHpCodes)->count();

                $provincial_org_codes = Organization::get()->groupBy('province_id');
                $district_org_codes = Organization::get()->groupBy('district_id');

                $data['provincial_data'] = [];
                foreach ($provincial_org_codes as $key => $org_code) {
                    $hpCodes = $org_code->pluck('org_code');
                    $data['provincial_data'] += [
                        $key => VaccinationRecord::whereIn('org_code', $hpCodes)->count()
                    ];

                }

                $data['district_data'] = [];
                foreach ($district_org_codes as $key => $org_code) {
                    $hpCodes = $org_code->pluck('org_code');
                    $data['district_data'] += [
                        $key => VaccinationRecord::whereIn('org_code', $hpCodes)->count()
                    ];
                }

                $data['organization_data'] = VaccinationRecord::
                select('org_code', \DB::raw('count(*) as total'))
                    ->groupBy('org_code')
                    ->get();
            }
        }
        return view('health-professional.report', ['data' => $data]);
    }
}
