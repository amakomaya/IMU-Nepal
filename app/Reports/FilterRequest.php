<?php

namespace App\Reports;

use App\Models\District;
use App\Models\Organization;
use App\Models\OrganizationMember;
use App\Models\Municipality;
use App\Models\Province;
use App\Models\Ward;
use Auth;
use Illuminate\Support\Facades\Cache;

class FilterRequest
{
    public static function filter($request)
    {
        $province_id = $request->get('province_id');
        $district_id = $request->get('district_id');
        $municipality_id = $request->get('municipality_id');
        $org_code = $request->get('org_code');
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');
        $select_year = $request->get('select_year');
        $select_month = $request->get('select_month');
        $loggedInToken = Auth::user()->token;

        if (Auth::user()->role == "healthpost") {
            $org_code = "";
            $healthpost = Organization::where('token', Auth::user()->token)->get()->first();
            if (count($org_code) > 0) {
                $org_code = $healthpost->org_code;
            }
        }

        if (Auth::user()->role == "province") {
            $province_id = Province::modelProvinceInfo($loggedInToken)->province_id;
            $provinces = Province::where('id', $province_id)->orderBy('province_name', 'asc')->get();
            $districts = District::where('province_id', $province_id)->orderBy('district_name', 'asc')->get();
            $municipalities = Municipality::where('district_id', $district_id)->orderBy('municipality_name', 'asc')->get();
            $organizations = Organization::where([['municipality_id', $municipality_id]])->orderBy('name', 'asc')->get();
        } elseif (Auth::user()->role == "dho") {
            $district_id = District::modelDistrictInfo($loggedInToken)->district_id;
            $province_id = District::provinceIdByDistrictId($district_id);
            $provinces = Province::where('id', $province_id)->orderBy('province_name', 'asc')->get();
            $districts = District::where('id', $district_id)->orderBy('district_name', 'asc')->get();
            $municipalities = Municipality::where('district_id', $district_id)->orderBy('municipality_name', 'asc')->get();
            $organizations = Organization::where([['municipality_id', $municipality_id]])->orderBy('name', 'asc')->get();
        } elseif (Auth::user()->role == "municipality") {
            $municipality_id = Municipality::modelMunicipalityInfo($loggedInToken)->municipality_id;
            $district_id = Municipality::modelMunicipalityInfo($loggedInToken)->district_id;
            $province_id = Municipality::modelMunicipalityInfo($loggedInToken)->province_id;
            $provinces = Province::where('id', $province_id)->orderBy('province_name', 'asc')->get();
            $districts = District::where('id', $district_id)->orderBy('district_name', 'asc')->get();
            $municipalities = Municipality::where('id', $municipality_id)->orderBy('municipality_name', 'asc')->get();
            $organizations = Organization::where([['municipality_id', $municipality_id]])->orderBy('name', 'asc')->get();
        } elseif (Auth::user()->role == "healthpost") {
            $healthpost_id = Organization::modelHealthpost($loggedInToken)->id;
            $org_code = Organization::modelHealthpost($loggedInToken)->org_code;
            $ward_or_healthpost = 'healthpost' . $municipality_id;
            $municipality_id = Organization::modelHealthpost($loggedInToken)->municipality_id;
            $district_id = Organization::modelHealthpost($loggedInToken)->district_id;
            $province_id = Organization::modelHealthpost($loggedInToken)->province_id;
            $provinces = Province::where('id', $province_id)->orderBy('province_name', 'asc')->get();
            $districts = District::where('id', $district_id)->orderBy('district_name', 'asc')->get();
            $municipalities = Municipality::where('id', $municipality_id)->orderBy('municipality_name', 'asc')->get();
            $organizations = Organization::where('id', $healthpost_id)->orderBy('name', 'asc')->get();

        } elseif(Auth::user()->role == "healthworker"){
            $healthworker = OrganizationMember::where('token', $loggedInToken)->first();
            $org_code = $healthworker->org_code;
            $municipality_id = $healthworker->municipality_id;
            $district_id = $healthworker->district_id;
            $province_id = $healthworker->province_id;
            $provinces = Province::where('id', $province_id)->orderBy('province_name', 'asc')->get();
            $districts = District::where('id', $district_id)->orderBy('district_name', 'asc')->get();
            $municipalities = Municipality::where('id', $municipality_id)->orderBy('municipality_name', 'asc')->get();
            $organizations = Organization::where('org_code', $org_code)->orderBy('name', 'asc')->get();
        } else{
            $provinces = Cache::remember('province-list', 48*60*60, function () {
              return Province::select(['id', 'province_name'])->get();
            });
            $districts = District::where('province_id', $province_id)->orderBy('district_name', 'asc')->get();
            $municipalities = Municipality::where('district_id', $district_id)->orderBy('municipality_name', 'asc')->get();
            $organizations = Organization::where([['municipality_id', $municipality_id]])->orderBy('name', 'asc')->get();
        }
        $request = compact('provinces', 'districts', 'municipalities', 'organizations', 'province_id', 'district_id', 'municipality_id', 'org_code', 'from_date', 'to_date','select_year','select_month');
        return $request;
    }
}