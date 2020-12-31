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
            $province = \App\Models\ProvinceInfo::where('token', \Auth::user()->token)->first();
            $data = Organization::where('province_id', $province->province_id)->with('user', 'municipality')->get(['token', 'name', 'municipality_id', 'hp_code']);
        }else{
            $data = Organization::with('user', 'municipality')->get(['token', 'name', 'municipality_id', 'hp_code']);
        }

        $sample = SuspectedCase::groupBy('hp_code')
            ->select('hp_code', \DB::raw('count(*) as sample_total'))
            ->get();

        $merged = $data->map(function ($item) use ($sample) {

            $single = $sample->where('hp_code',$item->hp_code)->first();

            $item['sample_total'] = ($single) ? $single->sample_total : 0;

            return $item;
        });

        return view('backend.overview.index', [
            'data' => $merged
        ]);
    }
}