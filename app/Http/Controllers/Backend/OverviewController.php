<?php

namespace App\Http\Controllers\Backend;

use App\Models\HealthProfessional;
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
            $province = \App\Models\ProvinceInfo::where('token', \Auth::user()->token)->first();
            $data = Organization::where('province_id', $province->province_id)->with('user', 'municipality')->get(['token', 'name', 'municipality_id', 'hp_code']);
        }else{
            $data = Organization::with('user', 'municipality')->get(['token', 'name', 'municipality_id', 'hp_code']);
        }

        $health_professional = HealthProfessional::groupBy('checked_by')
            ->select('checked_by', \DB::raw('count(*) as total'))
            ->get();

        $merged = $data->map(function ($item) use ($health_professional) {

            $single = $health_professional->where('checked_by',$item->token)->first();

            $item['sample_total'] = ($single) ? $single->total : 0;

            return $item;
        });

        return view('backend.overview.index', [
            'data' => $merged
        ]);
    }
}