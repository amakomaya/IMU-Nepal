<?php

namespace App\Http\Controllers\Data\Api;

use App\Models\Organization;
use App\Models\province;
use App\Models\SampleCollection;
use App\Models\SuspectedCase;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
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

        $rawForecast = DB::table('suspected_cases')
            ->latest('suspected_cases.created_at')
            ->join('sample_collection','suspected_cases.token', '=', 'sample_collection.woman_token')
            ->select(DB::raw('DATE(suspected_cases.created_at) as register_date'), DB::raw('DATE(sample_collection.created_at) as sample_created_at'), DB::raw('DATE(suspected_cases.updated_at) as result_date') , 'sample_collection.result as result')
            ->get();

        $forecast = $rawForecast->map(function ($item){
            dd($item);
        });

//        $provincialData = [];
//        foreach($hpCodesbyProvince as $key => $provinceData){
//            $hpCodes = $provinceData->pluck('org_code');
//            $data = [
//                'registered' => SuspectedCase::whereIn('org_code', $hpCodes)->active()->count(),
//                'registered_in_24_hrs' => SuspectedCase::whereIn('org_code', $hpCodes)->active()->where('created_at', '>', Carbon::now()->subDay()->toDateString())->count(),
//                'sample_collection' =>SampleCollection::whereIn('org_code', $hpCodes)->active()->count(),
//                'sample_collection_in_24_hrs' => SampleCollection::whereIn('org_code', $hpCodes)->active()->where('created_at', '>', Carbon::now()->subDay()->toDateString())->count(),
//                'lab_result_positive' => SampleCollection::whereIn('org_code', $hpCodes)->where('result', 3)->active()->count(),
//                'lab_result_positive_in_24_hrs' => SampleCollection::whereIn('org_code', $hpCodes)->where('result', 3)->where('updated_at', '>', Carbon::now()->subDay()->toDateString())->active()->count(),
//                'lab_result_negative' => SampleCollection::whereIn('org_code', $hpCodes)->where('result', 4)->active()->count(),
//                'lab_result_negative_in_24_hrs' => SampleCollection::whereIn('org_code', $hpCodes)->where('result', 4)->where('updated_at', '>', Carbon::now()->subDay()->toDateString())->active()->count(),
//            ];
//
//            $provincialForecast = [
//                'registered' => DB::table('suspected_cases')
//                    ->whereIn('org_code', $hpCodes)
//                    ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
//                    ->groupBy('date')
//                    ->get(),
//
//                'sample_collection' => DB::table('sample_collection')
//                    ->whereIn('org_code', $hpCodes)
//                    ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
//                    ->groupBy('date')
//                    ->get(),
//
//                'lab_result_positive' => DB::table('sample_collection')
//                    ->whereIn('org_code', $hpCodes)
//                    ->where('result', 3)
//                    ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
//                    ->groupBy('date')
//                    ->get(),
//
//                'lab_result_negative' => DB::table('sample_collection')
//                    ->whereIn('org_code', $hpCodes)
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
            $hpCodes = $provinceData->pluck('org_code');
            $provinceDataCalc = [
                'province' => province::where('id', $key)->first()->province_name,
                'sample_collection' => SampleCollection::whereIn('org_code', $hpCodes)->active()->count(),
                'sample_collection_in_24_hrs' => SampleCollection::whereIn('org_code', $hpCodes)->active()->where('created_at', '>', Carbon::now()->subDay()->toDateString())->count(),
                'lab_result_positive' => SampleCollection::whereIn('org_code', $hpCodes)->where('result', 3)->active()->count(),
                'lab_result_positive_in_24_hrs' => SampleCollection::whereIn('org_code', $hpCodes)->where('result', 3)->where('updated_at', '>', Carbon::now()->subDay()->toDateString())->active()->count(),
                'lab_result_negative' => SampleCollection::whereIn('org_code', $hpCodes)->where('result', 4)->active()->count(),
                'lab_result_negative_in_24_hrs' => SampleCollection::whereIn('org_code', $hpCodes)->where('result', 4)->where('updated_at', '>', Carbon::now()->subDay()->toDateString())->active()->count(),
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
        $data = SuspectedCase::where('suspected_cases.status', 1)
            ->join('sample_collection', 'suspected_cases.token', '=', 'sample_collection.woman_token')
            ->where('sample_collection.result', '!=', 5)
            ->select('suspected_cases.sex', 'sample_collection.result' , \DB::raw('count(*) as total'))
            ->groupBy('suspected_cases.sex', 'sample_collection.result')
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
        $data = SuspectedCase::where('suspected_cases.status', 1)
            ->join('sample_collection', 'suspected_cases.token', '=', 'sample_collection.woman_token')
            ->where('sample_collection.result', '!=', 5)
            ->select('suspected_cases.occupation', 'sample_collection.result' , \DB::raw('count(*) as total'))
            ->groupBy('suspected_cases.occupation', 'sample_collection.result')
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

    public function antigen(){

        $data = Cache::remember('analysis-report', 60 * 60, function () {
            return \DB::table('suspected_cases')->where('suspected_cases.status', 1)
                ->join('sample_collection', 'suspected_cases.token', '=', 'sample_collection.woman_token')
                ->join('organizations', 'suspected_cases.org_code', '=', 'organizations.org_code')
                ->whereIn('sample_collection.result', [3,4])
                ->select('sample_collection.result as antigen_result', 'sample_collection.service_for', 'organizations.province_id as province', DB::raw('count(*) as total'))
                ->groupBy(['antigen_result','province', 'service_for'])
                ->get();
        });

        $table = $data->map(function ($item) {
            $item->antigen_result = $this->formatResult($item->antigen_result);
            $item->service_for = $this->checkServiceFor($item->service_for);
            return collect($item)->flatten();
        })->toArray();

        $header = ['Result','Test Type', 'Province', 'Total'];
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

    private function checkServiceFor($service_for): string
    {
        switch($service_for){
            case '2':
                return 'Rapid Antigen Test';
            default:
                return 'SARS-CoV-2 RNA Test';
        }
    }
}
