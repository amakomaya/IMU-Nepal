<?php

namespace App\Http\Controllers\Data\Api;

use App\Models\Organization;
use App\Models\province;
use App\Models\SampleCollection;
use App\Models\SuspectedCase;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AggregateController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        $hpCodesbyProvince = Organization::get()->groupBy('province_id');

        $data = [
            'registered' => SuspectedCase::active()->count(),
            'registered_in_24_hrs' => SuspectedCase::active()->where('created_at', '>', Carbon::now()->subDay()->toDateString())->count(),
            'sample_collection' =>SampleCollection::active()->count(),
            'sample_collection_in_24_hrs' => SampleCollection::active()->where('created_at', '>', Carbon::now()->subDay()->toDateString())->count(),
            'lab_result_positive' => SampleCollection::where('result', 3)->active()->count(),
            'lab_result_positive_in_24_hrs' => SampleCollection::where('result', 3)->where('updated_at', '>', Carbon::now()->subDay()->toDateString())->active()->count(),
            'lab_result_negative' => SampleCollection::where('result', 4)->active()->count(),
            'lab_result_negative_in_24_hrs' => SampleCollection::where('result', 4)->where('updated_at', '>', Carbon::now()->subDay()->toDateString())->active()->count(),
        ];

        $forecast = [
            'registered' => DB::table('women')
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
                ->groupBy('date')
                ->get(),

            'sample_collection' => DB::table('ancs')
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
                ->groupBy('date')
                ->get(),

            'lab_result_positive' => DB::table('ancs')
                ->where('result', 3)
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
                ->groupBy('date')
                ->get(),

            'lab_result_negative' => DB::table('ancs')
                ->where('result', 4)
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
                ->groupBy('date')
                ->get(),
        ];

        $provincialData = [];
        foreach($hpCodesbyProvince as $key => $provinceData){
            $hpCodes = $provinceData->pluck('hp_code');
            $data = [
                'registered' => SuspectedCase::whereIn('hp_code', $hpCodes)->active()->count(),
                'registered_in_24_hrs' => SuspectedCase::whereIn('hp_code', $hpCodes)->active()->where('created_at', '>', Carbon::now()->subDay()->toDateString())->count(),
                'sample_collection' =>SampleCollection::whereIn('hp_code', $hpCodes)->active()->count(),
                'sample_collection_in_24_hrs' => SampleCollection::whereIn('hp_code', $hpCodes)->active()->where('created_at', '>', Carbon::now()->subDay()->toDateString())->count(),
                'lab_result_positive' => SampleCollection::whereIn('hp_code', $hpCodes)->where('result', 3)->active()->count(),
                'lab_result_positive_in_24_hrs' => SampleCollection::whereIn('hp_code', $hpCodes)->where('result', 3)->where('updated_at', '>', Carbon::now()->subDay()->toDateString())->active()->count(),
                'lab_result_negative' => SampleCollection::whereIn('hp_code', $hpCodes)->where('result', 4)->active()->count(),
                'lab_result_negative_in_24_hrs' => SampleCollection::whereIn('hp_code', $hpCodes)->where('result', 4)->where('updated_at', '>', Carbon::now()->subDay()->toDateString())->active()->count(),
            ];

            $provincialForecast = [
                'registered' => DB::table('women')
                    ->whereIn('hp_code', $hpCodes)
                    ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
                    ->groupBy('date')
                    ->get(),

                'sample_collection' => DB::table('ancs')
                    ->whereIn('hp_code', $hpCodes)
                    ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
                    ->groupBy('date')
                    ->get(),

                'lab_result_positive' => DB::table('ancs')
                    ->whereIn('hp_code', $hpCodes)
                    ->where('result', 3)
                    ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
                    ->groupBy('date')
                    ->get(),

                'lab_result_negative' => DB::table('ancs')
                    ->whereIn('hp_code', $hpCodes)
                    ->where('result', 4)
                    ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
                    ->groupBy('date')
                    ->get()
            ];

            $provincialData[] = [
                'province' => $key,
                'data' => $data,
                'forecast' => $provincialForecast
            ];
        }

        $responseData = [
            'total' => [
                'data' => $data,
                'forecast' => $forecast
            ],
            'provincialData' =>
                $provincialData
        ];
        return response()->json($responseData);
    }

    public function forMonitor(){
        $hpCodesbyProvince = Organization::get()->groupBy('province_id');
        $provincialData = [];
        foreach($hpCodesbyProvince as $key => $provinceData) {
            $hpCodes = $provinceData->pluck('hp_code');
            $provinceDataCalc = [
                'province' => province::where('id', $key)->first()->province_name,
                'sample_collection' => SampleCollection::whereIn('hp_code', $hpCodes)->active()->count(),
                'sample_collection_in_24_hrs' => SampleCollection::whereIn('hp_code', $hpCodes)->active()->where('created_at', '>', Carbon::now()->subDay()->toDateString())->count(),
                'lab_result_positive' => SampleCollection::whereIn('hp_code', $hpCodes)->where('result', 3)->active()->count(),
                'lab_result_positive_in_24_hrs' => SampleCollection::whereIn('hp_code', $hpCodes)->where('result', 3)->where('updated_at', '>', Carbon::now()->subDay()->toDateString())->active()->count(),
                'lab_result_negative' => SampleCollection::whereIn('hp_code', $hpCodes)->where('result', 4)->active()->count(),
                'lab_result_negative_in_24_hrs' => SampleCollection::whereIn('hp_code', $hpCodes)->where('result', 4)->where('updated_at', '>', Carbon::now()->subDay()->toDateString())->active()->count(),
            ];
            $provincialData[] = $provinceDataCalc;
        }
        $data =
            [
                'sample_collection' =>SampleCollection::active()->count(),
                'sample_collection_in_24_hrs' => SampleCollection::active()->where('created_at', '>', Carbon::now()->subDay()->toDateString())->count(),
                'lab_result_positive' => SampleCollection::where('result', 3)->active()->count(),
                'lab_result_positive_in_24_hrs' => SampleCollection::where('result', 3)->where('updated_at', '>', Carbon::now()->subDay()->toDateString())->active()->count(),
                'lab_result_negative' => SampleCollection::where('result', 4)->active()->count(),
                'lab_result_negative_in_24_hrs' => SampleCollection::where('result', 4)->where('updated_at', '>', Carbon::now()->subDay()->toDateString())->active()->count(),
                'provincial_data' => $provincialData
                ];

        return response()->json(
           $data
        );
    }
}
