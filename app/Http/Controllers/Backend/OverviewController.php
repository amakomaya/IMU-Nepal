<?php

namespace App\Http\Controllers\Backend;

use App\Models\HealthWorker;
use App\Models\Woman;
use App\Reports\DateFromToRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Healthpost;

class OverviewController extends Controller
{
    public function index()
    {
        $data = HealthPost::all();

        if(\Auth::user()->role == 'province'){
            $province = \App\Models\ProvinceInfo::where('token', \Auth::user()->token)->first();
            $data = $data->where('province_id', $province->province_id);
        }
        return view('backend.overview.index', compact('data'));
    }

    public function fchv(Request $request)
    {
        $date = $this->dataFromAndTo($request);

        $fchv = HealthWorker::where('role', 'fchv')->where('status', 1)->get();

        foreach ($fchv as $item) {
            $woman = Woman::where('created_by', $item->token)->active();
            $item['woman_total'] = $woman->count();
            $item['woman_monthly'] = $woman->fromToDate($date['from_date'], $date['to_date'])->count();
        }
        $data = $fchv;
        return view('backend.overview.fchv', compact('data'));
    }

    private function dataFromAndTo(Request $request)
    {
        return DateFromToRequest::dateFromTo($request);
    }
}