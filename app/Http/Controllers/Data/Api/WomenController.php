<?php


namespace App\Http\Controllers\Data\Api;

use App\Helpers\GetHealthpostCodes;
use App\Http\Controllers\Controller;
use App\Models\CictTracing;
use App\Models\ContactTracing;
use App\Models\ContactTracingOld;
use App\Models\Organization;
use App\Models\PaymentCase;
use App\Models\SampleCollection;
use App\Models\SampleCollectionOld;
use App\Models\LabTest;
use App\Models\LabTestOld;
use App\Models\SuspectedCase;
use App\Models\SuspectedCaseOld;
use App\Models\CommunityDeath;
use App\Models\Municipality;
use App\Reports\FilterRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yagiten\Nepalicalendar\Calendar;
use App\User;
use Illuminate\Validation\Rule;
use Auth;

class WomenController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
        $sample_collection_token = SampleCollection::where('hp_code', $hpCodes)->pluck('woman_token');
        $woman = SuspectedCase::whereIn('token', $sample_collection_token)->active()
            ->withAll();
        return response()->json([
            'collection' => $woman->advancedFilter()
        ]);
    }

    // Registered

    public function activeIndex(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);

        if ($request->db_switch == '2') {
            $woman = SuspectedCaseOld::active();
        } else {
            $woman = SuspectedCase::active();
        }

        if (Auth::user()->role == 'healthworker') {
            if (Auth::user()->can('poe-registration')) {
                $woman->where('case_type', '3');
            } else {
                $woman->where('case_type', '!=', '3');
            }
        }
        $woman = $woman->whereIn('hp_code', $hpCodes)
            ->doesnthave('ancs')
            ->with(['municipality',
                'healthpost']);

        $total = clone $woman;

        return response()->json([
            'collection' => $woman->advancedFilter(),
            'total' => $total->count()
        ]);
    }

    // Pending
    public function activePendingIndex(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);

        if ($request->db_switch == '2') {
            $woman = SuspectedCaseOld::active();
        } else {
            $woman = SuspectedCase::active();
        }

        if (Auth::user()->role == 'healthworker') {
            if (Auth::user()->can('poe-registration')) {
                $woman->where('case_type', '3');
            } else {
                $woman->where('case_type', '!=', '3');
            }
        }

        $woman = $woman->whereIn('hp_code', $hpCodes)
            ->where(function ($query) {
                $query->whereHas('ancs', function ($q) {
                    $q->where('service_for', '!=', "2")->whereIn('result', [0,2]);
                });
            })
            ->with(['municipality', 'latestAnc', 'ancs',
                'healthpost']);
        $total = clone $woman;

        return response()->json([
            'collection' => $woman->advancedFilter(),
            'total' => $total->count()
        ]);
    }

    public function activeAntigenPendingIndex(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
 
        if ($request->db_switch == '2') {
            $woman = SuspectedCaseOld::active();
        } else {
            $woman = SuspectedCase::active();
        }

        if (Auth::user()->role == 'healthworker') {
            if (Auth::user()->can('poe-registration')) {
                $woman->where('case_type', '3');
            } else {
                $woman->where('case_type', '!=', '3');
            }
        }

        $woman->whereIn('hp_code', $hpCodes)
            ->where(function ($query) {
                $query->whereHas('ancs', function ($q) {
                    $q->where('service_for', "2")->whereIn('result', [0,2]);
                });
            })
            ->with(['municipality', 'latestAnc', 'ancs',
                'healthpost']);
        $total = clone $woman;

        return response()->json([
            'collection' => $woman->advancedFilter(),
            'total' => $total->count()
        ]);
    }

    // Negative

    public function passiveIndex(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
//        $token = SampleCollection::whereIn('hp_code', $hpCodes)->where('result', 4)->pluck('woman_token');
//        $woman = SuspectedCase::whereIn('token', $token)->active()->withAll();

        if ($request->db_switch == '2') {
            $woman = SuspectedCaseOld::active();
        } else {
            $woman = SuspectedCase::active();
        }

        if (Auth::user()->role == 'healthworker') {
            if (Auth::user()->can('poe-registration')) {
                $woman->where('case_type', '3');
            } else {
                $woman->where('case_type', '!=', '3');
            }
        }
        $woman->whereIn('hp_code', $hpCodes)
            ->whereHas('ancs', function ($q) {
                $q->where('service_for', '!=', "2")->where('result', '=', 4);
            })
            ->with([
                 'municipality','district', 'ancs',
                'latestAnc' => function ($q) {
                    $q->with('getOrganization');
                },
                'healthpost'
            ]);

        $total = clone $woman;

        return response()->json([
                'collection' => $woman->advancedFilter(),
                'total' => $total->count()
            ]);
    }

    public function passiveAntigenIndex(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
        
        if ($request->db_switch == '2') {
            $woman = SuspectedCaseOld::active();
        } else {
            $woman = SuspectedCase::active();
        }

        if (Auth::user()->role == 'healthworker') {
            if (Auth::user()->can('poe-registration')) {
                $woman->where('case_type', '3');
            } else {
                $woman->where('case_type', '!=', '3');
            }
        }

        $woman->whereIn('hp_code', $hpCodes)
            ->whereHas('ancs', function ($q) {
                $q->where('service_for', "2")->where('result', '=', 4);
            })
            ->with([
                'district','municipality', 'ancs',
                'latestAnc' => function ($q) {
                    $q->with('getOrganization');
                },
                'healthpost'
            ]);

        $total = clone $woman;

        return response()->json([
                'collection' => $woman->advancedFilter(),
                'total' => $total->count()
            ]);
    }
    
    public function positiveIndex(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
//        $token = SampleCollection::whereIn('hp_code', $hpCodes)->where('result', 3)->pluck('woman_token');

        if ($request->db_switch == '2') {
            $woman = SuspectedCaseOld::active();
        } else {
            $woman = SuspectedCase::active();
        }

        if (Auth::user()->role == 'healthworker') {
            if (Auth::user()->can('poe-registration')) {
                $woman->where('case_type', '3');
            } else {
                $woman->where('case_type', '!=', '3');
            }
        }

        $woman->whereIn('hp_code', $hpCodes)->whereHas('ancs', function ($q) {
            $q->where('service_for', '!=', "2")->where('result', '=', 3);
        })->with([
                'ancs','healthpost' => function ($q) {
                    $q->select('name', 'hp_code');
                },
                'latestAnc' => function ($q) {
                    $q->with('getOrganization');
                },
                'municipality', 
                'cictTracing' => function($q) {
                    $q->with('organization');
                }
            ]);
        $total = clone $woman;

        return response()->json([
                'collection' => $woman->advancedFilter(),
                'total' => $total->count()
            ]);
    }

    public function positiveAntigenIndex(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);

        if ($request->db_switch == '2') {
            $woman = SuspectedCaseOld::active();
        } else {
            $woman = SuspectedCase::active();
        }

        if (Auth::user()->role == 'healthworker') {
            if (Auth::user()->can('poe-registration')) {
                $woman->where('case_type', '3');
            } else {
                $woman->where('case_type', '!=', '3');
            }
        }
        $woman
        ->whereIn('hp_code', $hpCodes)
            ->where(function ($query) {
                $query->whereHas('ancs', function ($q) {
                    $q->where('service_for', "2")->where('result', 3);
                });
            })
        ->with([
            'municipality','ancs', 
            'cictTracing' => function($q) {
                $q->with('organization');
            },
            'latestAnc' => function ($q) {
                $q->with('getOrganization');
            },
            'healthpost']);
        $total = clone $woman;

        return response()->json([
            'collection' => $woman->advancedFilter(),
            'total' => $total->count()
        ]);
    }

    public function tracingIndex(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);

        if ($request->db_switch == '2') {
            $tracing_tokens = ContactTracingOld::whereIn('hp_code', $hpCodes)->pluck('woman_token');
//            $woman = SuspectedCaseOld::active();
            $woman1 = SuspectedCase::whereIn('token', $tracing_tokens)->active();
            $woman2 = SuspectedCaseOld::whereIn('token', $tracing_tokens)->active();
            $woman = $woman1->unionAll($woman2)->distinct();
        } else {
            $tracing_tokens = ContactTracing::whereIn('hp_code', $hpCodes)->pluck('woman_token');
//            $woman = SuspectedCase::active();
            $woman2 = SuspectedCaseOld::whereIn('token', $tracing_tokens)->active();
            $woman1 = SuspectedCase::whereIn('token', $tracing_tokens)->active();
            $woman = $woman1->unionAll($woman2)->distinct();
        }
        $woman = $woman->withAll();
        return response()->json([
            'collection' => $woman->advancedFilter()
        ]);
    }

    public function labReceivedIndex(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);

        if ($request->db_switch == '2') {
            $woman = SuspectedCaseOld::active();
        } else {
            $woman = SuspectedCase::active();
        }

        if (Auth::user()->role == 'healthworker') {
            if (Auth::user()->can('poe-registration')) {
                $woman->where('case_type', '3');
            } else {
                $woman->where('case_type', '!=', '3');
            }
        }

        $woman->whereIn('hp_code', $hpCodes)
            ->whereHas('ancs', function ($q) {
                $q->where('service_for', '!=', "2")->where('result', '=', 9);
            })->with(['ancs','healthpost', 'latestAnc', 'municipality']);
        $total = clone $woman;

        return response()->json([
                'collection' => $woman->advancedFilter(),
                'total' => $total->count()
            ]);
    }

    public function labReceivedAntigenIndex(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);

        if ($request->db_switch == '2') {
            $woman = SuspectedCaseOld::active();
        } else {
            $woman = SuspectedCase::active();
        }

        if (Auth::user()->role == 'healthworker') {
            if (Auth::user()->can('poe-registration')) {
                $woman->where('case_type', '3');
            } else {
                $woman->where('case_type', '!=', '3');
            }
        }

        $woman->whereIn('hp_code', $hpCodes)
            ->where(function ($query) {
                $query->whereHas('ancs', function ($q) {
                    $q->where('service_for', "2")->where('result', 9);
                });
            })
            ->with(['municipality', 'latestAnc', 'ancs',
                'healthpost']);
        $total = clone $woman;

        return response()->json([
            'collection' => $woman->advancedFilter(),
            'total' => $total->count()
        ]);
    }

    public function labAddReceivedIndex(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
        $user = auth()->user();

        if ($request->db_switch == '2') {
            $sample_token = SampleCollectionOld::where('result', '9');
            $data = SuspectedCaseOld::active();
        } else {
            $sample_token = SampleCollection::where('result', '9');
            $data = SuspectedCase::active();
        }

        $sample_token = $sample_token->where('service_for', '!=', '2')
            ->where(function ($q) use ($hpCodes, $user) {
            $q->where('received_by', $user->token)
                ->orWhereIn('received_by_hp_code', $hpCodes);
        })->pluck('woman_token');

        $data = $data->whereIn('token', $sample_token)->withAll();

        return response()->json([
            'collection' => $data->advancedFilter()
        ]);
    }

    public function labAddReceivedAntigenIndex(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
        $user = auth()->user();

        if ($request->db_switch == '2') {
            $sample_token = SampleCollectionOld::where('result', '9');
            $data = SuspectedCaseOld::active();
        } else {
            $sample_token = SampleCollection::where('result', '9');
            $data = SuspectedCase::active();
        }

        $sample_token = $sample_token->where('service_for', '2')
            ->where(function ($q) use ($hpCodes, $user) {
            $q->where('received_by', $user->token)
                ->orWhereIn('received_by_hp_code', $hpCodes);
        })->pluck('woman_token');

        $data = $data->whereIn('token', $sample_token)->withAll();

        return response()->json([
            'collection' => $data->advancedFilter()
        ]);
    }

    public function labAddResultPositiveIndex(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
        $user = auth()->user();

        if ($request->db_switch == '2') {
            $sample_token = SampleCollectionOld::where('result', '3');
            $data = SuspectedCaseOld::active();
        } else {
            $sample_token = SampleCollection::where('result', '3');
            $data = SuspectedCase::active();
        }

        $sample_token = $sample_token->where('service_for', '!=', '2')
            ->where(function ($q) use ($hpCodes, $user) {
            $q->where('received_by', $user->token)
                ->orWhereIn('received_by_hp_code', $hpCodes);
        })->pluck('woman_token');
        $data = $data->whereIn('token', $sample_token)->withAll();
        return response()->json([
            'collection' => $data->advancedFilter()
        ]);
    }

    public function labAddResultPositiveAntigenIndex(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
        $user = auth()->user();

        if ($request->db_switch == '2') {
            $sample_token = SampleCollectionOld::where('result', '3');
            $data = SuspectedCaseOld::active();
        } else {
            $sample_token = SampleCollection::where('result', '3');
            $data = SuspectedCase::active();
        }

        $sample_token = $sample_token->where('service_for', '2')
            ->where(function ($q) use ($hpCodes, $user) {
            $q->where('received_by', $user->token)
                ->orWhereIn('received_by_hp_code', $hpCodes);
        })->pluck('woman_token');
        $data = $data->whereIn('token', $sample_token)->withAll();
        return response()->json([
            'collection' => $data->advancedFilter()
        ]);
    }

    public function labAddResultNegativeIndex(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
        $user = auth()->user();

        if ($request->db_switch == '2') {
            $sample_token = SampleCollectionOld::where('result', '4');
            $data = SuspectedCaseOld::active();
        } else {
            $sample_token = SampleCollection::where('result', '4');
            $data = SuspectedCase::active();
        }
        $sample_token = $sample_token->where('service_for', '!=', '2')
            ->where(function ($q) use ($hpCodes, $user) {
            $q->where('received_by', $user->token)
                ->orWhereIn('received_by_hp_code', $hpCodes);
        })->pluck('woman_token');
        $data = $data->whereIn('token', $sample_token)->withAll();

        return response()->json([
            'collection' => $data->advancedFilter()
        ]);
    }

    public function labAddResultNegativeAntigenIndex(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
        $user = auth()->user();

        if ($request->db_switch == '2') {
            $sample_token = SampleCollectionOld::where('result', '4');
            $data = SuspectedCaseOld::active();
        } else {
            $sample_token = SampleCollection::where('result', '4');
            $data = SuspectedCase::active();
        }
        $sample_token = $sample_token->where('service_for', '2')
            ->where(function ($q) use ($hpCodes, $user) {
            $q->where('received_by', $user->token)
                ->orWhereIn('received_by_hp_code', $hpCodes);
        })->pluck('woman_token');
        $data = $data->whereIn('token', $sample_token)->withAll();

        return response()->json([
            'collection' => $data->advancedFilter()
        ]);
    }

    public function casesRecoveredIndex(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);

        if ($request->db_switch == '2') {
            $woman = SuspectedCaseOld::active();
        } else {
            $woman = SuspectedCase::active();
        }

        $woman = $woman->whereIn('hp_code', $hpCodes)->casesRecoveredList()
        ->with(['ancs','healthpost' => function ($q) {
            $q->select('name', 'hp_code');
        }, 'latestAnc', 'district', 'municipality']);
        return response()->json([
            'collection' => $woman->advancedFilter()
        ]);
    }

    public function casesDeathIndex(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);

        if ($request->db_switch == '2') {
            $woman = SuspectedCaseOld::active();
        } else {
            $woman = SuspectedCase::active();
        }
        
        $woman = $woman->whereIn('hp_code', $hpCodes)
            ->casesDeathList()
            ->with(['ancs','healthpost' => function ($q) {
                $q->select('name', 'hp_code');
            }, 'latestAnc', 'district', 'municipality']);
        return response()->json([
            'collection' => $woman->advancedFilter()
        ]);
    }

    public function casesInOtherOrganization(Request $request): \Illuminate\Http\JsonResponse
    {
        $request = FilterRequest::filter($request);
        $hp_codes = Organization::where('municipality_id', $request['municipality_id'])->pluck('hp_code');
        $woman = SuspectedCase::where('municipality_id', $request['municipality_id'])
            ->whereNotIn('hp_code', $hp_codes)
            ->whereHas('ancs', function ($q) {
                $q->where('result', '3');
            })
            ->with('cictTracing')
            ->active()->withAll();
        return response()->json([
            'collection' => $woman->advancedFilter()
        ]);
    }

    public function show($token)
    {
        $data = SuspectedCase::withAll()->where('token', $token)->first();
        return response()->json([
            'record' => $data
        ]);
    }

    public function export(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
        $woman = SuspectedCase::whereIn('hp_code', $hpCodes)->active()->withAll()->with('municipality', 'district');

        $woman = $woman->get()->filter(function ($item, $key) {
            if ($item->latestAnc()->exists()) {
                return $item->latestAnc()->first()->result != "4";
            }
            if (!$item->latestAnc()->exists()) {
                return $item;
            }
        })->values();

        $formated_data = $woman->map(function ($item, $key) {
            $data = [];
            $data['serial_number'] = $key + 1;
            $data['name'] = $item['name'];
            $data['age'] = $item['age'];
            $data['age_unit'] = $this->ageUnitCheck($item['age_unit']);

            $data['emergency_contact_one'] = $item['emergency_contact_one'];
            $data['emergency_contact_two'] = $item['emergency_contact_two'];
            $data['district'] = $item['district']['district_name'];
            $data['municipality'] = $item['municipality']['municipality_name'];
            $data['ward'] = $item['ward'];
            $data['current_hospital'] = $item['healthpost']['name'];
            $data['swab_id'] = $item['latestAnc']['token'];
            $data['lab_id'] = $item['latestAnc']['labreport']['formated_token'];
            $data['result'] = $item['latestAnc']['formatted_result'];
            $data['created_at'] = Carbon::parse($item['created_at'])->format('Y-m-d');
            return $data;
        })->values();

        return response()->json($formated_data);
    }

    public function labExport()
    {
        $user = auth()->user();
        $sample_token = LabTest::where('checked_by', $user->token)->pluck('sample_token');
        $token = SampleCollection::whereIn('token', $sample_token)->pluck('woman_token');
        $data = SuspectedCase::whereIn('token', $token)->latest()->active()->withAll()->get();

        $final_data = $data->map(function ($item, $key) {
            $data = [];
            $data['serial_number'] = $key + 1;
            $data['name'] = $item['name'];
            $data['age'] = $item['age'];
            $data['age_unit'] = $this->ageUnitCheck($item['age_unit']);

            $data['emergency_contact_one'] = $item['emergency_contact_one'];
            $data['emergency_contact_two'] = $item['emergency_contact_two'];
            $data['district'] = $item['district']['district_name'];
            $data['municipality'] = $item['municipality']['municipality_name'];
            $data['ward'] = $item['ward'];
            $data['current_hospital'] = $item['healthpost']['name'];
            $data['swab_id'] = $item['latestAnc']['token'];
            $data['lab_id'] = $item['latestAnc']['labreport']['formated_token'];
            $data['result'] = $item['latestAnc']['formatted_result'];
            $data['created_at'] = Carbon::parse($item['created_at'])->format('Y-m-d');
            return $data;
        })->values();
        return response()->json($final_data);
    }
    private function ageUnitCheck($data)
    {
        switch ($data) {
            case '1':
                return 'Months';
            case '2':
                return 'Days';
            default:
                return 'Years';
        }
    }

    public function casesPaymentIndex(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
        
        $data = PaymentCase::whereIn('hp_code', $hpCodes)->whereNull('is_death')->latest()->withAll()->advancedFilter();
        return response()->json(['collection' => $data]);
    }

    public function casesPaymentDischargeIndex(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);

        $data = PaymentCase::whereIn('hp_code', $hpCodes)->where('is_death', 1)->latest()->withAll()->advancedFilter();
        return response()->json(['collection' => $data]);
    }

    public function casesPaymentDeathIndex(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);

        $data = PaymentCase::whereIn('hp_code', $hpCodes)->where('is_death', 2)->latest()->withAll()->advancedFilter();
        return response()->json(['collection' => $data]);
    }

    public function communityDeathIndex(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
        
        $data = CommunityDeath::whereIn('hp_code', $hpCodes)->latest()->withAll()->advancedFilter();
        return response()->json(['collection' => $data]);
    }

    public function deleteSuspectedCase($id)
    {
        try {
            $patients = SuspectedCase::with('ancs')->where('token', $id)->first();
            if ($patients->ancs) {
                foreach ($patients->ancs as $anc) {
                    if ($anc->labreport) {
                        $anc->labreport->delete();
                    }
                    $anc->delete();
                }
            }
            $patients->delete();
            
            return response()->json(['message' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'error']);
        }
    }

    public function deleteLabSample($id)
    {
        try {
            $sample_collection = SampleCollection::where('token', $id)->first();
            if ($sample_collection->service_for == '2') {
                $sample_collection->update([
                    'result' => '2',
                    'received_by' => null,
                    'received_by_hp_code' => null,
                    'received_date_en' => null,
                    'received_date_np' => null,
                    'sample_test_date_en' => null,
                    'sample_test_date_np' => null,
                    'sample_test_time' => null,
                    'lab_token' => null,
                    'reporting_date_en' => null,
                    'reporting_date_np' => null
                ]);
                LabTest::where('sample_token', $id)->delete();
            } else {
                if ($sample_collection->result == '3' || $sample_collection->result == '4') {
                    $sample_collection->update([
                        'result' => '9',
                        'sample_test_date_en' => null,
                        'sample_test_date_np' => null,
                        'sample_test_time' => null,
                        'reporting_date_en' => null,
                        'reporting_date_np' => null
                    ]);
                    LabTest::where('sample_token', $id)->update([
                        'sample_test_result' => '9',
                        'sample_test_date' => null,
                        'sample_test_time' => null,
                    ]);
                } elseif ($sample_collection->result == '9') {
                    $sample_collection->update([
                        'result' => '2',
                        'received_by' => null,
                        'received_by_hp_code' => null,
                        'received_date_en' => null,
                        'received_date_np' => null,
                        'sample_test_date_en' => null,
                        'sample_test_date_np' => null,
                        'sample_test_time' => null,
                        'lab_token' => null,
                        'reporting_date_en' => null,
                        'reporting_date_np' => null
                    ]);
                    LabTest::where('sample_token', $id)->delete();
                } else {
                    return response()->json(['message' => 'error']);
                }
            }
            return response()->json(['message' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'error']);
        }
    }

    public function CICTTracingList(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);

        $data = CictTracing::whereIn('hp_code', $hpCodes)->with('municipality', 'district')->latest()->advancedFilter();
        return response()->json(['collection' => $data]);
    }
}
