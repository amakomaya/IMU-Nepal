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
            $lab_received_collection = LabTest::where('checked_by', auth()->user()->token)->active()->get();

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
            'registered' => SuspectedCase::whereIn('hp_code', $hpCodes)->active()->count(),
            'registered_in_24_hrs' => SuspectedCase::whereIn('hp_code', $hpCodes)->active()->where('created_at', '>', Carbon::now()->subDay()->toDateString())->count(),
            'sample_collection' => SampleCollection::whereIn('hp_code', $hpCodes)->active()->count(),
            'sample_collection_in_24_hrs' => SampleCollection::whereIn('hp_code', $hpCodes)->active()->where('created_at', '>', Carbon::now()->subDay()->toDateString())->count(),
            'sample_received_in_lab' => SampleCollection::whereIn('hp_code', $hpCodes)->whereIn('result', [9, 3,4,5])->active()->count(),
            'sample_received_in_lab_in_24_hrs' => SampleCollection::whereIn('hp_code', $hpCodes)->whereIn('result', [9, 3,4,5])->where('updated_at', '>', Carbon::now()->subDay()->toDateString())->active()->count(),
            'lab_result_positive' => SampleCollection::whereIn('hp_code', $hpCodes)->where('result', 3)->active()->count(),
            'lab_result_positive_in_24_hrs' => SampleCollection::whereIn('hp_code', $hpCodes)->where('result', 3)->where('updated_at', '>', Carbon::now()->subDay()->toDateString())->active()->count(),
            'lab_result_negative' => SampleCollection::whereIn('hp_code', $hpCodes)->where('result', 4)->active()->count(),
            'lab_result_negative_in_24_hrs' => SampleCollection::whereIn('hp_code', $hpCodes)->where('result', 4)->where('updated_at', '>', Carbon::now()->subDay()->toDateString())->active()->count(),

            'in_lab_received' => $lab_received_collection->count(),
            'in_lab_received_in_24_hrs' => $dashboardLabCases->sum('in_lab_received_in_24_hrs'),
            'in_lab_received_positive' => $dashboardLabCases->sum('in_lab_received_positive'),
            'in_lab_received_positive_in_24_hrs' => $dashboardLabCases->sum('in_lab_received_positive_in_24_hrs'),
            'in_lab_received_negative' => $dashboardLabCases->sum('in_lab_received_negative'),
            'in_lab_received_negative_in_24_hrs' => $dashboardLabCases->sum('in_lab_received_negative_in_24_hrs'),
        ];
        return response()->json($data);
    }
}