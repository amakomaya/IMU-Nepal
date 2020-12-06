<?php

namespace App\Http\Controllers\Backend;

use App\Models\OrganizationMember;
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
            $data = Organization::with('user', 'municipality')->get(['token', 'name', 'district_id', 'hp_code']);
            $province = \App\Models\ProvinceInfo::where('token', \Auth::user()->token)->first();
            $data = $data->where('province_id', $province->province_id);
        }else{
            $data = Organization::with('user', 'municipality')->get(['token', 'name', 'municipality_id', 'hp_code']);
        }
        return view('backend.overview.index', compact('data'));
    }
}