<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Reports\FilterRequest;
use App\Models\SuspectedCase;
use Carbon\Carbon;
use Yagiten\Nepalicalendar\Calendar;

class SuspectedCaseReportController extends Controller
{
    public function casesReport(Request $request)
    {
        $response = FilterRequest::filter($request);
        foreach ($response as $key => $value) {
            $$key = $value;
        }
        $response['service_for'] = $request->service_for;
        
        $filter_date = $this->dataFromAndTo($request);
        $reporting_days = $filter_date['to_date']->diffInDays($filter_date['from_date']);

        
        if ($response['province_id'] == null)
        {
            $final_data = [];
            $request->session()->flash('message', 'Please select the above filters to view the data within the selected date range.');
            
            return view('backend.woman.report.report', compact('final_data','provinces','districts','municipalities','healthposts','province_id','district_id','municipality_id','hp_code','from_date','to_date', 'select_year', 'select_month', 'reporting_days'));
        }
        
        $data = \DB::table('women');

        if ($response['province_id'] !== null){
            $data = $data->where('women.province_id', $response['province_id']);
        }

        if ($response['district_id'] !== null){
            $data = $data->where('women.district_id', $response['district_id']);
        }

        if ($response['municipality_id'] !== null){
            $data = $data->where('women.municipality_id', $response['municipality_id']);
        }

        if($response['service_for']) {
            if($response['service_for'] == '2') {
                $data = $data->where('ancs.service_for', '2');
            }else {
                $date = $data->where('ancs.service_for', '!=', '2');
            }
        }

        $data = $data->join('ancs', 'ancs.woman_token', '=', 'women.token')
            ->join('provinces', 'women.province_id', '=', 'provinces.id')
            ->join('districts', 'women.district_id', '=', 'districts.id')
            ->join('municipalities', 'women.municipality_id', '=', 'municipalities.id')
            ->where('ancs.result', 3)
            ->whereDate('ancs.updated_at', '>', $filter_date['from_date']->toDateString())
            ->select(
                'provinces.province_name',
                'districts.district_name',
                'municipalities.municipality_name',

                'women.name',
                'women.age',
                'women.sex',
                'women.emergency_contact_one',
                'ancs.result',
                'ancs.updated_at'
            )
            ->get();
        
        $final_data = $data;
            
        return view('backend.woman.report.report', compact('final_data','provinces','districts','municipalities','healthposts','province_id','district_id','municipality_id','hp_code','from_date','to_date', 'select_year', 'select_month', 'reporting_days'));
        
        // if()

        // $woman = SuspectedCase::with(['province', 'district', 'municipality', 'latestAnc', 'ancs',
        //     'healthpost' => function($q) {
        //         $q->select('name', 'hp_code');
        //     }])
        //     ->where(function ($query){
        //         $query->whereHas('ancs', function($q){
        //             $q->where('service_for', "2")->where('result', 3);
        //         });
        //     })
        //     ->active();

    }



    private function dataFromAndTo(Request $request)
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
            'from_date' =>  $from_date_eng ?? Carbon::now()->subMonth(1)->startOfDay(),
            'to_date' => $to_date_eng ?? Carbon::now()->endOfDay()
        ];
    }
}
