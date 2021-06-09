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
                return LabTest::whereIn('hp_code', $hpCodes)->where('sample_test_result', '3')->whereDate('updated_at', Carbon::today())->get()->count();
            });

            $in_lab_received_negative = Cache::remember('in_lab_received_negative-' . auth()->user()->token, 60 * 60, function () use ($hpCodes) {
                $current_data = LabTest::whereIn('hp_code', $hpCodes)->where('sample_test_result', '4')->get()->count();
                $dump_data = DB::connection('mysqldump')->table('lab_tests')->whereIn('hp_code', $hpCodes)->where('sample_test_result', '4')->get()->count();
                return $current_data + $dump_data;
            });

            $in_lab_received_negative_in_24_hrs = Cache::remember('in_lab_received_negative_in_24_hrs-' . auth()->user()->token, 60 * 60, function () use ($hpCodes) {
                return LabTest::whereIn('hp_code', $hpCodes)->where('sample_test_result', '4')->whereDate('updated_at', Carbon::today())->get()->count();
            });
        }
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
            'cache_created_at' => Carbon::parse(\DB::table('cache')->where('key', 'laravelregistered-'.auth()->user()->token)->first()->expiration)->addMinutes(285)->format('Y-m-d H:i:s'),
            'user_token' => auth()->user()->token,
//            'immunization_registered' => HealthProfessional::whereIn('checked_by', auth()->user()->token)
//                ->whereNull('vaccinated_status')->count(),
//            'immunized' => HealthProfessional::whereIn('checked_by', auth()->user()->token)
//                ->where('vaccinated_status', '1')->count()
        ];

        return response()->json($data);
    }
}