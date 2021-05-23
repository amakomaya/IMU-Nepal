<?php

namespace App\Http\Controllers;

use App\Helpers\GetHealthpostCodes;
use App\Models\Organization;
use App\Models\OrganizationMember;
use App\Models\PaymentCase;
use App\Models\ProvinceInfo;
use App\Reports\FilterRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;

class PublicDataController extends Controller
{
    public function index() {
        if(Auth::user()->role == 'province') {
            $province_id = ProvinceInfo::where('token', Auth::user()->token)->first()->province_id;
        }elseif(Auth::user()->role == 'main') {
            $province_id = null;
        }else {
            return redirect('/admin');
        }
        return view('public.home.index', compact('province_id'));
    }

    public function publicPortal(Request $request){
        $data = \DB::table('payment_cases')->whereIn('healthposts.hospital_type', [3,5]);
//            ->whereNull('payment_cases.is_death');

            if($request->has('organization_type')){
                $data = $data->where('healthposts.hospital_type', $request->get('organization_type'));
            }

            if ($request->has('province_id')){
                $data = $data->where('healthposts.province_id', $request->get('province_id'));
            }

            if ($request->has('district_id')){
                $data = $data->where('healthposts.district_id', $request->get('district_id'));
            }

            if ($request->has('municipality_id')){
                $data = $data->where('healthposts.municipality_id', $request->get('municipality_id'));
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
                'healthposts.address',
                'healthposts.phone',
                'healthposts.hp_code as hp_code',

                'healthposts.no_of_beds',
                'healthposts.no_of_ventilators',
                'healthposts.no_of_icu',
                'healthposts.no_of_hdu',

                'healthposts.daily_consumption_of_oxygen as daily_capacity_in_liter',
                'healthposts.is_oxygen_facility as oxygen_availability',
                'payment_cases.health_condition',
                'payment_cases.is_death',
                'payment_cases.health_condition_update',
                'payment_cases.register_date_en',
                'payment_cases.date_of_outcome_en',
            ])
            ->orderBy('healthposts.name', 'asc')
            ->get();

        $mapped_data = $data->map(function ($value) {
            $return = [];
            $return['name'] = $value->name;
            $return['province_name'] = $value->province_name;
            $return['district_name'] = $value->district_name;
            $return['municipality_name'] = $value->municipality_name;
            $return['address'] = $value->address;
            $return['phone'] = $value->phone;
            $return['hp_code'] = $value->hp_code;

            $return['total_general'] = $value->no_of_beds ;
            $return['total_hdu'] = $value->no_of_hdu;
            $return['total_ventilators'] = $value->no_of_ventilators;
            $return['total_icu'] = $value->no_of_icu;

            $return['daily_capacity_in_liter'] = $value->daily_capacity_in_liter;
            $return['oxygen_availability'] = $value->oxygen_availability;

            $return['is_admission'] = 0;
            $return['is_death'] = 0;
            $return['is_discharge'] = 0;

            $parse_register_date = Carbon::parse($value->register_date_en);
            if(!empty($value->date_of_outcome_en)){
                $parse_date_of_outcome_en = Carbon::parse($value->date_of_outcome_en);
                if($parse_date_of_outcome_en->isToday()){
                    if ($value->is_death === '1'){
                        $return['is_discharge'] = 1;
                    }else{
                        $return['is_death'] = 1;
                    }
                }

            }

            if($parse_register_date->isToday()){
                $return['is_admission'] = 1;
            }

            if ($value->health_condition_update == null){
                $return['health_condition'] = $value->health_condition;
            }else{
                $array_health_condition = json_decode($value->health_condition_update, true);
                $return['health_condition'] = collect($array_health_condition)->sortBy('date')->first()['id'];
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
            $return['address'] = $value[0]['address'];
            $return['phone'] = $value[0]['phone'];

            $return['total_general'] = $value[0]['total_general'];
            $return['total_hdu'] = $value[0]['total_hdu'];
            $return['total_icu'] = $value[0]['total_icu'];
            $return['total_ventilators'] = $value[0]['total_ventilators'];

            $return['today_total_admission'] = collect($value)->where('is_admission', 1)->count();
            $return['today_total_death'] = collect($value)->where('is_death', 1)->count();
            $return['today_total_discharge'] = collect($value)->where('is_discharge', 1)->count();

            $return['used_general'] = collect($value)->whereIn('health_condition', ["1","2"])->count();
            $return['used_hdu'] = collect($value)->where('health_condition', "3")->count();
            $return['used_icu'] = collect($value)->where('health_condition', "4")->count();
            $return['used_ventilators'] = collect($value)->where('health_condition', "5")->count();

            $return['daily_capacity_in_liter'] = $value[0]['daily_capacity_in_liter'];
            $return['oxygen_availability'] = $value[0]['oxygen_availability'];

            return $return;
        })->values();
        return response()->json(['organizations' => $mapped_data_second]);
    }
}