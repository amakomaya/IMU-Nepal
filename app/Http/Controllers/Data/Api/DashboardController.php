<?php

namespace App\Http\Controllers\Data\Api;

use App\CovidImmunization;
use App\Helpers\GetHealthpostCodes;
use App\Models\HealthProfessional;
use App\Models\LabTest;
use App\Models\SampleCollection;
use App\Models\SuspectedCase;
use App\Models\VaccinationRecord;
use App\Reports\FilterRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use DB;

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

        // check at 13:300pm
//        $check_at_1330 = Carbon::parse('today 1:30pm');
//
//        if($check_at_1330 < Carbon::now()){
//            // 1 pm today + current
//            $date_from = Carbon::parse('today 1:30pm');
//            $date_to = Carbon::now();
//        }else{
//            // 1pm yesterday + current
//            $date_from = Carbon::parse('yesterday 1:30pm');
//            $date_to = Carbon::now();
//        }

        $date_from = Carbon::today()->startOfDay();
        $date_to = Carbon::now();
        if (auth()->user()->role == 'healthworker' || auth()->user()->role == 'healthpost') {
            $in_lab_received = Cache::remember('in_lab_received-' . auth()->user()->token, 60 * 60, function() use ($hpCodes) {
                $current_data = LabTest::whereIn('hp_code', $hpCodes)->get()->count();
                $dump_data = DB::connection('mysqldump')->table('lab_tests')->whereIn('hp_code', $hpCodes)->get()->count();
                return $current_data + $dump_data;
            });

            $in_lab_received_in_24_hrs = Cache::remember('in_lab_received_in_24_hrs-' . auth()->user()->token, 60 * 60, function () use ($hpCodes) {
                return LabTest::whereIn('hp_code', $hpCodes)->whereDate('created_at', Carbon::today())->get()->count();
            });

            $in_lab_received_positive = Cache::remember('in_lab_received_positive-' . auth()->user()->token, 60 * 60, function () use ($hpCodes) {
                $current_data = LabTest::whereIn('hp_code', $hpCodes)->where('sample_test_result', '3')->get()->count();
                $dump_data = DB::connection('mysqldump')->table('lab_tests')->whereIn('hp_code', $hpCodes)->where('sample_test_result', '3')->get()->count();
                return $current_data + $dump_data;
            });

            $in_lab_received_positive_in_24_hrs = Cache::remember('in_lab_received_positive_in_24_hrs-' . auth()->user()->token, 60 * 60, function () use ($hpCodes) {
                return LabTest::whereIn('hp_code', $hpCodes)->whereDate('sample_test_result', '3')->where('updated_at', Carbon::today())->get()->count();
            });

            $in_lab_received_negative = Cache::remember('in_lab_received_negative-' . auth()->user()->token, 60 * 60, function () use ($hpCodes) {
                $current_data = LabTest::whereIn('hp_code', $hpCodes)->where('sample_test_result', '4')->get()->count();
                $dump_data = DB::connection('mysqldump')->table('lab_tests')->whereIn('hp_code', $hpCodes)->where('sample_test_result', '4')->get()->count();
                return $current_data + $dump_data;
            });

            $in_lab_received_negative_in_24_hrs = Cache::remember('in_lab_received_negative_in_24_hrs-' . auth()->user()->token, 60 * 60, function () use ($hpCodes) {
                return LabTest::whereIn('hp_code', $hpCodes)->whereDate('sample_test_result', '4')->where('updated_at', Carbon::today())->get()->count();
            });
        }

        $date_five_days = Carbon::now()->subDays(5);

        $inside_data_all = SampleCollection::leftjoin('healthposts', 'ancs.hp_code', '=', 'healthposts.hp_code')
            ->whereIn('ancs.hp_code', $hpCodes)
            ->whereIn('ancs.result', [9, 4])
            ->whereIn('healthposts.hospital_type', [2, 3])
            // ->leftjoin('lab_tests', function($q) {
            //     $q->on('ancs.token', '=', 'lab_tests.sample_token');
            //     $q->on('ancs.hp_code', '=', 'lab_tests.hp_code');
            // })
            ->whereBetween(\DB::raw('DATE(ancs.updated_at)'), [$date_five_days, $date_to])
            ->select('ancs.*', DB::Raw('DATE(ancs.updated_at) as updated_at_date'))
            ->orderBy('updated_at_date', 'desc')
            ->get()
            ->groupBy('updated_at_date');

        $inside_data = [];
        foreach($inside_data_all as $key => $inside_datum) {
            $healthpost_name = $inside_datum[0]->name;
            $inside_data[$key]['inside_pcr_count'] = $inside_data[$key]['inside_antigen_count'] = $inside_data[$key]['inside_pcr_postive_cases_count'] = $inside_data[$key]['inside_pcr_negative_cases_count'] = $inside_data[$key]['inside_antigen_postive_cases_count'] = $inside_data[$key]['inside_antigen_negative_cases_count'] = 0;
            foreach($inside_datum as $solo) {
                if($solo->service_type == '1'){
                    $inside_data[$key]['inside_pcr_count'] += 1;
                    if($solo->result == 3){
                        $inside_data[$key]['inside_pcr_postive_cases_count'] += 1;
                    }
                    if($solo->result == 4){
                        $inside_data[$key]['inside_pcr_negative_cases_count'] += 1;
                    }
                }
                if($solo->service_type == '2'){
                    $inside_data[$key]['inside_antigen_count'] += 1;
                    if($solo->result == 3){
                        $inside_data[$key]['inside_antigen_postive_cases_count'] += 1;
                    }
                    if($solo->result == 4){
                        $inside_data[$key]['inside_antigen_negative_cases_count'] += 1;
                    }
                }
                
            }
        }

        $outside_data_all = LabTest::leftjoin('healthposts', 'lab_tests.hp_code', '=', 'healthposts.hp_code')
            ->leftjoin('ancs', 'lab_tests.sample_token', '=', 'ancs.token')
            ->whereIn('lab_tests.hp_code', $hpCodes)
            ->whereIn('lab_tests.sample_test_result', [9, 4])
            ->whereBetween(\DB::raw('DATE(ancs.updated_at)'), [$date_five_days, $date_to])
            ->select('lab_tests.*', 'ancs.service_type as ancs_service_type', DB::Raw('DATE(lab_tests.updated_at) as updated_at_date'))
            ->orderBy('updated_at_date', 'desc')
            ->get()
            ->groupBy('updated_at_date');

        $outside_data = [];
        foreach($outside_data_all as $key => $report) {
            $outside_data[$key]['outside_pcr_postive_cases_count'] = $outside_data[$key]['outside_pcr_negative_cases_count'] = $outside_data[$key]['outside_antigen_postive_cases_count'] = $outside_data[$key]['outside_antigen_negative_cases_count'] = $outside_data[$key]['outside_pcr_count'] = $outside_data[$key]['outside_antigen_count'] = 0;
            foreach($report as $solo) {
                if($solo->ancs_service_type == '1'){
                    $outside_data[$key]['outside_pcr_count'] += 1;
                    if($solo->sample_test_result == 3){
                        $outside_data[$key]['outside_pcr_postive_cases_count'] += 1;
                    }
                    if($solo->sample_test_result == 4){
                        $outside_data[$key]['outside_pcr_negative_cases_count'] += 1;
                    }
                }
                if($solo->ancs_service_type == '2'){
                    $outside_data[$key]['outside_antigen_count'] += 1;
                    if($solo->sample_test_result == 3){
                        $outside_data[$key]['outside_antigen_postive_cases_count'] += 1;
                    }
                    if($solo->sample_test_result == 4){
                        $outside_data[$key]['outside_antigen_negative_cases_count'] += 1;
                    }
                }
            }
        }

        $all_data = array_merge_recursive($inside_data,$outside_data);
        krsort($all_data);

        $data = [
            'registered' => Cache::remember('registered-' . auth()->user()->token, 60 * 60, function () use ($hpCodes) {
                $current_data = SuspectedCase::whereIn('hp_code', $hpCodes)->active()->count();
                $dump_data = DB::connection('mysqldump')->table('women')->whereIn('hp_code', $hpCodes)->where('status', 1)->count();
                return $current_data + $dump_data;
            }),
            'registered_in_24_hrs' => Cache::remember('registered_in_24_hrs-' . auth()->user()->token, 60 * 60, function () use ($date_to, $date_from, $hpCodes) {
                return SuspectedCase::whereIn('hp_code', $hpCodes)->active()
//                    ->whereDate('created_at', Carbon::today())
                      ->whereBetween('created_at', array($date_from->toDateTimeString(), $date_to->toDateTimeString()) )
                    ->count();
            }),
            'sample_collection' => Cache::remember('sample_collection-' . auth()->user()->token, 60 * 60, function () use ($hpCodes) {
                $current_data = SampleCollection::whereIn('hp_code', $hpCodes)->active()->count();
                $dump_data = DB::connection('mysqldump')->table('ancs')->whereIn('hp_code', $hpCodes)->where('status', 1)->count();
                return $current_data + $dump_data;
            }),
            'sample_collection_antigen' => Cache::remember('sample_collection_antigen-' . auth()->user()->token, 60 * 60, function () use ($hpCodes) {
                $current_data = SampleCollection::whereIn('hp_code', $hpCodes)->where('service_for', '2')->active()->count();
                $dump_data = DB::connection('mysqldump')->table('ancs')->whereIn('hp_code', $hpCodes)->where('service_for', '2')->where('status', 1)->count();
                return $current_data + $dump_data;
            }),
            'sample_collection_in_24_hrs' => Cache::remember('sample_collection_in_24_hrs-' . auth()->user()->token, 60 * 60, function () use ($date_to, $date_from, $hpCodes) {
                return SampleCollection::whereIn('hp_code', $hpCodes)->active()
//                    ->whereDate('created_at', Carbon::today())
                    ->whereBetween('created_at', array($date_from->toDateTimeString(), $date_to->toDateTimeString()) )
                    ->count();
            }),
            'sample_collection_in_24_hrs_antigen' => Cache::remember('sample_collection_in_24_hrs_antigen-' . auth()->user()->token, 60 * 60, function () use ($date_to, $date_from, $hpCodes) {
                return SampleCollection::whereIn('hp_code', $hpCodes)->where('service_for', '2')->active()
//                    ->whereDate('created_at', Carbon::today())
                    ->whereBetween('created_at', array($date_from->toDateTimeString(), $date_to->toDateTimeString()) )
                    ->count();
            }),
            'sample_received_in_lab' => Cache::remember('sample_received_in_lab-' . auth()->user()->token, 60 * 60, function () use ($hpCodes) {
                $current_data = SampleCollection::whereIn('hp_code', $hpCodes)->whereIn('result', [9, 3, 4, 5])->active()->count();
                $dump_data = DB::connection('mysqldump')->table('ancs')->whereIn('hp_code', $hpCodes)->whereIn('result', [9, 3, 4, 5])->where('status', 1)->count();
                return $current_data + $dump_data;
            }),
            'sample_received_in_lab_antigen' => Cache::remember('sample_received_in_lab_antigen-' . auth()->user()->token, 60 * 60, function () use ($hpCodes) {
                $current_data = SampleCollection::whereIn('hp_code', $hpCodes)->where('service_for', '2')->whereIn('result', [9, 3, 4, 5])->active()->count();
                $dump_data = DB::connection('mysqldump')->table('ancs')->whereIn('hp_code', $hpCodes)->where('service_for', '2')->whereIn('result', [9, 3, 4, 5])->where('status', 1)->count();
                return $current_data + $dump_data;
            }),
            'sample_received_in_lab_in_24_hrs' => Cache::remember('sample_received_in_lab_in_24_hrs-' . auth()->user()->token, 60 * 60, function () use ($date_to, $date_from, $hpCodes) {
                return SampleCollection::whereIn('hp_code', $hpCodes)->whereIn('result', [9, 3, 4, 5])
//                    ->whereDate('updated_at', Carbon::today())
                    ->whereBetween('created_at', array($date_from->toDateTimeString(), $date_to->toDateTimeString()) )
                    ->active()->count();
            }),
            'sample_received_in_lab_in_24_hrs_antigen' => Cache::remember('sample_received_in_lab_in_24_hrs_antigen-' . auth()->user()->token, 60 * 60, function () use ($date_to, $date_from, $hpCodes) {
                return SampleCollection::whereIn('hp_code', $hpCodes)->where('service_for', '2')
                    ->whereIn('result', [9, 3, 4, 5])
//                    ->whereDate('updated_at', Carbon::today())
                    ->whereBetween('created_at', array($date_from->toDateTimeString(), $date_to->toDateTimeString()) )
                    ->active()->count();
            }),
            'lab_result_positive' => Cache::remember('lab_result_positive-' . auth()->user()->token, 60 * 60, function () use ($hpCodes) {
                $current_data = SampleCollection::whereIn('hp_code', $hpCodes)->where('result', 3)->active()->count();
                $dump_data = DB::connection('mysqldump')->table('ancs')->whereIn('hp_code', $hpCodes)->where('result', 3)->where('status', 1)->count();
                return $current_data + $dump_data;
            }),
            'lab_result_positive_antigen' => Cache::remember('lab_result_positive_antigen-' . auth()->user()->token, 60 * 60, function () use ($hpCodes) {
                $current_data = SampleCollection::whereIn('hp_code', $hpCodes)->where('service_for', '2')->where('result', 3)->active()->count();
                $dump_data = DB::connection('mysqldump')->table('ancs')->whereIn('hp_code', $hpCodes)->where('service_for', '2')->where('result', 3)->where('status', 1)->count();
                return $current_data + $dump_data;
            }),
            'lab_result_positive_in_24_hrs' => Cache::remember('lab_result_positive_in_24_hrs-' . auth()->user()->token, 60 * 60, function () use ($date_to, $date_from, $hpCodes) {
                return SampleCollection::whereIn('hp_code', $hpCodes)->where('result', 3)
//                    ->whereDate('updated_at', Carbon::today())
                    ->whereBetween('updated_at', array($date_from->toDateTimeString(), $date_to->toDateTimeString()) )
                    ->active()->count();
            }),
            'lab_result_positive_in_24_hrs_antigen' => Cache::remember('lab_result_positive_in_24_hrs_antigen-' . auth()->user()->token, 60 * 60, function () use ($date_to, $date_from, $hpCodes) {
                return SampleCollection::whereIn('hp_code', $hpCodes)->where('service_for', '2')
                    ->where('result', 3)
//                    ->whereDate('updated_at', Carbon::today())
                    ->whereBetween('updated_at', array($date_from->toDateTimeString(), $date_to->toDateTimeString()))
                    ->active()->count();
            }),
            'lab_result_negative' => Cache::remember('lab_result_negative-' . auth()->user()->token, 60 * 60, function () use ($hpCodes) {
                $current_data = SampleCollection::whereIn('hp_code', $hpCodes)->where('result', 4)->active()->count();
                $dump_data = DB::connection('mysqldump')->table('ancs')->whereIn('hp_code', $hpCodes)->where('result', 4)->where('status', 1)->count();
                return $current_data + $dump_data;
            }),
            'lab_result_negative_antigen' => Cache::remember('lab_result_negative_antigen-' . auth()->user()->token, 60 * 60, function () use ($hpCodes) {
                $current_data = SampleCollection::whereIn('hp_code', $hpCodes)->where('service_for', '2')->where('result', 4)->active()->count();
                $dump_data = DB::connection('mysqldump')->table('ancs')->whereIn('hp_code', $hpCodes)->where('service_for', '2')->where('result', 4)->where('status', 1)->count();
                return $current_data + $dump_data;
            }),
            'lab_result_negative_in_24_hrs' => Cache::remember('lab_result_negative_in_24_hrs-' . auth()->user()->token, 60 * 60, function () use ($date_to, $date_from, $hpCodes) {
                return SampleCollection::whereIn('hp_code', $hpCodes)->where('result', 4)
//                    ->whereDate('updated_at', Carbon::today())
                    ->whereBetween('updated_at', array($date_from->toDateTimeString(), $date_to->toDateTimeString()) )
                    ->active()->count();
            }),
            'lab_result_negative_in_24_hrs_antigen' => Cache::remember('lab_result_negative_in_24_hrs_antigen-' . auth()->user()->token, 60 * 60, function () use ($date_to, $date_from, $hpCodes) {
                return SampleCollection::whereIn('hp_code', $hpCodes)->where('service_for', '2')
                    ->where('result', 4)
//                    ->whereDate('updated_at', Carbon::today())
                    ->whereBetween('updated_at', array($date_from->toDateTimeString(), $date_to->toDateTimeString()) )
                    ->active()->count();
            }),

            'in_lab_received' => $in_lab_received ?? 0,
            'in_lab_received_in_24_hrs' => $in_lab_received_in_24_hrs ?? 0,
            'in_lab_received_positive' => $in_lab_received_positive ?? 0,
            'in_lab_received_positive_in_24_hrs' => $in_lab_received_positive_in_24_hrs ?? 0,
            'in_lab_received_negative' => $in_lab_received_negative ?? 0,
            'in_lab_received_negative_in_24_hrs' => $in_lab_received_negative_in_24_hrs ?? 0,
            'total_immunization_record' => $total_immunization_record ?? 0,
            'immunization_registered' => $immunization_registered ?? 0,
            'immunized' => $immunized ?? 0,
            'vaccinated' => $vaccinated ?? 0,

            // time expiration in UMT add 5:45 to nepali time, sub 1 hrs to get updated at => 285
            // 'cache_created_at' => Carbon::parse(\DB::table('cache')->where('key', 'laravelregistered-'.auth()->user()->token)->first()->expiration)->addMinutes(285)->format('Y-m-d H:i:s'),
            'user_token' => auth()->user()->token,
//            'immunization_registered' => HealthProfessional::whereIn('checked_by', auth()->user()->token)
//                ->whereNull('vaccinated_status')->count(),
//            'immunized' => HealthProfessional::whereIn('checked_by', auth()->user()->token)
//                ->where('vaccinated_status', '1')->count(),
            'all_data' => $all_data,
        ];

        return response()->json($data);
    }
}