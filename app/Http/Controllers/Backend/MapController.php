<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\GetHealthpostCodes;
use App\Reports\FilterRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\SuspectedCase;
use GMaps;

class MapController extends Controller
{
    public function map()
    {
        return view('map.index');
    }

    public function data(){
        $data = SuspectedCase::whereNotNull('longitude')
            ->whereBetween('longitude', [79, 90])
            ->whereBetween('latitude', [26, 31])
            ->join('ancs', 'suspected_cases.token', '=', 'ancs.woman_token')
            ->whereIn('ancs.result', [3])
            ->select(\DB::raw('round(suspected_cases.latitude, 2) as latitude'), \DB::raw('round(suspected_cases.longitude, 2) as longitude'), \DB::raw('count(*) as total'))
            ->groupBy(\DB::raw('round(suspected_cases.longitude, 1)'))
            ->get()->makeHidden(['formated_age_unit', 'formated_gender']);

        $max_total = collect($data)->max('total');

        $data = collect($data)->map(function ($item) use ($max_total) {
            $item->total = $item->total / $max_total;
            return collect($item)->flatten();
        });

        return response()->json($data);
    }
}