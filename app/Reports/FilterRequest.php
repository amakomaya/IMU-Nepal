<?php

namespace App\Reports;

use App\Models\District;
use App\Models\Healthpost;
use App\Models\HealthWorker;
use App\Models\Municipality;
use App\Models\Province;
use App\Models\Ward;
use Auth;

class FilterRequest
{
    public static function filter($request)
    {
        $province_id = $request->get('province_id');
        $district_id = $request->get('district_id');
        $municipality_id = $request->get('municipality_id');
        $ward_or_healthpost = $request->get('ward_or_healthpost');
        $ward_id = $request->get('ward_id');
        $ward_no = Ward::getWardNo($ward_id);
        $hp_code = $request->get('hp_code');
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');
        $select_year = $request->get('select_year');
        $select_month = $request->get('select_month');
        $loggedInToken = Auth::user()->token;
//        $options = collect(['ward', 'healthpost']);
        $options = collect(['healthpost']);

        if (Auth::user()->role == "healthpost") {
            $hp_code = "";
            $healthpost = Healthpost::where('token', Auth::user()->token)->get()->first();
            if (count($hp_code) > 0) {
                $hp_code = $healthpost->hp_code;
            }
        }

        if (Auth::user()->role == "province") {
            $province_id = Province::modelProvinceInfo($loggedInToken)->province_id;
            $provinces = Province::where('id', $province_id)->orderBy('province_name', 'asc')->get();
            $districts = District::where('province_id', $province_id)->orderBy('district_name', 'asc')->get();
            $municipalities = Municipality::where('district_id', $district_id)->orderBy('municipality_name', 'asc')->get();
            $wards = Ward::where('municipality_id', $municipality_id)->orderBy('ward_no', 'asc')->get();
            $healthposts = Healthpost::where([['municipality_id', $municipality_id]])->orderBy('name', 'asc')->get();
        } elseif (Auth::user()->role == "dho") {
            $district_id = District::modelDistrictInfo($loggedInToken)->district_id;
            $province_id = District::provinceIdByDistrictId($district_id);
            $provinces = Province::where('id', $province_id)->orderBy('province_name', 'asc')->get();
            $districts = District::where('id', $district_id)->orderBy('district_name', 'asc')->get();
            $municipalities = Municipality::where('district_id', $district_id)->orderBy('municipality_name', 'asc')->get();
            $wards = Ward::where('municipality_id', $municipality_id)->orderBy('ward_no', 'asc')->get();
            $healthposts = Healthpost::where([['municipality_id', $municipality_id]])->orderBy('name', 'asc')->get();
        } elseif (Auth::user()->role == "municipality") {

            $municipality_id = Municipality::modelMunicipalityInfo($loggedInToken)->municipality_id;
            $district_id = Municipality::modelMunicipalityInfo($loggedInToken)->district_id;
            $province_id = Municipality::modelMunicipalityInfo($loggedInToken)->province_id;
            $provinces = Province::where('id', $province_id)->orderBy('province_name', 'asc')->get();
            $districts = District::where('id', $district_id)->orderBy('district_name', 'asc')->get();
            $municipalities = Municipality::where('id', $municipality_id)->orderBy('municipality_name', 'asc')->get();
            $wards = Ward::where('municipality_id', $municipality_id)->orderBy('ward_no', 'asc')->get();
            $healthposts = Healthpost::where([['municipality_id', $municipality_id]])->orderBy('name', 'asc')->get();
        } elseif (Auth::user()->role == "ward") {
            $ward_id = Ward::modelWard($loggedInToken)->id;
            $ward_no = Ward::getWardNo($ward_id);
            $municipality_id = Ward::modelWard($loggedInToken)->municipality_id;
            $district_id = Ward::modelWard($loggedInToken)->district_id;
            $province_id = Ward::modelWard($loggedInToken)->province_id;
            $provinces = Province::where('id', $province_id)->orderBy('province_name', 'asc')->get();
            $districts = District::where('id', $district_id)->orderBy('district_name', 'asc')->get();
            $municipalities = Municipality::where('id', $municipality_id)->orderBy('municipality_name', 'asc')->get();
            $ward_or_healthpost = 'ward' . $municipality_id;
            $options = collect(['ward']);
            $wards = Ward::where('id', $ward_id)->orderBy('ward_no', 'asc')->get();
            $healthposts = Healthpost::where([['ward_no', $ward_no], ['municipality_id', $municipality_id]])->orderBy('name', 'asc')->get();

        } elseif (Auth::user()->role == "healthpost") {
            $healthpost_id = Healthpost::modelHealthpost($loggedInToken)->id;
            $hp_code = Healthpost::modelHealthpost($loggedInToken)->hp_code;
            $ward_no = Healthpost::modelHealthpost($loggedInToken)->ward_no;
            $ward_or_healthpost = 'healthpost' . $municipality_id;
            $options = collect(['healthpost']);
            $municipality_id = Healthpost::modelHealthpost($loggedInToken)->municipality_id;
            $district_id = Healthpost::modelHealthpost($loggedInToken)->district_id;
            $province_id = Healthpost::modelHealthpost($loggedInToken)->province_id;
            $provinces = Province::where('id', $province_id)->orderBy('province_name', 'asc')->get();
            $districts = District::where('id', $district_id)->orderBy('district_name', 'asc')->get();
            $municipalities = Municipality::where('id', $municipality_id)->orderBy('municipality_name', 'asc')->get();
            $wards = Ward::where([['municipality_id', $municipality_id]])->orderBy('ward_no', 'asc')->get();
            $healthposts = Healthpost::where('id', $healthpost_id)->orderBy('name', 'asc')->get();

        } elseif(Auth::user()->role == "healthworker"){
            $healthworker = HealthWorker::where('token', $loggedInToken)->first();
            $hp_code = $healthworker->hp_code;
//            $ward_no = Healthpost::modelHealthpost($loggedInToken)->ward_no;
            $ward_or_healthpost = 'healthpost' . $municipality_id;
            $options = collect(['healthpost']);
            $municipality_id = $healthworker->municipality_id;
            $district_id = $healthworker->district_id;
            $province_id = $healthworker->province_id;
            $provinces = Province::where('id', $province_id)->orderBy('province_name', 'asc')->get();
            $districts = District::where('id', $district_id)->orderBy('district_name', 'asc')->get();
            $municipalities = Municipality::where('id', $municipality_id)->orderBy('municipality_name', 'asc')->get();
            $wards = Ward::where([['municipality_id', $municipality_id]])->orderBy('ward_no', 'asc')->get();
            $healthposts = Healthpost::where('hp_code', $hp_code)->orderBy('name', 'asc')->get();
        } else{
            $provinces = Province::all();
            $districts = District::where('province_id', $province_id)->orderBy('district_name', 'asc')->get();
            $municipalities = Municipality::where('district_id', $district_id)->orderBy('municipality_name', 'asc')->get();
            $wards = Ward::where('municipality_id', $municipality_id)->orderBy('ward_no', 'asc')->get();
            $healthposts = Healthpost::where([['municipality_id', $municipality_id]])->orderBy('name', 'asc')->get();
        }
        $request = compact('provinces', 'districts', 'municipalities', 'options', 'ward_or_healthpost', 'wards', 'healthposts', 'province_id', 'district_id', 'municipality_id', 'ward_id', 'ward_no', 'hp_code', 'from_date', 'to_date','select_year','select_month');
        return $request;
    }
}