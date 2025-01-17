<?php

namespace App\Http\Controllers\Data\Api;

use App\CovidImmunization;
use App\Helpers\GetHealthpostCodes;
use App\Models\CommunityDeath;
use App\Models\HealthProfessional;
use App\Models\LabTest;
use App\Models\SampleCollection;
use App\Models\SampleCollectionOld;
use App\Models\SuspectedCase;
use App\Models\SuspectedCaseOld;
use App\Models\VaccinationRecord;
use App\Models\PaymentCase;
use App\Reports\FilterRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use DB;
use Exception;

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
        $user_role = auth()->user()->role;

        switch($user_role){
            case 'healthpost':
            case 'healthworker':
                $temp_name = 'hp-' . $hpCodes[0];
                break;
            case 'main':
            case 'center':
                $temp_name = 'main';
                break;
            case 'province':
                $temp_name = 'prov-' . $response['province_id'];
                break;
            case 'dho':
                $temp_name = 'dho-' . $response['district_id'];
                break;
            case 'municipality':
                $temp_name = 'mun-' . $response['municipality_id'];
                break;
            default:
                $temp_name = auth()->user()->token;
                break;

        }

        $request->session()->put('temp_name', $temp_name);

        $date_chosen = Carbon::now()->toDateString();
        if($request->date_selected){
            switch($request->date_selected){
                case '2':
                    $date_chosen = Carbon::now()->subDays(1)->toDateString();
                    break;
                case '3':
                    $date_chosen = Carbon::now()->subDays(2)->toDateString();
                    break;
                case '4':
                    $date_chosen = Carbon::now()->subDays(3)->toDateString();
                    break;
                case '5':
                    $date_chosen = Carbon::now()->subDays(4)->toDateString();
                    break;
                case '6':
                    $date_chosen = Carbon::now()->subDays(5)->toDateString();
                    break;
                case '7':
                    $date_chosen = Carbon::now()->subDays(6)->toDateString();
                    break;
                case '8':
                    $date_chosen = Carbon::now()->subDays(7)->toDateString();
                    break;
                default:
                    $date_chosen = Carbon::now()->toDateString();
                    break;
            }
        }

        if($request->date_selected == '0'){
            $antigen_positive = Cache::remember('antigen_positive-all-' . $temp_name, 60 * 60, function () use ($hpCodes) {
                $current_data = SampleCollection::whereIn('received_by_hp_code', $hpCodes)
                    ->where('service_for', '2')->where('result', '3')
                    ->active()
                    ->get()->count();
                $dump_data = SampleCollectionOld::whereIn('received_by_hp_code', $hpCodes)
                    ->where('service_for', '2')->where('result', '3')
                    ->where('status', 1)
                    ->get()->count();
                return $current_data + $dump_data;
            });
            $antigen_negative = Cache::remember('antigen_negative-all-' . $temp_name, 60 * 60, function () use ($hpCodes) {
                $current_data = SampleCollection::whereIn('received_by_hp_code', $hpCodes)
                    ->where('service_for', '2')->where('result', '4')
                    ->active()
                    ->get()->count();
                $dump_data = SampleCollectionOld::whereIn('received_by_hp_code', $hpCodes)
                    ->where('service_for', '2')->where('result', '4')
                    ->where('status', 1)
                    ->get()->count();
                return $current_data + $dump_data;
            });
            $pcr_positive = Cache::remember('pcr_positive-all-' . $temp_name, 60 * 60, function () use ($hpCodes) {
                $current_data = SampleCollection::whereIn('received_by_hp_code', $hpCodes)
                    ->where('service_for', '1')->where('result', '3')
                    ->active()
                    ->get()->count();
                $dump_data = SampleCollectionOld::whereIn('received_by_hp_code', $hpCodes)
                    ->where('service_for', '1')->where('result', '3')
                    ->where('status', 1)
                    ->get()->count();
                return $current_data + $dump_data;
            });
            $pcr_negative = Cache::remember('pcr_negative-all-' . $temp_name, 60 * 60, function () use ($hpCodes) {
                $current_data = SampleCollection::whereIn('received_by_hp_code', $hpCodes)
                    ->where('service_for', '1')->where('result', '4')
                    ->active()
                    ->get()->count();
                $dump_data = SampleCollectionOld::whereIn('received_by_hp_code', $hpCodes)
                    ->where('service_for', '1')->where('result', '4')
                    ->where('status', 1)
                    ->get()->count();
                return $current_data + $dump_data;
            });
            $hospital_admission= Cache::remember('hospital_admission-all-' . $temp_name, 60 * 60, function () use ($hpCodes) {
                return PaymentCase::whereIn('hp_code', $hpCodes)
                    ->count();
            });
            $hospital_active_cases = Cache::remember('hospital_active_cases-all-' . $temp_name, 60 * 60, function () use ($hpCodes) {
                return PaymentCase::whereIn('hp_code', $hpCodes)
                    ->whereNull('is_death')
                    ->count();
            });
            $hospital_discharge = Cache::remember('hospital_discharge-all-' . $temp_name, 60 * 60, function () use ($hpCodes) {
                return PaymentCase::whereIn('hp_code', $hpCodes)
                    ->where('is_death', 1)
                    ->count();
            });
            $hospital_death = Cache::remember('hospital_death-all-' . $temp_name, 60 * 60, function () use ($hpCodes) {
                return PaymentCase::whereIn('hp_code', $hpCodes)
                    ->where('is_death', 2)
                    ->count();
            });
        }
        else {
            $antigen_positive = Cache::remember('antigen_positive-' . $date_chosen . '-' . $temp_name, 60 * 60, function () use ($date_chosen, $hpCodes) {
                return SampleCollection::whereIn('received_by_hp_code', $hpCodes)
                    ->where('service_for', '2')->where('result', '3')
                    ->whereDate('reporting_date_en', $date_chosen)
                    ->active()
                    ->get()->count();
            });
            $antigen_negative = Cache::remember('antigen_negative-' . $date_chosen . '-' . $temp_name, 60 * 60, function () use ($date_chosen, $hpCodes) {
                return SampleCollection::whereIn('received_by_hp_code', $hpCodes)
                    ->where('service_for', '2')->where('result', '4')
                    ->whereDate('reporting_date_en', $date_chosen)
                    ->active()
                    ->get()->count();
            });
            $pcr_positive = Cache::remember('pcr_positive-' . $date_chosen . '-' . $temp_name, 60 * 60, function () use ($date_chosen, $hpCodes) {
                return SampleCollection::whereIn('received_by_hp_code', $hpCodes)
                    ->where('service_for', '1')->where('result', '3')
                    ->whereDate('reporting_date_en', $date_chosen)
                    ->active()
                    ->get()->count();
            });
            $pcr_negative = Cache::remember('pcr_negative-' . $date_chosen . '-' . $temp_name, 60 * 60, function () use ($date_chosen, $hpCodes) {
                return SampleCollection::whereIn('received_by_hp_code', $hpCodes)
                    ->where('service_for', '1')->where('result', '4')
                    ->whereDate('reporting_date_en', $date_chosen)
                    ->active()
                    ->get()->count();
            });
            $hospital_admission= Cache::remember('hospital_admission-' . $date_chosen . '-' . $temp_name, 60 * 60, function () use ($date_chosen, $hpCodes) {
                return PaymentCase::whereIn('hp_code', $hpCodes)
                    ->whereDate('register_date_en', $date_chosen)
                    ->count();
            });
            $hospital_active_cases = Cache::remember('hospital_active_cases-' . $date_chosen . '-' . $temp_name, 60 * 60, function () use ($date_chosen, $hpCodes) {
                return PaymentCase::whereIn('hp_code', $hpCodes)
                    ->whereDate('register_date_en', '<=', $date_chosen)
                    ->where(function($q) use ($date_chosen) {
                        $q->whereNull('date_of_outcome_en')
                            ->orWhereDate('date_of_outcome_en', '>', $date_chosen);
                        })
                    ->count();
            });
            $hospital_discharge = Cache::remember('hospital_discharge-' . $date_chosen . '-' . $temp_name, 60 * 60, function () use ($date_chosen, $hpCodes) {
                return PaymentCase::whereIn('hp_code', $hpCodes)
                    ->where('is_death', 1)
                    ->whereDate('date_of_outcome_en', $date_chosen)
                    ->count();
            });
            $hospital_death = Cache::remember('hospital_death-' . $date_chosen . '-' . $temp_name, 60 * 60, function () use ($date_chosen, $hpCodes) {
                return PaymentCase::whereIn('hp_code', $hpCodes)
                    ->where('is_death', 2)
                    ->whereDate('date_of_outcome_en', $date_chosen)
                    ->count();
            });
        }

        try {
          $cache_created_at = Carbon::parse(\DB::table('cache')->where('key', 'laravelpcr_positive-' . $date_chosen . '-' . $temp_name)->first()->expiration)->addMinutes(285)->format('Y-m-d H:i:s');
        } catch (Exception $e) {
          $cache_created_at = '-';
        }
        $data = [
            'antigen_positive' => $antigen_positive,
            'antigen_negative' => $antigen_negative,
            'pcr_positive' => $pcr_positive,
            'pcr_negative' => $pcr_negative,
            'hospital_admission' => $hospital_admission,
            'hospital_active_cases' => $hospital_active_cases,
            'hospital_discharge' => $hospital_discharge,
            'hospital_death' => $hospital_death,
            // 'temp_name' => $temp_name,
            'cache_created_at' => $cache_created_at
        ];

        return response()->json($data);
    }

    public function indexOld(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
        
        // if($request->date_selected == 2){
        //     $customRecDate = 'reporting_date_en';
        // } else {
        //     $customRecDate = 'received_date_en';
        // }

        //switch role
        //if reporting fetch/store from related reporting_id
        //if normal sore on h=_code

        $date_from = Carbon::today()->startOfDay();
        $date_to = Carbon::now();
        if (auth()->user()->role == 'healthworker' || auth()->user()->role == 'healthpost') {
            $in_lab_received = Cache::remember('in_lab_received-' . auth()->user()->token, 60 * 60, function() use ($hpCodes) {
                $current_data = SampleCollection::whereIn('received_by_hp_code', $hpCodes)->get()->count();
                $dump_data = SampleCollectionOld::whereIn('received_by_hp_code', $hpCodes)->get()->count();
                // $dump_data = DB::connection('mysqldump')->table('lab_tests')->whereIn('hp_code', $hpCodes)->get()->count();
                return $current_data + $dump_data;
            });

            $in_lab_received_in_24_hrs = Cache::remember('in_lab_received_in_24_hrs-' . auth()->user()->token, 60 * 60, function () use ($hpCodes) {
                return SampleCollection::whereIn('received_by_hp_code', $hpCodes)
                    ->where('received_date_en', Carbon::now()->toDateString())
                    ->get()->count();
            });

            $in_lab_received_positive = Cache::remember('in_lab_received_positive-' . auth()->user()->token, 60 * 60, function () use ($hpCodes) {
                $current_data = SampleCollection::whereIn('received_by_hp_code', $hpCodes)->where('result', '3')->get()->count();
                $dump_data = SampleCollectionOld::whereIn('received_by_hp_code', $hpCodes)->where('result', '3')->get()->count();
                // $dump_data = DB::connection('mysqldump')->table('lab_tests')->whereIn('hp_code', $hpCodes)->where('sample_test_result', '3')->get()->count();
                return $current_data + $dump_data;
            });

            $in_lab_received_positive_in_24_hrs = Cache::remember('in_lab_received_positive_in_24_hrs-' . auth()->user()->token, 60 * 60, function () use ($hpCodes) {
                return SampleCollection::whereIn('received_by_hp_code', $hpCodes)->where('result', '3')
                    ->where('sample_test_date_en', Carbon::now()->toDateString())
                    ->get()->count();
            });

            $in_lab_received_negative = Cache::remember('in_lab_received_negative-' . auth()->user()->token, 60 * 60, function () use ($hpCodes) {
                $current_data = SampleCollection::whereIn('received_by_hp_code', $hpCodes)->where('result', '4')->get()->count();
                $dump_data = SampleCollectionOld::whereIn('received_by_hp_code', $hpCodes)->where('result', '4')->get()->count();
                // $dump_data = DB::connection('mysqldump')->table('lab_tests')->whereIn('hp_code', $hpCodes)->where('sample_test_result', '4')->get()->count();
                return $current_data + $dump_data;
            });

            $in_lab_received_negative_in_24_hrs = Cache::remember('in_lab_received_negative_in_24_hrs-' . auth()->user()->token, 60 * 60, function () use ($hpCodes) {
                return SampleCollection::whereIn('received_by_hp_code', $hpCodes)->where('result', '4')
                    ->where('sample_test_date_en', Carbon::now()->toDateString())
                    ->get()->count();
            });
        }

        $data = [
            'registered' => Cache::remember('registered-' . auth()->user()->token, 60 * 60, function () use ($hpCodes) {
                $current_data = SuspectedCase::whereIn('hp_code', $hpCodes)->active()->count();
                $dump_data = SuspectedCaseOld::whereIn('hp_code', $hpCodes)->where('status', 1)->count();
                // $dump_data = DB::connection('mysqldump')->table('women')->whereIn('hp_code', $hpCodes)->where('status', 1)->count();
                return $current_data + $dump_data;
            }),
            'registered_in_24_hrs' => Cache::remember('registered_in_24_hrs-' . auth()->user()->token, 60 * 60, function () use ($date_to, $date_from, $hpCodes) {
                return SuspectedCase::whereIn('hp_code', $hpCodes)->active()
                    ->where('register_date_en', Carbon::today()->toDateString())
//                      ->whereBetween('register_date_en', array($date_from->toDateTimeString(), $date_to->toDateTimeString()) )
                    ->count();
            }),
            'sample_collection' => Cache::remember('sample_collection-' . auth()->user()->token, 60 * 60, function () use ($hpCodes) {
                $current_data = SampleCollection::whereIn('hp_code', $hpCodes)->active()->count();
                $dump_data = SampleCollectionOld::whereIn('hp_code', $hpCodes)->where('status', 1)->count();
                // $dump_data = DB::connection('mysqldump')->table('ancs')->whereIn('hp_code', $hpCodes)->where('status', 1)->count();
                return $current_data + $dump_data;
            }),

            'sample_collection_antigen' => Cache::remember('sample_collection_antigen-' . auth()->user()->token, 60 * 60, function () use ($hpCodes) {
                $current_data = SampleCollection::whereIn('hp_code', $hpCodes)->where('service_for', '2')->active()->count();
                $dump_data = DB::connection('mysqldump')->table('ancs')->whereIn('hp_code', $hpCodes)->where('service_for', '2')->where('status', 1)->count();
                return $current_data + $dump_data;
            }),
            'sample_collection_in_24_hrs' => Cache::remember('sample_collection_in_24_hrs-' . auth()->user()->token, 60 * 60, function () use ($date_to, $date_from, $hpCodes) {
                return SampleCollection::whereIn('hp_code', $hpCodes)->active()
                    ->where('collection_date_en', Carbon::today()->toDateString())
//                    ->whereBetween('collection_date_en', array($date_from->toDateTimeString(), $date_to->toDateTimeString()) )
                    ->count();
            }),
            'sample_collection_in_24_hrs_antigen' => Cache::remember('sample_collection_in_24_hrs_antigen-' . auth()->user()->token, 60 * 60, function () use ($date_to, $date_from, $hpCodes) {
                return SampleCollection::whereIn('hp_code', $hpCodes)->where('service_for', '2')->active()
                    ->where('collection_date_en', Carbon::today()->toDateString())
//                    ->whereBetween('collection_date_en', array($date_from->toDateTimeString(), $date_to->toDateTimeString()) )
                    ->count();
            }),

            'sample_received_in_lab' => Cache::remember('sample_received_in_lab-' . auth()->user()->token, 60 * 60, function () use ($hpCodes) {
                $current_data = SampleCollection::whereIn('hp_code', $hpCodes)->whereIn('result', [9, 3, 4, 5])->active()->count();
                $dump_data = SampleCollectionOld::whereIn('hp_code', $hpCodes)->whereIn('result', [9, 3, 4, 5])->where('status', 1)->count();
                // $dump_data = DB::connection('mysqldump')->table('ancs')->whereIn('received_by_hp_code', $hpCodes)->whereIn('result', [9, 3, 4, 5])->where('status', 1)->count();
                return $current_data + $dump_data;
            }),
            'sample_received_in_lab_antigen' => Cache::remember('sample_received_in_lab_antigen-' . auth()->user()->token, 60 * 60, function () use ($hpCodes) {
                $current_data = SampleCollection::whereIn('hp_code', $hpCodes)->where('service_for', '2')->whereIn('result', [9, 3, 4, 5])->active()->count();
                $dump_data = SampleCollectionOld::whereIn('hp_code', $hpCodes)->where('service_for', '2')->whereIn('result', [9, 3, 4, 5])->where('status', 1)->count();
                // $dump_data = DB::connection('mysqldump')->table('ancs')->whereIn('hp_code', $hpCodes)->where('service_for', '2')->whereIn('result', [9, 3, 4, 5])->where('status', 1)->count();
                return $current_data + $dump_data;
            }),
            'sample_received_in_lab_in_24_hrs' => Cache::remember('sample_received_in_lab_in_24_hrs-' . auth()->user()->token, 60 * 60, function () use ($date_to, $date_from, $hpCodes) {
                return SampleCollection::whereIn('hp_code', $hpCodes)->whereIn('result', [9, 3, 4, 5])
                    ->where('received_date_en', Carbon::today()->toDateString())
//                    ->whereBetween('received_date_en', array($date_from->toDateTimeString(), $date_to->toDateTimeString()) )
                    ->active()->count();
            }),
            'sample_received_in_lab_in_24_hrs_antigen' => Cache::remember('sample_received_in_lab_in_24_hrs_antigen-' . auth()->user()->token, 60 * 60, function () use ($date_to, $date_from, $hpCodes) {
                return SampleCollection::whereIn('hp_code', $hpCodes)->where('service_for', '2')
                    ->whereIn('result', [9, 3, 4, 5])
                    ->where('received_date_en', Carbon::today()->toDateString())
//                    ->whereBetween('received_date_en', array($date_from->toDateTimeString(), $date_to->toDateTimeString()) )
                    ->active()->count();
            }),
            'lab_result_positive' => Cache::remember('lab_result_positive-' . auth()->user()->token, 60 * 60, function () use ($hpCodes) {
                $current_data = SampleCollection::whereIn('hp_code', $hpCodes)->where('result', 3)->active()->count();
                $dump_data = SampleCollectionOld::whereIn('hp_code', $hpCodes)->where('result', 3)->where('status', 1)->count();
                // $dump_data = DB::connection('mysqldump')->table('ancs')->whereIn('hp_code', $hpCodes)->where('result', 3)->where('status', 1)->count();
                return $current_data + $dump_data;
            }),
            'lab_result_positive_antigen' => Cache::remember('lab_result_positive_antigen-' . auth()->user()->token, 60 * 60, function () use ($hpCodes) {
                $current_data = SampleCollection::whereIn('hp_code', $hpCodes)->where('service_for', '2')->where('result', 3)->active()->count();
                $dump_data = SampleCollectionOld::whereIn('hp_code', $hpCodes)->where('service_for', '2')->where('result', 3)->where('status', 1)->count();
                // $dump_data = DB::connection('mysqldump')->table('ancs')->whereIn('hp_code', $hpCodes)->where('service_for', '2')->where('result', 3)->where('status', 1)->count();
                return $current_data + $dump_data;
            }),
            'lab_result_positive_in_24_hrs' => Cache::remember('lab_result_positive_in_24_hrs-' . auth()->user()->token, 60 * 60, function () use ($date_to, $date_from, $hpCodes) {
                return SampleCollection::whereIn('hp_code', $hpCodes)->where('result', 3)
                    ->where('sample_test_date_en', Carbon::today()->toDateString())
//                    ->whereBetween('sample_test_date_en', array($date_from->toDateTimeString(), $date_to->toDateTimeString()) )
                    ->active()->count();
            }),
            'lab_result_positive_in_24_hrs_antigen' => Cache::remember('lab_result_positive_in_24_hrs_antigen-' . auth()->user()->token, 60 * 60, function () use ($date_to, $date_from, $hpCodes) {
                return SampleCollection::whereIn('hp_code', $hpCodes)->where('service_for', '2')
                    ->where('result', 3)
                    ->where('sample_test_date_en', Carbon::today()->toDateString())
//                    ->whereBetween('sample_test_date_en', array($date_from->toDateTimeString(), $date_to->toDateTimeString()))
                    ->active()->count();
            }),
            'lab_result_negative' => Cache::remember('lab_result_negative-' . auth()->user()->token, 60 * 60, function () use ($hpCodes) {
                $current_data = SampleCollection::whereIn('hp_code', $hpCodes)->where('result', 4)->active()->count();
                $dump_data = SampleCollectionOld::whereIn('hp_code', $hpCodes)->where('result', 4)->where('status', 1)->count();
                // $dump_data = DB::connection('mysqldump')->table('ancs')->whereIn('hp_code', $hpCodes)->where('result', 4)->where('status', 1)->count();
                return $current_data + $dump_data;
            }),
            'lab_result_negative_antigen' => Cache::remember('lab_result_negative_antigen-' . auth()->user()->token, 60 * 60, function () use ($hpCodes) {
                $current_data = SampleCollection::whereIn('hp_code', $hpCodes)->where('service_for', '2')->where('result', 4)->active()->count();
                $dump_data = SampleCollectionOld::whereIn('hp_code', $hpCodes)->where('service_for', '2')->where('result', 4)->where('status', 1)->count();
                // $dump_data = DB::connection('mysqldump')->table('ancs')->whereIn('hp_code', $hpCodes)->where('service_for', '2')->where('result', 4)->where('status', 1)->count();
                return $current_data + $dump_data;
            }),
            'lab_result_negative_in_24_hrs' => Cache::remember('lab_result_negative_in_24_hrs-' . auth()->user()->token, 60 * 60, function () use ($date_to, $date_from, $hpCodes) {
                return SampleCollection::whereIn('hp_code', $hpCodes)->where('result', 4)
                    ->where('sample_test_date_en', Carbon::today()->toDateString())
//                    ->whereBetween('sample_test_date_en', array($date_from->toDateTimeString(), $date_to->toDateTimeString()) )
                    ->active()->count();
            }),
            'lab_result_negative_in_24_hrs_antigen' => Cache::remember('lab_result_negative_in_24_hrs_antigen-' . auth()->user()->token, 60 * 60, function () use ($date_to, $date_from, $hpCodes) {
                return SampleCollection::whereIn('hp_code', $hpCodes)->where('service_for', '2')
                    ->where('result', 4)
                    ->whereDate('sample_test_date_en', Carbon::today()->toDateString())
//                    ->whereBetween('sample_test_date_en', array($date_from->toDateTimeString(), $date_to->toDateTimeString()) )
                    ->active()->count();
            }),
            'todays_community_death' => Cache::remember('todays_community_death-' . auth()->user()->token, 60 * 60, function () use ($date_to, $date_from, $hpCodes) {
                return CommunityDeath::whereIn('hp_code', $hpCodes)
                    ->whereBetween('created_at', array($date_from->toDateTimeString(), $date_to->toDateTimeString()))
                    ->count();
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
            'user_token' => auth()->user()->token
//            'immunization_registered' => HealthProfessional::whereIn('checked_by', auth()->user()->token)
//                ->whereNull('vaccinated_status')->count(),
//            'immunized' => HealthProfessional::whereIn('checked_by', auth()->user()->token)
//                ->where('vaccinated_status', '1')->count(),
        ];

        return response()->json($data);
    }

    public function poeDashboard(Request $request) {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
        $date_from = Carbon::today()->startOfDay();
        $date_to = Carbon::now();
        $data = [];

        $current_data = SuspectedCase::whereIn('hp_code', $hpCodes)->active()->count();
        try {
          $dump_data = SuspectedCaseOld::whereIn('hp_code', $hpCodes)->where('status', 1)->count();
        } catch (\Exception $e) {
          $dump_data = 0;
        }
        $data['registered'] = $current_data + $dump_data;

        $current_data = SampleCollection::whereIn('hp_code', $hpCodes)->where('service_for', '2')
            ->where('result', 3)->active()->count();
        try {
          $dump_data = SampleCollectionOld::whereIn('hp_code', $hpCodes)->where('service_for', '2')
            ->where('result', 3)->where('status', 1)->count(); 
        } catch (\Exception $e) {
          $dump_data = 0;
        }
        $data['lab_result_positive_antigen'] = $current_data + $dump_data;

        $current_data = SampleCollection::whereIn('hp_code', $hpCodes)->where('service_for', '2')
            ->where('result', 4)->active()->count();
        try {
          $dump_data = SampleCollectionOld::whereIn('hp_code', $hpCodes)->where('service_for', '2')
            ->where('result', 4)->where('status', 1)->count();
        } catch (\Exception $e) {
          $dump_data = 0;
        }
        $data['lab_result_negative_antigen'] = $current_data + $dump_data;

        return response()->json($data);
    }

    public function poeDashboardByDate(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);

        $date_chosen = Carbon::now()->toDateString();
        if($request->date_selected){
            if($request->date_selected == '2') {
                $date_chosen = Carbon::now()->subDays(1)->toDateString();
            }elseif($request->date_selected == '3') {
                $date_chosen = Carbon::now()->subDays(2)->toDateString();
            }elseif($request->date_selected == '4') {
                $date_chosen = Carbon::now()->subDays(3)->toDateString();
            }elseif($request->date_selected == '5') {
                $date_chosen = Carbon::now()->subDays(4)->toDateString();
            }elseif($request->date_selected == '6') {
                $date_chosen = Carbon::now()->subDays(5)->toDateString();
            }elseif($request->date_selected == '7') {
                $date_chosen = Carbon::now()->subDays(6)->toDateString();
            }elseif($request->date_selected == '8') {
                $date_chosen = Carbon::now()->subDays(7)->toDateString();
            }else {
                $date_chosen = Carbon::now()->toDateString();
            }
        }

        $total_registered_all = SuspectedCase::whereIn('hp_code', $hpCodes)
            ->whereDate('created_at', $date_chosen)
            ->active()
            ->count();
        $total_registered_only = SuspectedCase::whereIn('hp_code', $hpCodes)
            ->whereDate('created_at', $date_chosen)
            ->active()
            ->doesnthave('ancs')
            ->count();
        $antigen_positive = SampleCollection::whereIn('hp_code', $hpCodes)
            ->where('service_for', '2')->where('result', '3')
            ->whereDate('reporting_date_en', $date_chosen)
            ->active()
            ->count();
        $antigen_negative = SampleCollection::whereIn('hp_code', $hpCodes)
            ->where('service_for', '2')->where('result', '4')
            ->whereDate('reporting_date_en', $date_chosen)
            ->active()
            ->count();
            
        $total_tested = $antigen_positive + $antigen_negative;
        $pr = '-';
        if ($total_tested != 0) {
          $pr = $antigen_positive/($antigen_negative+$antigen_positive);
          $pr = number_format($pr*100, 2).'%';
        }

        $data = [
            'antigen_positive' => $antigen_positive,
            'antigen_negative' => $antigen_negative,
            'total_registered_only' => $total_registered_only,
            'total_registered_all' => $total_registered_all,
            'pr' => $pr
        ];

        return response()->json($data);
    }
}