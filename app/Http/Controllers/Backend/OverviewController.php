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

    private function filter($type)
    {
        if(\Auth::user()->role == 'province'){
            $province = \App\Models\ProvinceInfo::where('token', \Auth::user()->token)->first();
            $data = Organization::where('province_id', $province->province_id)
                ->where('hospital_type', $type)
                ->with('user', 'province', 'municipality', 'district')
                ->get(['token', 'name', 'province_id', 'district_id', 'municipality_id', 'hp_code']);
        }else{
            $data = Organization::with('user', 'district', 'province', 'municipality')
                ->where('hospital_type', $type)
                ->get(['token', 'name', 'municipality_id','province_id', 'district_id', 'hp_code']);
        }

        $suspected_case = SuspectedCase::
            groupBy('hp_code')
            ->select('hp_code', \DB::raw('count(*) as total'));

        $sample_collection = SampleCollection::
        groupBy('hp_code')
            ->select('hp_code', \DB::raw('count(*) as total'));
//
//        $lab_test = LabTest::
//        groupBy('checked_by')
//            ->select('checked_by', \DB::raw('count(*) as total'));

        $merged = $data->map(function ($item) use ($sample_collection, $suspected_case){
            $item['total_cases'] = $suspected_case->where('hp_code', $item->hp_code)->first()->total ?? 0;
            $item['sample_collection_total'] = $sample_collection->where('hp_code', $item->hp_code)->first()->total ?? 0;
           return $item;
        });


        return view('backend.overview.index', [
            'data' => $merged
        ]);

    }

}