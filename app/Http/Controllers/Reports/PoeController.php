<?php

namespace App\Http\Controllers\Reports;
use App\Models\SuspectedCase;
use App\Models\SampleCollection;
use App\Models\Organization;
use App\Models\Province;
use App\Models\District;
use App\Models\LabTest;
use App\Models\ContactTracing;
use App\Models\ContactTracingOld;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use DB;
use App\Helpers\GetHealthpostCodes;
use App\Reports\FilterRequest;
use Yagiten\Nepalicalendar\Calendar;
use Carbon\Carbon;

class PoeController extends Controller
{

    private function dataFromAndTo(Request $request, $subDaysDefault = 1)
    {
        if (!empty($request['from_date'])) {
            $from_date_array = explode("-", $request['from_date']);
            $from_date_eng = Carbon::parse(Calendar::nep_to_eng($from_date_array[0], $from_date_array[1], $from_date_array[2])->getYearMonthDay())->startOfDay();
        }
        if (!empty($request['to_date'])) {
            $to_date_array = explode("-", $request['to_date']);
            $to_date_eng = Carbon::parse(Calendar::nep_to_eng($to_date_array[0], $to_date_array[1], $to_date_array[2])->getYearMonthDay())->endOfDay();
        }

        return [
            'from_date' =>  $from_date_eng ?? Carbon::now()->subDays($subDaysDefault)->startOfDay(),
            'to_date' => $to_date_eng ?? Carbon::now()->endOfDay(),
            'default_from_date' => Carbon::now()->subDays(1)->startOfDay()
        ];
    }

    public function poeReport(Request $request) {
      
        $response = FilterRequest::filter($request);
        $response['hospital_type'] = 7;
        $hpCodes = GetHealthpostCodes::filter($response);
        
        $filter_date = $this->dataFromAndTo($request);
        
        $reporting_days = $filter_date['to_date']->diffInDays($filter_date['from_date']) + 1;
        $default_from_date = $filter_date['default_from_date'];
        foreach ($response as $key => $value) {
            $$key = $value;
        }

        $healthposts = Organization::whereIn('hp_code', $hpCodes)->where('hospital_type', 7)->get();

        $reports = SampleCollection::leftjoin('healthposts', 'ancs.hp_code', '=', 'healthposts.hp_code')
            ->whereIn('ancs.hp_code', $hpCodes)
            ->whereIn('ancs.result', [3, 4, 2, 9])
            ->whereBetween(\DB::raw('DATE(ancs.sample_test_date_en)'), [$filter_date['from_date']->toDateString(), $filter_date['to_date']->toDateString()]);

        if ($response['province_id'] !== null) {
            $reports = $reports->where('healthposts.province_id', $response['province_id']);
        }

        if($response['district_id'] !== null){
            $reports = $reports->where('healthposts.district_id', $response['district_id']);
        }
        if($response['municipality_id'] !== null){
            $reports = $reports->where('healthposts.municipality_id', $response['municipality_id']);
        }

        $reports = $reports->get()
            ->groupBy('hp_code');

        
            // var_dump($reports->getBindings());
            // dd($reports);
        
        $data = [];
        $total_data['all_total_screened'] = $total_data['all_total_tested'] = $total_data['all_antigen_postive_cases_count'] = 
        $total_data['all_antigen_negative_cases_count'] = $total_data['all_positivity_rate'] = 0;
        foreach($hpCodes as $key => $hpCode)  {
          $data[$hpCode]['not_tested'] = SuspectedCase::where('hp_code', $hpCode)
          ->whereBetween(\DB::raw('DATE(created_at)'), [$filter_date['from_date']->toDateString(), $filter_date['to_date']->toDateString()])
          ->active()
          ->doesnthave('ancs')
          ->count();
        }
        foreach($reports as $key => $report) {
            // $district_name = District::where('id', $report[0]->district_id)->pluck('district_name')[0];
            $province_name = Province::where('id', $report[0]->province_id)->pluck('province_name')[0];
           
            $healthpost_name = $report[0]->name;

            $data[$key]['healthpost_name'] = $healthpost_name;
            $data[$key]['province_name'] = $province_name;
            // $data[$key]['district_name'] = $district_name;
            // $data[$key]['municipality_name'] = '';
            
            
            $data[$key]['total_test'] = $report->count()??0;
            
            //municipality
            $data[$key]['antigen_pending_received'] = $report->where('service_for', '2')->where('result', 2)->count() ?? 0+$report->where('service_for', '2')->where('result', 9)->count()??0;
            
            $data[$key]['antigen_postive_cases_count'] = $report->where('service_for', '2')->where('result', 3)->count() ?? 0;
            $data[$key]['antigen_negative_cases_count'] = $report->where('service_for', '2')->where('result', 4)->count() ?? 0;
            $data[$key]['total_screened'] = $data[$key]['not_tested'] + $data[$key]['antigen_pending_received'] +
                $data[$key]['antigen_postive_cases_count'] + $data[$key]['antigen_negative_cases_count'];
            $data[$key]['total_tested'] = $data[$key]['antigen_pending_received'] +
                $data[$key]['antigen_postive_cases_count'] + $data[$key]['antigen_negative_cases_count'];
            $pr = round(($data[$key]['antigen_postive_cases_count'] / $data[$key]['total_tested'] * 100), 2);
            $data[$key]['positivity_rate'] = $pr?$pr.'%':'-';
            // $data[$key]['malaria_tested'] = $report->where('service_for', '2')->where('result', 4)->count();
            // $data[$key]['last_tested_date'] = '2078-02-10';
            // $data[$key]['last_positive_date'] = '2078-02-10';

            $total_data['all_total_screened'] += $data[$key]['total_screened'];
            $total_data['all_total_tested'] += $data[$key]['total_tested'];
            $total_data['all_antigen_postive_cases_count'] += $data[$key]['antigen_postive_cases_count'];
            $total_data['all_antigen_negative_cases_count'] += $data[$key]['antigen_negative_cases_count'];
        }

        $all_pr = round(($total_data['all_antigen_postive_cases_count'] / $total_data['all_total_tested'] * 100), 2);
        if($total_data['all_antigen_postive_cases_count'] == 0){
            $total_data['all_positivity_rate'] = '0%';
        }else{
            $total_data['all_positivity_rate'] = $all_pr ? $all_pr . '%' : '0%';
        }

        // dd($data);
        return view('backend.sample.report.poe-report', compact('data', 'total_data', 'provinces','districts','municipalities','healthposts','province_id','district_id','municipality_id','hp_code','from_date','to_date', 'select_year', 'select_month', 'reporting_days', 'default_from_date'));
    }
}
