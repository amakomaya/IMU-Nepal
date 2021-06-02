<?php

namespace App\Http\Controllers\Reports;

use App\Reports\FilterRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yagiten\Nepalicalendar\Calendar;

class CasesPaymentController extends Controller
{
    public function monthlyLineListing(Request $request){
        $response = FilterRequest::filter($request);
        foreach ($response as $key => $value) {
            $$key = $value;
        }

        $filter_date = $this->dataFromAndTo($request);
        $reporting_days = $filter_date['to_date']->diffInDays($filter_date['from_date']);

        if ($response['province_id'] == null ||
            $response['municipality_id'] == null ||
            $response['district_id'] == null
        ){
            $data = [];
            $request->session()->flash('message', 'Please select all the above filters to view the line listing data of the selected organization within the selected date range.');
            return view('backend.cases.reports.monthly-line-listing', compact('data','provinces','districts','municipalities','healthposts','province_id','district_id','municipality_id','hp_code','from_date','to_date', 'select_year', 'select_month', 'reporting_days'));
        }

        $data = \DB::table('payment_cases')->whereIn('healthposts.hospital_type', [3,5,6]);

        if ($response['province_id'] !== null){
            $data = $data->where('healthposts.province_id', $response['province_id']);
        }

        if ($response['district_id'] !== null){
            $data = $data->where('healthposts.district_id', $response['district_id']);
        }

        if ($response['municipality_id'] !== null){
            $data = $data->where('healthposts.municipality_id', $response['municipality_id']);
        }

        if ($response['hp_code'] !== null){
            $data = $data->where('healthposts.hp_code', $response['hp_code']);
        }

        $running_period_cases = $data
            ->join('healthposts', 'payment_cases.hp_code', '=', 'healthposts.hp_code')
            ->where(function($query) use ($filter_date) {
                return $query
                    ->whereDate('payment_cases.register_date_en', '>', $filter_date['from_date']->toDateString())
                    ->where('payment_cases.date_of_outcome_en', '>', $filter_date['from_date']->toDateString())
                    ->orWhere('payment_cases.date_of_outcome_en', null);
            })->select([
                'payment_cases.name as name',
                'payment_cases.age',
                'payment_cases.gender',
                'payment_cases.guardian_name',
                'payment_cases.phone',
                'payment_cases.self_free',

                'payment_cases.health_condition',
                'payment_cases.is_death',
                'payment_cases.health_condition_update',
                'payment_cases.register_date_en',
                'payment_cases.register_date_np',
                'payment_cases.date_of_outcome_en',
                'payment_cases.date_of_outcome',
            ])
            ->orderBy('healthposts.name', 'asc')
            ->get();

        $mapped_data = $running_period_cases->map(function ($item) use ($filter_date) {
            $return = [];
            $return['name'] = $item->name;
            $return['age'] = $item->age;
            $return['gender'] = $this->genderParse($item->gender);
            $return['guardian_name'] = $item->guardian_name;
            $return['phone'] = $item->phone;
            $return['paid_free'] = $this->selfFreeParse($item->self_free);

            $arr_initial_health_condition = array([
                'id' => $item->health_condition,
                'date' => $item->register_date_en
            ]);
            $array_health_condition = json_decode($item->health_condition_update, true) ?? [];
            $array_all_condition = array_merge($arr_initial_health_condition,$array_health_condition);

            if(!empty($item->date_of_outcome_en)) {
                $end_case_date = Carbon::parse($item->date_of_outcome_en);
            } else {
                $end_case_date = Carbon::now();
            }
            $days_calculation_array = [];
            foreach ($array_all_condition as $i => $value){
                $next_date = array_key_exists($i + 1, $array_all_condition) ? Carbon::parse($array_all_condition[$i + 1]['date']) : $end_case_date;
                if (Carbon::parse($next_date)->lessThan($filter_date['from_date']->toDateString())){
                    $next_date = $filter_date['from_date']->toDateString();
                }


                if (Carbon::parse($value['date'])->lessThan($filter_date['from_date']->toDateString())){
                    $value['date'] = $filter_date['from_date']->toDateString();
                }

                $diff_days = Carbon::parse($value['date'])->diffInDays($next_date);
                $difference_days = array_key_exists($i + 1, $array_all_condition) ? $diff_days : $diff_days +1;

                $single_array = [
                    'health_condition' => $value['id'],
                    'days_difference' => $difference_days
                ];

                array_push($days_calculation_array, $single_array);
            }

            $return['no_of_days_in_general_bed'] = 0;
            $return['no_of_days_in_hdu_bed'] = 0;
            $return['no_of_days_in_icu_bed'] = 0;
            $return['no_of_days_in_ventilator'] = 0;

            foreach ($days_calculation_array as $arr){
                switch ($arr['health_condition']){
                    case 1:
                    case 2:
                        $return['no_of_days_in_general_bed'] = $return['no_of_days_in_general_bed'] + $arr['days_difference'];
                        break;
                    case 3:
                        $return['no_of_days_in_hdu_bed'] = $return['no_of_days_in_hdu_bed'] + $arr['days_difference'];
                        break;
                    case 4:
                        $return['no_of_days_in_icu_bed'] = $return['no_of_days_in_icu_bed'] + $arr['days_difference'];
                        break;
                    case 5:
                        $return['no_of_days_in_ventilator'] = $return['no_of_days_in_ventilator'] + $arr['days_difference'];
                        break;
                }
            }
            $return['total_no_of_days'] =   $return['no_of_days_in_general_bed'] +
                                            $return['no_of_days_in_hdu_bed'] +
                                            $return['no_of_days_in_icu_bed'] +
                                            $return['no_of_days_in_ventilator'];
            $return['register_date_en'] = $item->register_date_np;
            $return['outcome_status'] = $this->outcomeStatusParse($item->is_death);
            $return['date_of_outcome_en'] = $item->date_of_outcome;
            return $return;
        })->values();
        $data = $mapped_data;
        return view('backend.cases.reports.monthly-line-listing', compact('data','provinces','districts','municipalities','healthposts','province_id','district_id','municipality_id','hp_code','from_date','to_date', 'select_year', 'select_month', 'reporting_days'));

    }

    public function overview(Request $request){

        $response = FilterRequest::filter($request);
        foreach ($response as $key => $value) {
            $$key = $value;
        }

        $filter_date = $this->dataFromAndTo($request);
        $reporting_days = $filter_date['to_date']->diffInDays($filter_date['from_date']);

        if ($response['province_id'] == null)
        {
            $data = [];
            $request->session()->flash('message', 'Please select the above filters to view the data within the selected date range.');
            return view('backend.cases.reports.overview', compact('data','provinces','districts','municipalities','healthposts','province_id','district_id','municipality_id','hp_code','from_date','to_date', 'select_year', 'select_month', 'reporting_days'));
        }

        $data = \DB::table('payment_cases')->whereIn('healthposts.hospital_type', [3,5,6]);

        if ($response['province_id'] !== null){
            $data = $data->where('healthposts.province_id', $response['province_id']);
        }

        if ($response['district_id'] !== null){
            $data = $data->where('healthposts.district_id', $response['district_id']);
        }

        if ($response['municipality_id'] !== null){
            $data = $data->where('healthposts.municipality_id', $response['municipality_id']);
        }

        if ($response['hp_code'] !== null){
            $data = $data->where('healthposts.hp_code', $response['hp_code']);
        }


        $data = $data->join('healthposts', 'payment_cases.hp_code', '=', 'healthposts.hp_code')
            ->join('districts', 'healthposts.district_id', '=', 'districts.id')
            ->join('provinces', 'healthposts.province_id', '=', 'provinces.id')
            ->join('municipalities', 'healthposts.municipality_id', '=', 'municipalities.id')
            ->select([
                'healthposts.name as name',
                'provinces.province_name',
                'districts.district_name',
                'municipalities.municipality_name',
                'healthposts.phone',
                'healthposts.hp_code as hp_code',

                'payment_cases.health_condition',
                'payment_cases.is_death',
                'payment_cases.health_condition_update',
                'payment_cases.register_date_en',
                'payment_cases.date_of_outcome_en',
            ])
            ->orderBy('healthposts.name', 'asc')
            ->get();

        $filter_date = $this->dataFromAndTo($request);


        $mapped_data = [];
        $mapped_data_second = collect($mapped_data)->map(function ($value){
            $return = [];
            $value = collect($value);
            $return['name'] = $value[0]['name'];
            $return['province_name'] = $value[0]['province_name'];
            $return['district_name'] = $value[0]['district_name'];
            $return['municipality_name'] = $value[0]['municipality_name'];
            $return['phone'] = $value[0]['phone'];

            $return['used_general'] = collect($value)->sum('used_general');
            $return['used_hdu'] = collect($value)->sum('used_hdu');
            $return['used_icu'] = collect($value)->sum('used_icu');
            $return['used_ventilators'] = collect($value)->sum('used_ventilators');

            return $return;
        })->values();

        $data = $mapped_data_second;

        return view('backend.cases.reports.overview', compact('data','provinces','districts','municipalities','healthposts','province_id','district_id','municipality_id','hp_code','from_date','to_date', 'select_year', 'select_month', 'reporting_days'));
    }
    public function dailyListing(Request $request){
        $response = FilterRequest::filter($request);
        foreach ($response as $key => $value) {
            $$key = $value;
        }

        $filter_date = $this->dataFromAndTo($request);
        $reporting_days = $filter_date['to_date']->diffInDays($filter_date['from_date']);

        if ($response['province_id'] == null ||
            $response['municipality_id'] == null ||
            $response['district_id'] == null
        ){
            $final_data = [];
            $request->session()->flash('message', 'Please select all the above filters to view the data within the selected date range.');
            return view('backend.cases.reports.daily-listing', compact('final_data','provinces','districts','municipalities','healthposts','province_id','district_id','municipality_id','hp_code','from_date','to_date', 'select_year', 'select_month', 'reporting_days'));
        }

        $data = \DB::table('payment_cases')->whereIn('healthposts.hospital_type', [3,5,6]);

        if ($response['province_id'] !== null){
            $data = $data->where('healthposts.province_id', $response['province_id']);
        }

        if ($response['district_id'] !== null){
            $data = $data->where('healthposts.district_id', $response['district_id']);
        }

        if ($response['municipality_id'] !== null){
            $data = $data->where('healthposts.municipality_id', $response['municipality_id']);
        }

        if ($response['hp_code'] !== null){
            $data = $data->where('healthposts.hp_code', $response['hp_code']);
        }

        $daily_cases = $data
            ->join('healthposts', 'payment_cases.hp_code', '=', 'healthposts.hp_code')
            ->where(function($query) use ($filter_date) {
                return $query
                    ->whereDate('payment_cases.register_date_en', '>', $filter_date['from_date']->toDateString())
                    ->where('payment_cases.date_of_outcome_en', '>', $filter_date['from_date']->toDateString())
                    ->orWhere('payment_cases.date_of_outcome_en', null);
            })->select([
                'payment_cases.health_condition',
                'payment_cases.is_death',
                'payment_cases.health_condition_update',
                'payment_cases.register_date_en',
                'payment_cases.register_date_np',
                'payment_cases.date_of_outcome_en',
                'payment_cases.date_of_outcome',
            ])
            ->orderBy('healthposts.name', 'asc')
            ->get();

        $mapped_data = $daily_cases->map(function ($item) use ($filter_date) {
            $return = [];

            $arr_initial_health_condition = array([
                'id' => $item->health_condition,
                'date' => $item->register_date_en
            ]);
            $array_health_condition = json_decode($item->health_condition_update, true) ?? [];
            $array_all_condition = array_merge($arr_initial_health_condition,$array_health_condition);

            if(!empty($item->date_of_outcome_en)) {
                $end_case_date = Carbon::parse($item->date_of_outcome_en);
            } else {
                $end_case_date = Carbon::now();
            }
            $days_calculation_array = [];
            foreach ($array_all_condition as $i => $value){
                $next_date = array_key_exists($i + 1, $array_all_condition) ? Carbon::parse($array_all_condition[$i + 1]['date']) : $end_case_date;
                if (Carbon::parse($next_date)->lessThan($filter_date['from_date']->toDateString())){
                    $next_date = $filter_date['from_date']->toDateString();
                }


                if (Carbon::parse($value['date'])->lessThan($filter_date['from_date']->toDateString())){
                    $value['date'] = $filter_date['from_date']->toDateString();
                }

                $diff_days = Carbon::parse($value['date'])->diffInDays($next_date);
                $difference_days = array_key_exists($i + 1, $array_all_condition) ? $diff_days : $diff_days +1;

                $single_array = [
                    'health_condition' => $value['id'],
                    'days_difference' => $difference_days,
                    'admitted_date' => date('Y-m-d',strtotime($value['date']))
                ];

                array_push($days_calculation_array, $single_array);
            }

            return (array)$days_calculation_array;
        })->toArray();
        $all_data_array = $mapped_data;

        $all_data = [];
        foreach($all_data_array as $keyy => $solo) {
            foreach($solo as $keyz => $solo2) {
                array_push($all_data, $solo2);
            }
        }

        foreach ($all_data as $element) {
            $result[$element['admitted_date']][] = $element;
        }
        ksort($result);

        $final_data = [];
        foreach($result as $keyn => $result_solo_aray) {
            $final_data[$keyn]['general_count'] = $final_data[$keyn]['hdu_count'] = $final_data[$keyn]['icu_count'] = $final_data[$keyn]['ventilator_count'] = 0;
            foreach($result_solo_aray as $keym => $res) {
                if($res['health_condition'] == '2') {
                    $final_data[$keyn]['general_count'] +=  $res['days_difference'];
                }
                elseif($res['health_condition'] == '3') {
                    $final_data[$keyn]['hdu_count'] +=  $res['days_difference'];
                }
                elseif($res['health_condition'] == '4') {
                    $final_data[$keyn]['icu_count'] +=  $res['days_difference'];
                }
                elseif($res['health_condition'] == '5') {
                    $final_data[$keyn]['ventilator_count'] +=  $res['days_difference'];
                }
            }

            $final_data[$keyn]['total_cost'] = ($final_data[$keyn]['general_count'] * 100) +
                                                ($final_data[$keyn]['hdu_count'] * 100) +
                                                ($final_data[$keyn]['icu_count'] * 100) +
                                                ($final_data[$keyn]['ventilator_count'] * 100);
        }
        
        return view('backend.cases.reports.daily-listing', compact('final_data','provinces','districts','municipalities','healthposts','province_id','district_id','municipality_id','hp_code','from_date','to_date', 'select_year', 'select_month', 'reporting_days'));

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

    private function genderParse($gender)
    {
        switch ($gender){
            case 1:
                return "Male";
            case 2:
                return "Female";
            default:
                return "Other";
        }
    }

    private function selfFreeParse($self_free)
    {
        switch ($self_free){
            case 1:
                return 'Paid';
            case 2:
                return 'Free';
            default:
                return 'N/A';
        }

    }

    private function outcomeStatusParse($is_death)
    {
        switch ($is_death){
            case 1:
                return 'Discharge';
            case 2:
                return 'Death';
            default:
                return 'Under Treatment';
        }
    }
}