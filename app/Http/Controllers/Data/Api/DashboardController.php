<?php

namespace App\Http\Controllers\Data\Api;

use App\Helpers\GetHealthpostCodes;
use App\Models\LabTest;
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

        if (auth()->user()->role == 'healthworker' || auth()->user()->role == 'healthpost' ){
            $in_lab_received = Cache::remember('in_lab_received-'.auth()->user()->token, 60*60, function () use ($hpCodes) {
                return LabTest::whereIn('hp_code', $hpCodes)->get()->count();
            });

            $in_lab_received_in_24_hrs = Cache::remember('in_lab_received_in_24_hrs-'.auth()->user()->token, 60*60, function () use ($hpCodes) {
                return LabTest::whereIn('hp_code', $hpCodes)->where('created_at', '>', Carbon::now()->subDay()->toDateString())->get()->count();
            });

            $in_lab_received_positive = Cache::remember('in_lab_received_positive-'.auth()->user()->token, 60*60, function () use ($hpCodes) {
                return LabTest::whereIn('hp_code', $hpCodes)->where('sample_test_result', '3')->get()->count();
            });
            $in_lab_received_positive_in_24_hrs = Cache::remember('in_lab_received_positive_in_24_hrs-'.auth()->user()->token, 60*60, function () use ($hpCodes) {
                return LabTest::whereIn('hp_code', $hpCodes)->where('sample_test_result', '3')->where('updated_at', '>', Carbon::now()->subDay()->toDateString())->get()->count();
            });
            $in_lab_received_negative = Cache::remember('in_lab_received_negative-'.auth()->user()->token, 60*60, function () use ($hpCodes) {
                return LabTest::whereIn('hp_code', $hpCodes)->where('sample_test_result', '4')->get()->count();
            });
            $in_lab_received_negative_in_24_hrs = Cache::remember('in_lab_received_negative_in_24_hrs-'.auth()->user()->token, 60*60, function () use ($hpCodes) {
                return LabTest::whereIn('hp_code', $hpCodes)->where('sample_test_result', '4')->where('updated_at', '>', Carbon::now()->subDay()->toDateString())->get()->count();
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

            'in_lab_received' => $in_lab_received ?? 0,
            'in_lab_received_in_24_hrs' => $in_lab_received_in_24_hrs ?? 0,
            'in_lab_received_positive' => $in_lab_received_positive ?? 0,
            'in_lab_received_positive_in_24_hrs' => $in_lab_received_positive_in_24_hrs ?? 0,
            'in_lab_received_negative' => $in_lab_received_negative ?? 0,
            'in_lab_received_negative_in_24_hrs' => $in_lab_received_negative_in_24_hrs ?? 0,
            // time expiration in UMT add 5:45 to nepali time, sub 1 hrs to get updated at => 285
            'cache_created_at' => Carbon::parse(\DB::table('cache')->where('key', 'laravelregistered-'.auth()->user()->token)->first()->expiration)->addMinutes(285)->format('Y-m-d H:i:s'),
            'user_token' => auth()->user()->token
        ];

        return response()->json($data);
    }
}