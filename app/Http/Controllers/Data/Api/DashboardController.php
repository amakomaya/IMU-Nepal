<?php

namespace App\Http\Controllers\Data\Api;

use App\Helpers\GetHealthpostCodes;
use App\Models\LabTest;
use App\Models\Organization;
use App\Models\SampleCollection;
use App\Models\SuspectedCase;
use App\Reports\FilterRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
        $dashboardLabCases = collect();
        $lab_received_collection = collect();
        if (auth()->user()->role == 'healthworker'){
            $lab_received_collection = Cache::remember('lab_received_collection-'.auth()->user()->token, 60, function () {
                return LabTest::where('checked_by', auth()->user()->token)->active()->get();
            });

            $dashboardLabCases = $lab_received_collection->map(function ($value){
                $data = [];
                $data['token'] = $value['token'];

                $data['in_lab_received_in_24_hrs'] = $value['created_at'] >= Carbon::now()->subDay()->toDateTimeString() ? 1 : 0 ;

                $data['in_lab_received_in_24_hrs'] = $value['created_at'] >= Carbon::now()->subDay()->toDateTimeString() ? 1 : 0 ;

                $data['in_lab_received_positive'] = 0;
                $data['in_lab_received_negative'] = 0;
                $data['in_lab_received_positive_in_24_hrs'] = 0;
                $data['in_lab_received_negative_in_24_hrs'] = 0;
                switch ($value->sample_test_result){
                    case 3:
                        $data['in_lab_received_positive']++;
                        try {
                            if($value->updated_at >= Carbon::now()->subDay()->toDateTimeString()) {
                                $data['in_lab_received_positive_in_24_hrs']++;
                            }
                        }catch (\Exception $e){}
                        break;
                    case 4:
                        $data['in_lab_received_negative']++;
                        try {
                            if($value->updated_at >= Carbon::now()->subDay()->toDateTimeString()) {
                                $data['in_lab_received_negative_in_24_hrs']++;
                            }
                        }catch (\Exception $e){}
                        break;
                }
                return $data;
            });

        }

        $data = [
            'registered' => Cache::remember('registered-'.auth()->user()->token, 60*60, function () use ($hpCodes) {
                return SuspectedCase::whereIn('hp_code', $hpCodes)->active()->count();
                    }),
            'registered_in_24_hrs' => Cache::remember('registered_in_24_hrs-'.auth()->user()->token, 60*60, function () use ($hpCodes) {
                return SuspectedCase::whereIn('hp_code', $hpCodes)->active()->where('created_at', '>', Carbon::now()->subDay()->toDateString())->count();
            }),
            'sample_collection' => Cache::remember('sample_collection-'.auth()->user()->token, 60*60, function () use ($hpCodes) {
                return SampleCollection::whereIn('hp_code', $hpCodes)->active()->count();
            }),
            'sample_collection_in_24_hrs' => Cache::remember('sample_collection_in_24_hrs-'.auth()->user()->token, 60*60, function () use ($hpCodes) {
                return SampleCollection::whereIn('hp_code', $hpCodes)->active()->where('created_at', '>', Carbon::now()->subDay()->toDateString())->count();
            }),
            'sample_received_in_lab' => Cache::remember('sample_received_in_lab-'.auth()->user()->token, 60*60, function () use ($hpCodes) {
                return SampleCollection::whereIn('hp_code', $hpCodes)->whereIn('result', [9, 3,4,5])->active()->count();
            }),
            'sample_received_in_lab_in_24_hrs' => Cache::remember('sample_received_in_lab_in_24_hrs-'.auth()->user()->token, 60*60, function () use ($hpCodes) {
                return SampleCollection::whereIn('hp_code', $hpCodes)->whereIn('result', [9, 3,4,5])->where('updated_at', '>', Carbon::now()->subDay()->toDateString())->active()->count();
            }),
            'lab_result_positive' => Cache::remember('lab_result_positive-'.auth()->user()->token, 60*60, function () use ($hpCodes) {
                return SampleCollection::whereIn('hp_code', $hpCodes)->where('result', 3)->active()->count();
            }),
            'lab_result_positive_in_24_hrs' => Cache::remember('lab_result_positive_in_24_hrs-'.auth()->user()->token, 60*60, function () use ($hpCodes) {
                return SampleCollection::whereIn('hp_code', $hpCodes)->where('result', 3)->where('updated_at', '>', Carbon::now()->subDay()->toDateString())->active()->count();
            }),
            'lab_result_negative' => Cache::remember('lab_result_negative-'.auth()->user()->token, 60*60, function () use ($hpCodes) {
                return SampleCollection::whereIn('hp_code', $hpCodes)->where('result', 4)->active()->count();
            }),
            'lab_result_negative_in_24_hrs' => Cache::remember('lab_result_negative_in_24_hrs-'.auth()->user()->token, 60*60, function () use ($hpCodes) {
                return SampleCollection::whereIn('hp_code', $hpCodes)->where('result', 4)->where('updated_at', '>', Carbon::now()->subDay()->toDateString())->active()->count();
            }),

            'in_lab_received' => $lab_received_collection->count(),
            'in_lab_received_in_24_hrs' => $dashboardLabCases->sum('in_lab_received_in_24_hrs'),
            'in_lab_received_positive' => $dashboardLabCases->sum('in_lab_received_positive'),
            'in_lab_received_positive_in_24_hrs' => $dashboardLabCases->sum('in_lab_received_positive_in_24_hrs'),
            'in_lab_received_negative' => $dashboardLabCases->sum('in_lab_received_negative'),
            'in_lab_received_negative_in_24_hrs' => $dashboardLabCases->sum('in_lab_received_negative_in_24_hrs'),
            // time expiration in UMT add 5:45 to nepali time, sub 1 hrs to get updated at => 285
            'cache_created_at' => Carbon::parse(\DB::table('cache')->where('key', 'laravelregistered-'.auth()->user()->token)->first()->expiration)->addMinutes(285)->format('Y-m-d H:i:s'),
            'user_token' => auth()->user()->token
        ];

        return response()->json($data);
    }
}