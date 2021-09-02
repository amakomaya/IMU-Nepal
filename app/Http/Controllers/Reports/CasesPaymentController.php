<?php

namespace App\Http\Controllers\Reports;

use App\Reports\FilterRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yagiten\Nepalicalendar\Calendar;
use App\Helpers\GetHealthpostCodes;

use App\Models\District;
use App\Models\Municipality;
use App\Models\PaymentCase;

class CasesPaymentController extends Controller
{

    public function filterValidDate($from_date, $to_date, $check_date, $reg_date = null, $outcome_date = null) {
        $check_condition = false;
        $from_date = Carbon::parse($from_date);
        $to_date = Carbon::parse($to_date);
        $reg_date = Carbon::parse($reg_date);
        $check_date = Carbon::parse($check_date);
        if($outcome_date) {
            $outcome_date = Carbon::parse($outcome_date);
            $check_condition = $check_date->gte($reg_date) && $check_date->gte($from_date) && $check_date->lte($to_date) && $check_date->lte($outcome_date); 
        } else {
            $check_condition = $check_date->gte($reg_date) && $check_date->lte($to_date) && $check_date->gte($from_date); 
        }
        return $check_condition;
        
    }

    public function monthlyLineListing(Request $request){
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
        foreach ($response as $key => $value) {
            $$key = $value;
        }

        $filter_date = $this->dataFromAndTo($request);
        $reporting_days = $filter_date['to_date']->diffInDays($filter_date['from_date']);

        if ($response['province_id'] == null ||
            $response['district_id'] == null
        ){
            $final_data = [];
            $request->session()->flash('message', 'Please select all the above filters to view the line listing data of the selected organization within the selected date range.');
            return view('backend.cases.reports.monthly-line-listing', compact('final_data','provinces','districts','municipalities','healthposts','province_id','district_id','municipality_id','hp_code','from_date','to_date', 'select_year', 'select_month', 'reporting_days'));
        }
     
        $from_date_en = Carbon::parse($filter_date['from_date'])->toDateString();
        $to_date_en = Carbon::parse($filter_date['to_date'])->toDateString();

        $data = PaymentCase::join('healthposts', 'payment_cases.hp_code', '=', 'healthposts.hp_code')
            ->leftjoin('provinces', 'provinces.id', '=', 'healthposts.province_id')
            ->leftjoin('districts', 'districts.id', '=', 'healthposts.district_id')
            ->leftjoin('municipalities', 'municipalities.id', '=', 'healthposts.municipality_id')
            ->whereIn('payment_cases.hp_code', $hpCodes)
            ->whereIn('healthposts.hospital_type', [3,5,6])
            ->whereDate('register_date_en', '<=', $to_date_en)
            ->where(function ($query) use ($from_date_en){
                $query->whereNull('date_of_outcome_en')
                    ->orWhereDate('date_of_outcome_en', '>=', $from_date_en);
            })->select([
                'payment_cases.name as name',
                'payment_cases.hospital_register_id',
                'payment_cases.age',
                'payment_cases.gender',
                'payment_cases.guardian_name',
                'payment_cases.phone',
                'payment_cases.address',
                'payment_cases.self_free',
                'payment_cases.health_condition',
                'payment_cases.is_death',
                'payment_cases.health_condition_update',
                'payment_cases.register_date_en',
                'payment_cases.register_date_np',
                'payment_cases.date_of_outcome_en',
                'payment_cases.date_of_outcome',
                'districts.district_name',
                'municipalities.municipality_name',
                'healthposts.name as healthpost_name',
                'healthposts.municipality_id as municipality_id'
            ])
            ->orderBy('healthposts.name', 'asc')
            ->get();
        // dd(\Illuminate\Support\Str::replaceArray('?', $data->getBindings(), $data->toSql()));

        $final_data = [];
        $hc_precedence = [
            '5' => 0,
            '4' => 1,
            '3' => 2,
            '2' => 3,
            '1' => 4
        ];

        foreach($data as $key => $item) {
            $reg_date = Carbon::parse($item->register_date_en)->toDateString();
            $outcome_date = $item->date_of_outcome_en ? Carbon::parse($item->date_of_outcome_en)->toDateString() : null;
            $final_data[$key]['general_count'] = $final_data[$key]['hdu_count'] = $final_data[$key]['icu_count'] = 
                $final_data[$key]['ventilator_count'] = $final_data[$key]['no_symptoms_count'] = 0;
            $final_data[$key]['name'] = $item->name;
            $final_data[$key]['district_name'] = $item->district_name;
            $final_data[$key]['municipality_name'] = $item->municipality_name;
            $final_data[$key]['healthpost_name'] = $item->healthpost_name;
            $final_data[$key]['hospital_register_id'] = $item->hospital_register_id;
            $final_data[$key]['age'] = $item->age;
            $final_data[$key]['gender'] = $item->gender;
            $final_data[$key]['phone'] = $item->phone;
            $final_data[$key]['address'] = $item->address;
            $final_data[$key]['guardian_name'] = $item->guardian_name;
            $final_data[$key]['self_free'] = $item->self_free;
            $final_data[$key]['outcome_status'] = $item->is_death;
            $final_data[$key]['register_date'] = $item->register_date_np;
            $final_data[$key]['date_of_outcome'] = $item->date_of_outcome;
            $final_data[$key]['register_date_en'] = $item->register_date_en;
            $final_data[$key]['date_of_outcome_en'] = $item->date_of_outcome_en;
            $final_data[$key]['date_conditon_array'] = [];
            $final_data[$key]['all_dates'] = '';

            //From Registration Date or from date
            $check_date = $reg_date;

            if($this->filterValidDate($from_date_en, $to_date_en, $check_date, $reg_date, $outcome_date)){
                $final_data[$key]['date_conditon_array'][$check_date] = strval($item->health_condition);
            } else {
                $final_data[$key]['date_conditon_array'][$from_date_en] = strval($item->health_condition);
            }

            //From HC Update
            $updated_health_condition = $item->health_condition_update ? json_decode($item->health_condition_update, true) : [];
            foreach($updated_health_condition as $key_3 => $condition){
                $check_date = Carbon::parse($condition['date'])->toDateString();
                if($this->filterValidDate($from_date_en, $to_date_en, $check_date, $reg_date, $outcome_date)){
                    if(array_key_exists($check_date, $final_data[$key]['date_conditon_array'])) {
                        if($hc_precedence[$condition['id']] < $hc_precedence[$final_data[$key]['date_conditon_array'] [$check_date]]) {
                            $final_data[$key]['date_conditon_array'][$check_date] = strval($condition['id']);
                        }
                    } else {
                        $final_data[$key]['date_conditon_array'] [$check_date] = strval($condition['id']);
                    }
                }
            }

            //From Outcome date or last to date
            ksort($final_data[$key]['date_conditon_array']);
            $last_health_conditon_key = array_keys($final_data[$key]['date_conditon_array']);
            $last_health_conditon_key = end($last_health_conditon_key);
            $last_health_conditon_value = $final_data[$key]['date_conditon_array'][$last_health_conditon_key];
           
            $check_date = $outcome_date;
            if($check_date) {
                if($this->filterValidDate($from_date_en, $to_date_en, $check_date, $reg_date, $outcome_date)){
                    if(!array_key_exists($check_date, $final_data[$key]['date_conditon_array'])) {
                        $final_data[$key]['date_conditon_array'] [$check_date] = $last_health_conditon_value;
                    }
                } else {
                    $final_data[$key]['date_conditon_array'] [$to_date_en] = $last_health_conditon_value;
                }
            } else {
                $final_data[$key]['date_conditon_array'] [$to_date_en] = $last_health_conditon_value;
            }

            $beforeDateCondition = null;
            ksort($final_data[$key]['date_conditon_array']);
            $last_health_conditon_date = array_keys($final_data[$key]['date_conditon_array']);
            $last_health_conditon_date = end($last_health_conditon_date);

            //Calculate Bed usage 
            $date_array_count = count($final_data[$key]['date_conditon_array']);

            foreach($final_data[$key]['date_conditon_array'] as $date => $condition) {

                $hc = $condition;
                if($beforeDateCondition) {
                    //calculation logic
                    $parsedDate = Carbon::parse($date);
                    $parsedDateBefore = Carbon::parse($beforeDateCondition['date']);
                    $totalDays = $parsedDateBefore->diffInDays($parsedDate);

                    switch($beforeDateCondition['condition']) {
                        case '1':
                            $final_data[$key]['no_symptoms_count'] += $totalDays;
                            break;
                        case '2':
                            $final_data[$key]['general_count'] += $totalDays;
                            break;
                        case '3':
                            $final_data[$key]['hdu_count'] += $totalDays;
                            break;
                        case '4':
                            $final_data[$key]['icu_count'] += $totalDays;
                            break;
                        case '5':
                            $final_data[$key]['ventilator_count'] += $totalDays;
                            break;
                        default:
                            break;
                    }

                }


                if($date == $last_health_conditon_date) {
                    $totalDays = 1;
                    switch($condition) {
                        case '1':
                            $final_data[$key]['no_symptoms_count'] += $totalDays;
                            break;
                        case '2':
                            $final_data[$key]['general_count'] += $totalDays;
                            break;
                        case '3':
                            $final_data[$key]['hdu_count'] += $totalDays;
                            break;
                        case '4':
                            $final_data[$key]['icu_count'] += $totalDays;
                            break;
                        case '5':
                            $final_data[$key]['ventilator_count'] += $totalDays;
                            break;
                        default:
                            break;
                    }
                        $final_data[$key]['all_dates'] .= '(Last Date) ' . $this->engToNep($date) . ' (' . $this->allHealth($condition) . ')'; 

                }else {
                    $final_data[$key]['all_dates'] .= $this->engToNep($date) . ' (' . $this->allHealth($condition) . ')<br>';
                }
                $beforeDateCondition = array(
                    'date' => $date,
                    'condition' => $condition
                );

               
            }
        }
        
        return view('backend.cases.reports.monthly-line-listing', compact('final_data','provinces','districts','municipalities','healthposts','province_id','district_id','municipality_id','hp_code','from_date','to_date', 'select_year', 'select_month', 'reporting_days'));

    }

    public function overview(Request $request){
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
        foreach ($response as $key => $value) {
            $$key = $value;
        }

        $filter_date = $this->dataFromAndTo($request);
        $reporting_days = $filter_date['to_date']->diffInDays($filter_date['from_date']);

        if ($response['province_id'] == null)
        {
            $final_data = [];
            $request->session()->flash('message', 'Please select the above filters to view the data within the selected date range.');
            return view('backend.cases.reports.overview', compact('final_data','provinces','districts','municipalities','healthposts','province_id','district_id','municipality_id','hp_code','from_date','to_date', 'select_year', 'select_month', 'reporting_days'));
        }
     
        $from_date_en = Carbon::parse($filter_date['from_date'])->toDateString();
        $to_date_en = Carbon::parse($filter_date['to_date'])->toDateString();

        $data = PaymentCase::join('healthposts', 'payment_cases.hp_code', '=', 'healthposts.hp_code')
            ->leftjoin('provinces', 'provinces.id', '=', 'healthposts.province_id')
            ->leftjoin('districts', 'districts.id', '=', 'healthposts.district_id')
            ->leftjoin('municipalities', 'municipalities.id', '=', 'healthposts.municipality_id')
            ->where('payment_cases.self_free', 2)
            ->whereIn('payment_cases.hp_code', $hpCodes)
            ->whereIn('healthposts.hospital_type', [3,5,6])
            ->whereDate('register_date_en', '<=', $to_date_en)
            ->where(function ($query) use ($from_date_en){
                $query->whereNull('date_of_outcome_en')
                    ->orWhereDate('date_of_outcome_en', '>=', $from_date_en);
            })
            ->select([
                'healthposts.name as name',
                'healthposts.id as healthpost_id',
                'healthposts.phone',
                'healthposts.hp_code as hp_code',
                'provinces.province_name',
                'districts.district_name',
                'municipalities.municipality_name',
                'payment_cases.health_condition',
                'payment_cases.is_death',
                'payment_cases.health_condition_update',
                'payment_cases.register_date_en',
                'payment_cases.date_of_outcome_en',
            ])
            ->orderBy('healthposts.name', 'asc')
            ->get()
            ->groupBy('healthpost_id');

        $final_data = [];
        $sub_final_data = [];
        $hc_precedence = [
            '5' => 0,
            '4' => 1,
            '3' => 2,
            '2' => 3,
            '1' => 4
        ];

        foreach($data as $key => $item_arrays){
            $final_data[$key]['general_count'] = $final_data[$key]['hdu_count'] = $final_data[$key]['icu_count'] = 
                $final_data[$key]['ventilator_count'] = $final_data[$key]['no_symptoms_count'] = 0;
            $final_data[$key]['healthpost_name'] = $item_arrays->first()->name;
            $final_data[$key]['healthpost_id'] = $item_arrays->first()->healthpost_id;
            $final_data[$key]['province_id'] = $item_arrays->first()->province_name;
            $final_data[$key]['district_id'] = $item_arrays->first()->district_name;
            $final_data[$key]['municipality_id'] = $item_arrays->first()->municipality_name;

            foreach($item_arrays as $key_2 => $item) {
                $reg_date = Carbon::parse($item->register_date_en)->toDateString();
                $outcome_date = $item->date_of_outcome_en ? Carbon::parse($item->date_of_outcome_en)->toDateString() : null;
                $sub_final_data[$key_2]['date_conditon_array'] = [];

                //From Registration Date or from date
                $check_date = $reg_date;

                if($this->filterValidDate($from_date_en, $to_date_en, $check_date, $reg_date, $outcome_date)){
                    $sub_final_data[$key_2]['date_conditon_array'][$check_date] = strval($item->health_condition);
                } else {
                    $sub_final_data[$key_2]['date_conditon_array'][$from_date_en] = strval($item->health_condition);
                }

                //From HC Update
                $updated_health_condition = $item->health_condition_update ? json_decode($item->health_condition_update, true) : [];
                foreach($updated_health_condition as $key_3 => $condition){
                    $check_date = Carbon::parse($condition['date'])->toDateString();
                    if($this->filterValidDate($from_date_en, $to_date_en, $check_date, $reg_date, $outcome_date)){
                        if(array_key_exists($check_date, $sub_final_data[$key_2]['date_conditon_array'])) {
                            if($hc_precedence[$condition['id']] < $hc_precedence[$sub_final_data[$key_2]['date_conditon_array'] [$check_date]]) {
                                $sub_final_data[$key_2]['date_conditon_array'][$check_date] = strval($condition['id']);
                            }
                        } else {
                            $sub_final_data[$key_2]['date_conditon_array'] [$check_date] = strval($condition['id']);
                        }
                    }
                }

                //From Outcome date or last to date
                ksort($sub_final_data[$key_2]['date_conditon_array']);
                $last_health_conditon_key = array_keys($sub_final_data[$key_2]['date_conditon_array']);
                $last_health_conditon_key = end($last_health_conditon_key);
                $last_health_conditon_value = $sub_final_data[$key_2]['date_conditon_array'][$last_health_conditon_key];
            
                $check_date = $outcome_date;
                if($check_date) {
                    if($this->filterValidDate($from_date_en, $to_date_en, $check_date, $reg_date, $outcome_date)){
                        if(!array_key_exists($check_date, $sub_final_data[$key_2]['date_conditon_array'])) {
                            $sub_final_data[$key_2]['date_conditon_array'] [$check_date] = $last_health_conditon_value;
                        }
                    } else {
                        $sub_final_data[$key_2]['date_conditon_array'] [$to_date_en] = $last_health_conditon_value;
                    }
                } else {
                    $sub_final_data[$key_2]['date_conditon_array'] [$to_date_en] = $last_health_conditon_value;
                }

                $beforeDateCondition = null;
                ksort($sub_final_data[$key_2]['date_conditon_array']);
                $last_health_conditon_date = array_keys($sub_final_data[$key_2]['date_conditon_array']);
                $last_health_conditon_date = end($last_health_conditon_date);

                //Calculate Bed usage 
                foreach($sub_final_data[$key_2]['date_conditon_array'] as $date => $condition) {
                    $hc = $condition;
                    if($beforeDateCondition) {
                        //calculation logic
                        $parsedDate = Carbon::parse($date);
                        $parsedDateBefore = Carbon::parse($beforeDateCondition['date']);
                        $totalDays = $parsedDateBefore->diffInDays($parsedDate);

                        switch($beforeDateCondition['condition']) {
                            case '1':
                                $final_data[$key]['no_symptoms_count'] += $totalDays;
                                break;
                            case '2':
                                $final_data[$key]['general_count'] += $totalDays;
                                break;
                            case '3':
                                $final_data[$key]['hdu_count'] += $totalDays;
                                break;
                            case '4':
                                $final_data[$key]['icu_count'] += $totalDays;
                                break;
                            case '5':
                                $final_data[$key]['ventilator_count'] += $totalDays;
                                break;
                            default:
                                break;
                        }
                    }

                    if($date == $last_health_conditon_date) {
                        $totalDays = 1;
                        switch($condition) {
                            case '1':
                                $final_data[$key]['no_symptoms_count'] += $totalDays;
                                break;
                            case '2':
                                $final_data[$key]['general_count'] += $totalDays;
                                break;
                            case '3':
                                $final_data[$key]['hdu_count'] += $totalDays;
                                break;
                            case '4':
                                $final_data[$key]['icu_count'] += $totalDays;
                                break;
                            case '5':
                                $final_data[$key]['ventilator_count'] += $totalDays;
                                break;
                            default:
                                break;
                        }
                    }
                    $beforeDateCondition = array(
                        'date' => $date,
                        'condition' => $condition
                    );
                
                }
            }                
        }

        return view('backend.cases.reports.overview', compact('final_data','provinces','districts','municipalities','healthposts','province_id','district_id','municipality_id','hp_code','from_date','to_date', 'select_year', 'select_month', 'reporting_days'));
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
            $final_data = $grandsum = [];
            $request->session()->flash('message', 'Please select all the above filters to view the data within the selected date range.');
            return view('backend.cases.reports.daily-listing', compact('final_data','provinces','districts','municipalities','healthposts','province_id','district_id','municipality_id','hp_code','from_date','to_date', 'select_year', 'select_month', 'reporting_days', 'grandsum'));
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
        $total_general = $total_hdu = $total_icu = $total_ventilator = $grand_total_cost = 0;
        foreach($result as $keyn => $result_solo_aray) {
            $final_data[$keyn]['general_count'] = $final_data[$keyn]['hdu_count'] = $final_data[$keyn]['icu_count'] = $final_data[$keyn]['ventilator_count'] = 0;

            $date_en_array = explode("-", $keyn);
            $date_en = Calendar::eng_to_nep($date_en_array[0], $date_en_array[1], $date_en_array[2])->getYearMonthDay();

            $final_data[$keyn]['date_np'] = $date_en;
            foreach($result_solo_aray as $keym => $res) {
                if($res['health_condition'] == '2') {
                    $final_data[$keyn]['general_count'] +=  $res['days_difference'];
                    $total_general += $res['days_difference'];
                }
                elseif($res['health_condition'] == '3') {
                    $final_data[$keyn]['hdu_count'] +=  $res['days_difference'];
                    $total_hdu += $res['days_difference'];
                }
                elseif($res['health_condition'] == '4') {
                    $final_data[$keyn]['icu_count'] +=  $res['days_difference'];
                    $total_icu +=  $res['days_difference'];
                }
                elseif($res['health_condition'] == '5') {
                    $final_data[$keyn]['ventilator_count'] +=  $res['days_difference'];
                    $total_ventilator += $res['days_difference'];
                }
            }

            $final_data[$keyn]['total_cost'] = ($final_data[$keyn]['general_count'] * 100) +
                                                ($final_data[$keyn]['hdu_count'] * 100) +
                                                ($final_data[$keyn]['icu_count'] * 100) +
                                                ($final_data[$keyn]['ventilator_count'] * 100);
            $grand_total_cost += $final_data[$keyn]['total_cost'];
        }

        $grandsum['total_general'] = $total_general;
        $grandsum['total_hdu'] = $total_hdu;
        $grandsum['total_icu'] = $total_icu;
        $grandsum['total_ventilator'] = $total_ventilator;
        $grandsum['grand_total_cost'] = $grand_total_cost;

        return view('backend.cases.reports.daily-listing', compact('final_data', 'grandsum', 'provinces','districts','municipalities','healthposts','province_id','district_id','municipality_id','hp_code','from_date','to_date', 'select_year', 'select_month', 'reporting_days'));

    }

    public function situationReport(Request $request){
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
        foreach ($response as $key => $value) {
            $$key = $value;
        }

        $response['hospital_type'] = $request->hospital_type;

        $filter_date = $this->dataFromAndTo($request);
        $reporting_days = $filter_date['to_date']->diffInDays($filter_date['from_date']);

        if ($response['province_id'] == null){
            $final_data = [];
            $request->session()->flash('message', 'Please select all the above filters to view the data within the selected date range.');
            return view('backend.cases.reports.situation-report', compact('final_data','provinces','districts','municipalities','healthposts','province_id','district_id','municipality_id','from_date','to_date', 'select_year', 'select_month', 'reporting_days'));
        }
     
        $from_date_en = Carbon::parse($filter_date['from_date'])->toDateString();
        $to_date_en = Carbon::parse($filter_date['to_date'])->toDateString();

        $data = PaymentCase::join('healthposts', 'payment_cases.hp_code', '=', 'healthposts.hp_code')
            ->leftjoin('provinces', 'provinces.id', '=', 'healthposts.province_id')
            ->leftjoin('districts', 'districts.id', '=', 'healthposts.district_id')
            ->leftjoin('municipalities', 'municipalities.id', '=', 'healthposts.municipality_id')
            ->whereIn('payment_cases.hp_code', $hpCodes);

        if ($response['hospital_type'] !== null){
            $data = $data->where('healthposts.hospital_type', $response['hospital_type']);
        }
        
        $running_period_cases = $data
            ->whereDate('register_date_en', '<=', $to_date_en)
            ->where(function ($query) use ($from_date_en){
                $query->whereNull('date_of_outcome_en')
                    ->orWhereDate('date_of_outcome_en', '>=', $from_date_en);
            })
            ->select([
                'payment_cases.health_condition',
                'payment_cases.is_death',
                'payment_cases.health_condition_update',
                'payment_cases.register_date_en',
                'payment_cases.register_date_np',
                'payment_cases.date_of_outcome_en',
                'payment_cases.date_of_outcome',
                'healthposts.name as healthpost_name',
                'healthposts.id as healthpost_id',
                'healthposts.province_id',
                'healthposts.district_id',
                'healthposts.municipality_id',
                'healthposts.hospital_type',
                'healthposts.no_of_beds',
                'healthposts.no_of_hdu',
                'healthposts.no_of_icu',
                'healthposts.no_of_ventilators',
                'provinces.province_name',
                'districts.district_name',
                'municipalities.municipality_name'
            ])
            ->get()
            ->groupBy('healthpost_id');

        $from_date_np = $filter_date['from_date']->toDateString();
        $to_date_np = $filter_date['to_date']->toDateString();
            
        $final_data = [];
        $hc_precedence = [
            '5' => 0,
            '4' => 1,
            '3' => 2,
            '2' => 3,
            '1' => 4
        ];

        // dd($running_period_cases);
        
        foreach($running_period_cases as $key => $item_arrays){
            $final_data[$key]['general_count'] = $final_data[$key]['hdu_count'] = $final_data[$key]['icu_count'] = 
                $final_data[$key]['ventilator_count'] = $final_data[$key]['death_count'] = $final_data[$key]['discharge_count'] = 0;
            $final_data[$key]['healthpost_name'] = $item_arrays->first()->healthpost_name;
            $final_data[$key]['healthpost_id'] = $item_arrays->first()->healthpost_id;
            $final_data[$key]['province_id'] = $item_arrays->first()->province_name;
            $final_data[$key]['district_id'] = $item_arrays->first()->district_name;
            $final_data[$key]['municipality_id'] = $item_arrays->first()->municipality_name;
            $final_data[$key]['hospital_type'] = $this->healthpostTypeParse($item_arrays->first()->hospital_type);
            $final_data[$key]['no_of_beds'] = $item_arrays->first()->no_of_beds;
            $final_data[$key]['no_of_hdu'] = $item_arrays->first()->no_of_hdu;
            $final_data[$key]['no_of_icu'] = $item_arrays->first()->no_of_icu;
            $final_data[$key]['no_of_ventilators'] = $item_arrays->first()->no_of_ventilators;

            foreach($item_arrays as $key_2 => $item) {
                $reg_date = Carbon::parse($item->register_date_en)->toDateString();
                $outcome_date = $item->date_of_outcome_en ? Carbon::parse($item->date_of_outcome_en)->toDateString() : null;
                $sub_final_data[$key_2]['date_conditon_array'] = [];

                //From Registration Date or from date
                $check_date = $reg_date;

                if($this->filterValidDate($from_date_en, $to_date_en, $check_date, $reg_date, $outcome_date)){
                    $sub_final_data[$key_2]['date_conditon_array'][$check_date] = strval($item->health_condition);
                } else {
                    $sub_final_data[$key_2]['date_conditon_array'][$from_date_en] = strval($item->health_condition);
                }

                //From HC Update
                $updated_health_condition = $item->health_condition_update ? json_decode($item->health_condition_update, true) : [];
                foreach($updated_health_condition as $key_3 => $condition){
                    $check_date = Carbon::parse($condition['date'])->toDateString();
                    if($this->filterValidDate($from_date_en, $to_date_en, $check_date, $reg_date, $outcome_date)){
                        if(array_key_exists($check_date, $sub_final_data[$key_2]['date_conditon_array'])) {
                            if($hc_precedence[$condition['id']] < $hc_precedence[$sub_final_data[$key_2]['date_conditon_array'] [$check_date]]) {
                                $sub_final_data[$key_2]['date_conditon_array'][$check_date] = strval($condition['id']);
                            }
                        } else {
                            $sub_final_data[$key_2]['date_conditon_array'][$check_date] = strval($condition['id']);
                        }
                    }
                }
                
                //From Outcome date or last to date
            
                $check_date = $outcome_date;
                if($check_date) {
                    if($this->filterValidDate($from_date_en, $to_date_en, $check_date, $reg_date, $outcome_date)){
                        if($item->is_death == '1'){
                            $final_data[$key]['discharge_count']++;
                        }elseif($item->is_death == '2'){
                            $final_data[$key]['death_count']++;
                        }
                    }
                }

                //Calculate Bed usage 
                foreach($sub_final_data[$key_2]['date_conditon_array'] as $date => $condition) {
                    //calculation logic

                    switch($condition) {
                        case '1':
                            $final_data[$key]['general_count']++;
                            break;
                        case '2':
                            $final_data[$key]['general_count']++;
                            break;
                        case '3':
                            $final_data[$key]['hdu_count']++;
                            break;
                        case '4':
                            $final_data[$key]['icu_count']++;
                            break;
                        case '5':
                            $final_data[$key]['ventilator_count']++;
                            break;
                        default:
                            break;
                    }
                
                }
            }
        }

        return view('backend.cases.reports.situation-report', compact('final_data','provinces','districts','municipalities','healthposts','province_id','district_id','municipality_id','hp_code','from_date','to_date', 'select_year', 'select_month', 'reporting_days'));

    }

    public function checkHealthCondition($condition_id){
        if($condition_id == '3') {
            $health_type = 'hdu_count';
        }
        elseif($condition_id == '4') {
            $health_type = 'icu_count';
        }
        elseif($condition_id == '5') {
            $health_type = 'ventilator_count';
        }
        else {
            $health_type = 'general_count';
        }
        return $health_type;
    }

    public function checkIsDeathCondtition($id_death_id){
        if($id_death_id == '1') {
            $is_death_type = 'discharge_count';
        }
        else {
            $is_death_type = 'death_count';
        }
        return $is_death_type;
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

    private function allHealth($condition_id)
    {
        switch ($condition_id){
            case '1':
                return 'No Symptoms';
            case '2':
                return 'General';
            case '3':
                return 'HDU';
            case '4':
                return 'ICU';
            case '5':
                return 'Ventilator';
            default:
                break;
        }
    }

    private function engToNep($date)
    {
        $date_en = explode("-", Carbon::parse($date)->format('Y-m-d'));
        $date_np = Calendar::eng_to_nep($date_en[0], $date_en[1], $date_en[2])->getYearMonthDayEngToNep();
        return $date_np;
    }

    private function healthpostTypeParse($temp)
    {
        switch ($temp){
            case '1':
                return 'HOME Isolation & CICT';
            case '2':
                return 'PCR Lab Test Only';
            case '3':
                return 'PCR Lab & Treatment (Hospital)';
            case '4':
                return 'Normal';
            case '5':
                return 'Institutional Isolation';
            case '6':
                return 'Hospital without PCR Lab';
            default:
                return 'N/A';
        }
    }
}