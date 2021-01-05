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
    public function timeSeries(): \Illuminate\Http\JsonResponse
    {

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

        $rawForecast = DB::table('women')
            ->latest('women.created_at')
            ->join('ancs','women.token', '=', 'ancs.woman_token')
            ->select(DB::raw('DATE(women.created_at) as register_date'), DB::raw('DATE(ancs.created_at) as sample_created_at'), DB::raw('DATE(women.updated_at) as result_date') , 'ancs.result as result')
            ->get();

        $forecast = $rawForecast->map(function ($item){
            dd($item);
        });

//        $provincialData = [];
//        foreach($hpCodesbyProvince as $key => $provinceData){
//            $hpCodes = $provinceData->pluck('hp_code');
//            $data = [
//                'registered' => SuspectedCase::whereIn('hp_code', $hpCodes)->active()->count(),
//                'registered_in_24_hrs' => SuspectedCase::whereIn('hp_code', $hpCodes)->active()->where('created_at', '>', Carbon::now()->subDay()->toDateString())->count(),
//                'sample_collection' =>SampleCollection::whereIn('hp_code', $hpCodes)->active()->count(),
//                'sample_collection_in_24_hrs' => SampleCollection::whereIn('hp_code', $hpCodes)->active()->where('created_at', '>', Carbon::now()->subDay()->toDateString())->count(),
//                'lab_result_positive' => SampleCollection::whereIn('hp_code', $hpCodes)->where('result', 3)->active()->count(),
//                'lab_result_positive_in_24_hrs' => SampleCollection::whereIn('hp_code', $hpCodes)->where('result', 3)->where('updated_at', '>', Carbon::now()->subDay()->toDateString())->active()->count(),
//                'lab_result_negative' => SampleCollection::whereIn('hp_code', $hpCodes)->where('result', 4)->active()->count(),
//                'lab_result_negative_in_24_hrs' => SampleCollection::whereIn('hp_code', $hpCodes)->where('result', 4)->where('updated_at', '>', Carbon::now()->subDay()->toDateString())->active()->count(),
//            ];
//
//            $provincialForecast = [
//                'registered' => DB::table('women')
//                    ->whereIn('hp_code', $hpCodes)
//                    ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
//                    ->groupBy('date')
//                    ->get(),
//
//                'sample_collection' => DB::table('ancs')
//                    ->whereIn('hp_code', $hpCodes)
//                    ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
//                    ->groupBy('date')
//                    ->get(),
//
//                'lab_result_positive' => DB::table('ancs')
//                    ->whereIn('hp_code', $hpCodes)
//                    ->where('result', 3)
//                    ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
//                    ->groupBy('date')
//                    ->get(),
//
//                'lab_result_negative' => DB::table('ancs')
//                    ->whereIn('hp_code', $hpCodes)
//                    ->where('result', 4)
//                    ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
//                    ->groupBy('date')
//                    ->get()
//            ];
//
//            $provincialData[] = [
//                'province' => $key,
//                'data' => $data,
//                'forecast' => $provincialForecast
//            ];
//        }

        $table = collect($data)->values()->toArray();

        $table = array($table);
        $header = ['Registered', 'Registered in 24 hrs', 'Sample Collection',
            'Sample Collection in 24 hrs', 'Lab Result Positive',
            'Lab Result Positive in 24 hrs', 'Lab Result Negative',
            'Lab Result Negative in 24 hrs'
        ];

        array_unshift($table, $header);

        $responseData = [
                'data' => $table,
                'forecast' => $rawForecast
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

    public function gender(){
        $data = SuspectedCase::where('women.status', 1)
            ->join('ancs', 'women.token', '=', 'ancs.woman_token')
            ->where('ancs.result', '!=', 5)
            ->select('women.sex', 'ancs.result' , \DB::raw('count(*) as total'))
            ->groupBy('women.sex', 'ancs.result')
            ->get()->makeHidden(['formated_age_unit', 'formated_gender']);

        $table = collect($data)->map(function ($item) {
            $item->sex = $this->formatGender($item->sex);
            $item->result = $this->formatResult($item->result);
            return collect($item)->flatten();
        })->toArray();

        $header = ['Gender', 'Result', 'Total'];
        array_unshift($table, $header);

        return $table;
    }

    public function occupation(){
        $data = SuspectedCase::where('women.status', 1)
            ->join('ancs', 'women.token', '=', 'ancs.woman_token')
            ->where('ancs.result', '!=', 5)
            ->select('women.occupation', 'ancs.result' , \DB::raw('count(*) as total'))
            ->groupBy('women.occupation', 'ancs.result')
            ->get()->makeHidden(['formated_age_unit', 'formated_gender']);

        $table = collect($data)->map(function ($item) {
            $item->occupation = $this->formatOccupation($item->occupation);
            $item->result = $this->formatResult($item->result);
            return collect($item)->flatten();
        })->toArray();

        $header = ['Occupation', 'Result', 'Total'];
        array_unshift($table, $header);

        return $table;
    }

    private function formatOccupation($data){
        switch($data){
            case '1':
                return 'Front Line Healthworker';

            case '2':
                return 'Doctor';

            case '3':
                return 'Nurse';

            case '4':
                return 'Police / Army';

            case '5':
                return 'Business / Industry';

            case '6':
                return 'Teacher / Student ( Education )';

            case '7':
                return 'Civil Servant';

            case '8':
                return 'Journalist';

            case '9':
                return 'Agriculture';

            case '10':
                return 'Transport / Delivery';

            default:
                return 'Other';
        }
    }


    private function formatResult($value){
        switch($value){
            case '2':
                return 'Registered / Pending';
            case '3':
                return 'Positive';
            case '4':
                return 'Negative';
            case '9':
                return 'Received';
            default:
                return '';
        }
    }

    private function formatGender($value){

        switch($value){
            case '1':
                return 'Male';
            case '2':
                return 'Female';
            default:
                return 'Other';
        }
    }
}
