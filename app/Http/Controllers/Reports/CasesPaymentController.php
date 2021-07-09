<?php

namespace App\Http\Controllers\Reports;

use App\Reports\FilterRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yagiten\Nepalicalendar\Calendar;

use App\Models\District;
use App\Models\Municipality;
use App\Models\PaymentCase;

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
            $response['district_id'] == null
        ){
            $data = [];
            $request->session()->flash('message', 'Please select all the above filters to view the line listing data of the selected organization within the selected date range.');
            return view('backend.cases.reports.monthly-line-listing', compact('data','provinces','districts','municipalities','healthposts','province_id','district_id','municipality_id','hp_code','from_date','to_date', 'select_year', 'select_month', 'reporting_days'));
        }

        // $municipality = Mun
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

        $district_name = District::where('id', $response['district_id'])->first()->district_name;

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

                'healthposts.name as healthpost_name',
                'healthposts.municipality_id as municipality_id'
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
            $return['healthpost_name'] = $item->healthpost_name;
            $return['municipality_name'] = Municipality::where('id', $item->municipality_id)->first()->municipality_name;

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
        return view('backend.cases.reports.monthly-line-listing', compact('data','provinces','districts','municipalities','healthposts','province_id','district_id','municipality_id','hp_code','from_date','to_date', 'select_year', 'select_month', 'reporting_days', 'district_name'));

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
            $final_data = [];
            $request->session()->flash('message', 'Please select the above filters to view the data within the selected date range.');
            return view('backend.cases.reports.overview', compact('final_data','provinces','districts','municipalities','healthposts','province_id','district_id','municipality_id','hp_code','from_date','to_date', 'select_year', 'select_month', 'reporting_days'));
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
            ->where(function($query) use ($filter_date) {
                return $query
                    ->whereDate('payment_cases.register_date_en', '>', $filter_date['from_date']->toDateString())
                    ->where('payment_cases.date_of_outcome_en', '>', $filter_date['from_date']->toDateString())
                    ->orWhere('payment_cases.date_of_outcome_en', null);
            })
            ->select([
                'healthposts.name as name',
                'healthposts.id as healthpost_id',
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

        $mapped_data_second = $data->map(function ($item) use ($filter_date){
            $return = [];
            $return['name'] = $item->name;
            $return['healthpost_id'] = $item->healthpost_id;
            $return['province_name'] = $item->province_name;
            $return['district_name'] = $item->district_name;
            $return['municipality_name'] = $item->municipality_name;

            $return['total_general'] = $return['total_hdu'] = $return['total_icu'] = $return['total_ventilator'] = 0;

            // if($item->is_death == null) {
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
                    if($difference_days > 0){
                        if($value['id'] == '2') {
                            $return['total_general'] += 1;
                        }
                        elseif($value['id'] == '3') {
                            $return['total_hdu'] += 1;
                        }
                        elseif($value['id'] == '4') {
                            $return['total_icu'] += 1;
                        }
                        elseif($value['id'] == '5') {
                            $return['total_ventilator'] += 1;
                        }
                    }
                }
            // }

            return $return;
        })->toArray();


        $all_data = $mapped_data_second;

        if(!empty($all_data)) {
            foreach ($all_data as $element) {
                $result[$element['healthpost_id']][] = $element;
            }
            
            foreach($result as $keyn => $result_solo_aray) {
                $final_data[$keyn]['general_count'] = $final_data[$keyn]['hdu_count'] = $final_data[$keyn]['icu_count'] = $final_data[$keyn]['ventilator_count'] = $final_data[$keyn]['death_count'] = $final_data[$keyn]['discharge_count'] = 0;
                $final_data[$keyn]['name'] = $result_solo_aray[0]['name'];
                $final_data[$keyn]['healthpost_id'] = $result_solo_aray[0]['healthpost_id'];
                $final_data[$keyn]['province_name'] = $result_solo_aray[0]['province_name'];
                $final_data[$keyn]['district_name'] = $result_solo_aray[0]['district_name'];
                $final_data[$keyn]['municipality_name'] = $result_solo_aray[0]['municipality_name'];
    
                foreach($result_solo_aray as $keym => $res) {
                    $final_data[$keyn]['general_count'] +=  $res['total_general'];
                    $final_data[$keyn]['hdu_count'] +=  $res['total_hdu'];
                    $final_data[$keyn]['icu_count'] +=  $res['total_icu'];
                    $final_data[$keyn]['ventilator_count'] +=  $res['total_ventilator'];
                }

                $final_data[$keyn]['total_cost'] = ($final_data[$keyn]['general_count'] * 100) +
                                                ($final_data[$keyn]['hdu_count'] * 100) +
                                                ($final_data[$keyn]['icu_count'] * 100) +
                                                ($final_data[$keyn]['ventilator_count'] * 100);
            }
        } else {
            $final_data = [];
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

        $data = PaymentCase::join('healthposts', 'payment_cases.hp_code', '=', 'healthposts.hp_code')
            ->leftjoin('provinces', 'provinces.id', '=', 'healthposts.province_id')
            ->leftjoin('districts', 'districts.id', '=', 'healthposts.district_id')
            ->leftjoin('municipalities', 'municipalities.id', '=', 'healthposts.municipality_id');

        if ($response['province_id'] !== null){
            $data = $data->where('payment_cases.province_id', $response['province_id']);
        }

        if ($response['district_id'] !== null){
            $data = $data->where('payment_cases.district_id', $response['district_id']);
        }

        if ($response['municipality_id'] !== null){
            $data = $data->where('payment_cases.municipality_id', $response['municipality_id']);
        }

        if ($response['hospital_type'] !== null){
            $data = $data->where('healthposts.hospital_type', $response['hospital_type']);
        }

        $running_period_cases = $data
            ->where(function ($query) use ($filter_date){
                $query->whereBetween('payment_cases.register_date_en', [$filter_date['from_date']->toDateString(), $filter_date['to_date']->toDateString()])
                    ->where(function($q) use ($filter_date) {
                        $q->whereDate('payment_cases.date_of_outcome_en', '>=', $filter_date['from_date']->toDateString())
                            ->orWhereNull('payment_cases.date_of_outcome_en');
                        })
                ->orWhereDate('payment_cases.register_date_en', '<=', $filter_date['from_date']->toDateString())
                    ->where(function($q2) use ($filter_date) {
                        $q2->whereDate('payment_cases.date_of_outcome_en', '>=', $filter_date['from_date']->toDateString())
                            ->orWhereNull('payment_cases.date_of_outcome_en');
                    });
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
                'healthposts.no_of_beds',
                'healthposts.no_of_hdu',
                'healthposts.no_of_icu',
                'healthposts.no_of_ventilators',
                'provinces.province_name',
                'districts.district_name',
                'municipalities.municipality_name'
            ])
            ->get();

        $mapped_data = $running_period_cases->map(function ($item) use ($filter_date) {
            $return = [];
            $return['healthpost_name'] = $item->healthpost_name;
            $return['healthpost_id'] = $item->healthpost_id;
            $return['province_id'] = $item->province_name;
            $return['district_id'] = $item->district_name;
            $return['municipality_id'] = $item->municipality_name;
            $return['no_of_beds'] = $item->no_of_beds;
            $return['no_of_hdu'] = $item->no_of_hdu;
            $return['no_of_icu'] = $item->no_of_icu;
            $return['no_of_ventilators'] = $item->no_of_ventilators;

            $return['total_general'] = $return['total_hdu'] = $return['total_icu'] = $return['total_ventilator'] = $return['death'] = $return['discharge'] = 0;

            if($item->is_death != null) {
                if (Carbon::parse($item->date_of_outcome_en)->toDateString() < $filter_date['from_date']->toDateString() || 
                Carbon::parse($item->date_of_outcome_en)->toDateString() > $filter_date['to_date']->toDateString()){}
                else {
                    if($item->is_death == '1') {
                        $return['discharge'] = 1;
                    }elseif($item->is_death == '2') {
                        $return['death'] = 1;
                    }
                }
            }
            $arr_initial_health_condition = array([
                'id' => $item->health_condition,
                'date' => $item->register_date_en
            ]);
            $array_health_condition = json_decode($item->health_condition_update, true) ?? [];
            $array_all_condition = array_merge($arr_initial_health_condition,$array_health_condition);

            if(!empty($item->date_of_outcome_en)) {
                $end_case_date = Carbon::parse($item->date_of_outcome_en)->toDateString();
            } else {
                $end_case_date = Carbon::now()->toDateString();
            }

            foreach ($array_all_condition as $i => $value){
                $next_date = array_key_exists($i + 1, $array_all_condition) ? Carbon::parse($array_all_condition[$i + 1]['date'])->toDateString() : $end_case_date;
                if ($next_date > $filter_date['to_date']->toDateString()){
                    $next_date = $filter_date['to_date']->toDateString();
                }

                if (Carbon::parse($value['date'])->toDateString() > $filter_date['to_date']->toDateString()){
                    $value['date'] = $filter_date['to_date']->toDateString();
                }

                $diff_days = Carbon::parse($value['date'])->diffInDays($next_date);
                $difference_days = array_key_exists($i + 1, $array_all_condition) ? $diff_days : $diff_days +1;
                if($difference_days > 0){
                    if($value['id'] == '1' || $value['id'] == '2') {
                        $return['total_general'] += 1;
                    }
                    elseif($value['id'] == '3') {
                        $return['total_hdu'] += 1;
                    }
                    elseif($value['id'] == '4') {
                        $return['total_icu'] += 1;
                    }
                    elseif($value['id'] == '5') {
                        $return['total_ventilator'] += 1;
                    }
                }
            }
            return $return;
        })->groupBy('healthpost_id');

        // dd($mapped_data);

        $final_data = [];
        if(!empty($mapped_data)) {
            foreach($mapped_data as $keyn => $result_solo_aray) {
                $final_data[$keyn]['general_count'] = $final_data[$keyn]['hdu_count'] = $final_data[$keyn]['icu_count'] = $final_data[$keyn]['ventilator_count'] = $final_data[$keyn]['death_count'] = $final_data[$keyn]['discharge_count'] = 0;
                $final_data[$keyn]['healthpost_name'] = $result_solo_aray[0]['healthpost_name'];
                $final_data[$keyn]['healthpost_id'] = $result_solo_aray[0]['healthpost_id'];
                $final_data[$keyn]['province_id'] = $result_solo_aray[0]['province_id'];
                $final_data[$keyn]['district_id'] = $result_solo_aray[0]['district_id'];
                $final_data[$keyn]['municipality_id'] = $result_solo_aray[0]['municipality_id'];
                $final_data[$keyn]['no_of_beds'] = $result_solo_aray[0]['no_of_beds'];
                $final_data[$keyn]['no_of_hdu'] = $result_solo_aray[0]['no_of_hdu'];
                $final_data[$keyn]['no_of_icu'] = $result_solo_aray[0]['no_of_icu'];
                $final_data[$keyn]['no_of_ventilators'] = $result_solo_aray[0]['no_of_ventilators'];
                $final_data[$keyn]['no_of_registration'] = count($result_solo_aray);
    
                foreach($result_solo_aray as $keym => $res) {
                    $final_data[$keyn]['general_count'] +=  $res['total_general'];
                    $final_data[$keyn]['hdu_count'] +=  $res['total_hdu'];
                    $final_data[$keyn]['icu_count'] +=  $res['total_icu'];
                    $final_data[$keyn]['ventilator_count'] +=  $res['total_ventilator'];
                    $final_data[$keyn]['death_count'] +=  $res['death'];
                    $final_data[$keyn]['discharge_count'] +=  $res['discharge'];
                }
            }
        }


        return view('backend.cases.reports.situation-report', compact('final_data','provinces','districts','municipalities','healthposts','province_id','district_id','municipality_id','hp_code','from_date','to_date', 'select_year', 'select_month', 'reporting_days'));

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