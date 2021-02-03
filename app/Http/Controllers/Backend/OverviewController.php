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
            $data = Organization::where('province_id', $province->province_id)->with('user', 'province', 'municipality', 'district')->get(['token', 'name', 'province_id', 'district_id', 'municipality_id', 'hp_code']);
        }else{
            $data = Organization::with('user', 'district', 'province', 'municipality')->get(['token', 'name', 'municipality_id','province_id', 'district_id', 'hp_code']);
        }

        $health_professional = HealthProfessional::groupBy('checked_by')
            ->select('checked_by', \DB::raw('count(*) as total'))
            ->get();

        $health_professional_vaccinated = HealthProfessional::
        select('vaccinated_id', 'checked_by')
            ->join('vaccination_records', 'health_professional.id', 'vaccinated_id')
            ->groupBy('checked_by')
            ->select('checked_by as vaccinated_checked_by', \DB::raw('count(*) as total'))
            ->get();

        $merged = $data->map(function ($item) use ($health_professional_vaccinated, $health_professional) {

            $single = $health_professional->where('checked_by',$item->token)->first();

            $item['sample_total'] = ($single) ? $single->total : 0;
            $item['vaccinated_total'] = $health_professional_vaccinated->whereIn('vaccinated_checked_by', $item->token)->sum('total');

            return $item;
        });

        return view('backend.overview.index', [
            'data' => $merged
        ]);
    }
}