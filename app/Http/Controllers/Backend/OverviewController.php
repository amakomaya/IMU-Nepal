<?php

namespace App\Http\Controllers\Backend;

use App\Models\HealthProfessional;
use App\Models\LabTest;
use App\Models\OrganizationMember;
use App\Models\SampleCollection;
use App\Models\SuspectedCase;
use App\Reports\DateFromToRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Organization;

class OverviewController extends Controller
{
    public function index()
    {
        if(\Auth::user()->role == 'province'){
            $province = \App\Models\ProvinceInfo::where('token', \Auth::user()->token)->first();
            $data = Organization::where('province_id', $province->province_id)->with('user', 'province', 'municipality', 'district')->latest()->get(['token', 'name', 'province_id', 'district_id', 'municipality_id', 'org_code']);
        }else{
            $data = Organization::with('user', 'district', 'province', 'municipality')->latest()->get(['token', 'name', 'municipality_id','province_id', 'district_id', 'org_code']);
        }

        return view('backend.overview.index', [
            'data' => $data
        ]);
    }

    public function cict()
    {
        return $this->filter(1);
    }

    public function hospital()
    {
        return $this->filter(5);
    }

    public function labtest()
    {
        return $this->filter(2);
    }

    public function both()
    {
        return $this->filter(3);
    }

    public function normal()
    {
        return $this->filter(4);
    }

    public function hospitalnopcr()
    {
        return $this->filter(6);
    }

    public function poe()
    {
        return $this->filter(7);
    }

    public function vaccination()
    {
        return $this->filter(8);
    }

    public function search(){
        return view('backend.overview.search');
    }

    private function filter($type)
    {
        if(\Auth::user()->role == 'province'){
            $province = \App\Models\ProvinceInfo::where('token', \Auth::user()->token)->first();
            $data = Organization::where('province_id', $province->province_id)
                ->where('hospital_type', $type)
                ->with('user', 'province', 'municipality', 'district')
                ->get(['token', 'name', 'province_id', 'district_id', 'municipality_id', 'org_code']);
        }else{
            $data = Organization::with('user', 'district', 'province', 'municipality')
                ->where('hospital_type', $type)
                ->get(['token', 'name', 'municipality_id','province_id', 'district_id', 'org_code']);
        }

        return view('backend.overview.index', [
            'data' => $data
        ]);

    }

}