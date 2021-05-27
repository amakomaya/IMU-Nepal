<?php

namespace App\Http\Controllers\Reports;

use App\Reports\FilterRequest;
use Carbon\Carbon;
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

        $mapped_data = $data->map(function ($value) use ($filter_date) {
            $return = [];
            $return['name'] = $value->name;
            $return['province_name'] = $value->province_name;
            $return['district_name'] = $value->district_name;
            $return['municipality_name'] = $value->municipality_name;
            $return['phone'] = $value->phone;
            $return['hp_code'] = $value->hp_code;

            $return['is_admission'] = 0;
            $return['is_death'] = $value->is_death;
            $return['is_discharge'] = 0;

            $parse_register_date = Carbon::parse($value->register_date_en);



            if(!empty($value->date_of_outcome_en)){
                $parse_date_of_outcome_en = Carbon::parse($value->date_of_outcome_en);
                if($parse_date_of_outcome_en->isToday()){
                    if ($value->is_death === '1'){
                        $return['is_discharge'] = 11;
                    }
                    if($value->is_death === '2'){
                        $return['is_death'] = 12;
                    }
                }
            }

            if ($value->health_condition_update == null){
                $return['health_condition'] = $value->health_condition;
            }else{
                $array_health_condition = json_decode($value->health_condition_update, true);
                $return['health_condition'] = collect($array_health_condition)->last()['id'];
            }

            if($parse_register_date->isToday()){
                $return['is_admission'] = 10;
            }

            return $return;
        })->groupBy(function($item) {
            return $item['hp_code'];
        });


        $mapped_data_second = $mapped_data->map(function ($value){
            $return = [];
            $value = collect($value);
            $return['name'] = $value[0]['name'];
            $return['province_name'] = $value[0]['province_name'];
            $return['district_name'] = $value[0]['district_name'];
            $return['municipality_name'] = $value[0]['municipality_name'];
            $return['phone'] = $value[0]['phone'];

            $return['used_general'] = collect($value)->where('is_death', null)->whereIn('health_condition', [1,2])->count();
            $return['used_hdu'] = collect($value)->where('is_death', null)->where('health_condition', 3)->count();
            $return['used_icu'] = collect($value)->where('is_death', null)->where('health_condition', 4)->count();
            $return['used_ventilators'] = collect($value)->where('is_death', null)->where('health_condition', 5)->count();

            return $return;
        })->values();

        $data = $mapped_data_second;

        return view('backend.cases.report.overview', compact('data','provinces','districts','municipalities','healthposts','province_id','district_id','municipality_id','hp_code','from_date','to_date', 'select_year', 'select_month'));
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