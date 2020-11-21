<?php

namespace App\Http\Controllers\Data\Api;

use App\Helpers\GetHealthpostCodes;
use App\Models\Anc;
use App\Models\LabTest;
use App\Models\Woman;
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

        $user = auth()->user();

        $lab_received_collection = LabTest::where('checked_by', $user->token)->active()->get();


        $cases = Woman::whereIn('hp_code', $hpCodes)->active()->with('ancs')->get();

        $dashboardCases = $cases->map(function ($value){
            $data = [];
            $data['token'] = $value['token'];
            $data['case_created_at_in_24_hrs'] = $value['created_at'] >= Carbon::now()->subDay()->toDateTimeString() ? 1 : 0 ;

            $sample_collection = $value->ancs;
            $data['sample_collection_count'] = $sample_collection->count();

            $filtered_sample = $sample_collection->map(function ($sample){
                $sample_data = [];
                $sample_data['sample_collection_in_24_hrs'] = 0;

                $sample_data['sample_received'] = 0;
                $sample_data['sample_received_in_24_hrs'] = 0;

                $sample_data['lab_result_positive'] = 0;
                $sample_data['lab_result_positive_in_24_hrs'] = 0;

                $sample_data['lab_result_negative'] = 0;
                $sample_data['lab_result_negative_in_24_hrs'] = 0;
                if($sample['created_at'] >= Carbon::now()->subDay()->toDateTimeString()){
                    $sample_data['sample_collection_in_24_hrs']++;
                }
                if ($sample->result != 2){
                    $sample_data['sample_received']++;
                    switch ($sample->result){
                        case '3':
                            $sample_data['lab_result_positive']++;
                            try {
                                if($sample->labreport->updated_at >= Carbon::now()->subDay()->toDateTimeString()) {
                                    $sample_data['lab_result_positive_in_24_hrs']++;
                                }
                            }catch (\Exception $e){}
                            break;
                        case '4':
                            $sample_data['lab_result_negative']++;
                            try {
                                if($sample->labreport->updated_at >= Carbon::now()->subDay()->toDateTimeString()) {
                                    $sample_data['lab_result_negative_in_24_hrs']++;
                                }
                            }catch (\Exception $e){}
                            break;
                    }

                    try {
                        if($sample->labreport->created_at >= Carbon::now()->subDay()->toDateTimeString()) {
                            $sample_data['sample_received_in_24_hrs']++;
                        }
                    }catch (\Exception $e){}
                }
                return $sample_data;
            })->values();

            $data['sample_collection_in_24_hrs'] = $filtered_sample->sum('sample_collection_in_24_hrs');

            $data['sample_received'] = $filtered_sample->sum('sample_received');
            $data['sample_received_in_24_hrs'] = $filtered_sample->sum('sample_received_in_24_hrs');

            $data['lab_result_positive'] = $filtered_sample->sum('lab_result_positive');
            $data['lab_result_positive_in_24_hrs'] = $filtered_sample->sum('lab_result_positive_in_24_hrs');

            $data['lab_result_negative'] = $filtered_sample->sum('lab_result_negative');
            $data['lab_result_negative_in_24_hrs'] = $filtered_sample->sum('lab_result_negative_in_24_hrs');

            return $data;
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
                case '3':
                    $data['in_lab_received_positive']++;
                    try {
                        if($value->updated_at >= Carbon::now()->subDay()->toDateTimeString()) {
                            $data['in_lab_received_positive_in_24_hrs']++;
                        }
                    }catch (\Exception $e){}
                    break;
                case '4':
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

        $data = [
            'registered' => $dashboardCases->count(),
            'registered_in_24_hrs' => $dashboardCases->sum('case_created_at_in_24_hrs'),
            'sample_collection' => $dashboardCases->sum('sample_collection_count'),
            'sample_collection_in_24_hrs' => $dashboardCases->sum('sample_collection_in_24_hrs'),
            'sample_received_in_lab' => $dashboardCases->sum('sample_received'),
            'sample_received_in_lab_in_24_hrs' => $dashboardCases->sum('sample_received_in_24_hrs'),
            'lab_result_positive' => $dashboardCases->sum('lab_result_positive'),
            'lab_result_positive_in_24_hrs' => $dashboardCases->sum('lab_result_positive_in_24_hrs'),
            'lab_result_negative' => $dashboardCases->sum('lab_result_negative'),
            'lab_result_negative_in_24_hrs' => $dashboardCases->sum('lab_result_negative_in_24_hrs'),

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