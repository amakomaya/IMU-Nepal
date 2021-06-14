<?php


namespace App\Http\Controllers\Data\Api;

use App\Helpers\GetHealthpostCodes;
use App\Http\Controllers\Controller;
use App\Models\ContactTracing;
use App\Models\Organization;
use App\Models\PaymentCase;
use App\Models\SampleCollection;
use App\Models\LabTest;
use App\Models\SuspectedCase;
use App\Models\SuspectedCaseOld;
use App\Reports\FilterRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yagiten\Nepalicalendar\Calendar;
use App\User;
use Illuminate\Validation\Rule;

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
        $woman = SuspectedCase::whereIn('hp_code', $hpCodes)->active()->doesnthave('ancs')->with(['province', 'district', 'municipality', 'latestAnc', 'ancs',
                'healthpost' => function($q) {
                    $q->select('name', 'hp_code');
                }]);
        return response()->json([
            'collection' => $woman->advancedFilter()
        ]);
    }

 // Pending
    public function activePendingIndex(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);

        if($request->db_switch == '2') {
            $woman = SuspectedCaseOld::active();
        } else{
            $woman = SuspectedCase::active();
        }
        $woman = $woman->whereIn('hp_code', $hpCodes)
            ->where(function ($query){
                $query->whereHas('ancs', function($q){
                    $q->where('service_for', '!=' ,"2")->whereIn('result', [0,2]);
                });
            })
            ->with(['province', 'district', 'municipality', 'latestAnc', 'ancs',
                'healthpost' => function($q) {
                    $q->select('name', 'hp_code');
                }]);
        return response()->json([
            'collection' => $woman->advancedFilter()
        ]);
    }

    public function activePendingIndexOld(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);

        $woman = \DB::connection('mysqldump')->table('women')->where('women.status', 1)
            ->whereIn('women.hp_code', $hpCodes)
            ->leftjoin('ancs', 'women.token', '=', 'ancs.woman_token')
            ->leftjoin('municipalities', 'women.municipality_id', 'municipalities.id')
            ->where('ancs.service_for', '!=', '2')
            ->whereIn('ancs.result', [0,2])
            ->leftjoin('healthposts', 'women.hp_code', '=', 'healthposts.hp_code')
            ->leftjoin('lab_tests', 'ancs.token', '=', 'lab_tests.sample_token')
            ->select(
                'women.*',
                \DB::raw("count(ancs.id) as ancs_count"),
                \DB::raw('(select ancs.token from ancs where women.token = ancs.woman_token order by ancs.id desc limit 1) as ancs_token'),
                'ancs.created_at as ancs_created_at',
                'ancs.updated_at as ancs_updated_at',
                'ancs.result as ancs_result',
                'ancs.service_for as ancs_service_for',
                'lab_tests.token as lab_tests_token',
                'healthposts.name as healthpost_name',
                'municipalities.municipality_name as municipality_name',
                'municipalities.district_name as district_name'
            )
            ->groupBy('women.id')
            ->orderBy(
                request('order_column', 'created_at'),
                request('order_direction', 'desc')
            )
            ->paginate(request('limit', 100));
        return response()->json([
            'collection' => $woman
        ]);
    }

    public function activeAntigenPendingIndex(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
        
        $woman = SuspectedCase::active()
            ->whereIn('hp_code', $hpCodes)
            ->where(function ($query){
                $query->whereHas('ancs', function($q){
                    $q->where('service_for', "2")->whereIn('result', [0,2]);
                });
            })
            ->with(['province', 'district', 'municipality', 'latestAnc', 'ancs',
                'healthpost' => function($q) {
                    $q->select('name', 'hp_code');
                }]);
        return response()->json([
            'collection' => $woman->advancedFilter()
        ]);
    }

    public function activeAntigenPendingIndexOld(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);

        $woman = \DB::connection('mysqldump')->table('women')->where('women.status', 1)
            ->whereIn('women.hp_code', $hpCodes)
            ->leftjoin('ancs', 'women.token', '=', 'ancs.woman_token')
            ->leftjoin('municipalities', 'women.municipality_id', 'municipalities.id')
            ->where('ancs.service_for', '2')
            ->whereIn('ancs.result', [0,2])
            ->leftjoin('healthposts', 'women.hp_code', '=', 'healthposts.hp_code')
            ->leftjoin('lab_tests', 'ancs.token', '=', 'lab_tests.sample_token')
            ->select(
                'women.*',
                \DB::raw("count(ancs.id) as ancs_count"),
                \DB::raw('(select ancs.token from ancs where women.token = ancs.woman_token order by ancs.id desc limit 1) as ancs_token'),
                'ancs.created_at as ancs_created_at',
                'ancs.updated_at as ancs_updated_at',
                'ancs.result as ancs_result',
                'ancs.service_for as ancs_service_for',
                'lab_tests.token as lab_tests_token',
                'healthposts.name as healthpost_name',
                'municipalities.municipality_name as municipality_name',
                'municipalities.district_name as district_name'
            )
            ->groupBy('women.id')
            ->orderBy(
                request('order_column', 'created_at'),
                request('order_direction', 'desc')
            )
            ->paginate(request('limit', 100));
        return response()->json([
            'collection' => $woman
        ]);
    }

    // Negative

    public function passiveIndex(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
//        $token = SampleCollection::whereIn('hp_code', $hpCodes)->where('result', 4)->pluck('woman_token');
//        $woman = SuspectedCase::whereIn('token', $token)->active()->withAll();

        $woman = SuspectedCase::active()
            ->whereIn('hp_code', $hpCodes)
            ->whereHas('ancs', function($q){
                $q->where('service_for', '!=' , "2")->where('result', '=', 4);
            })
            ->with(['province', 'district', 'municipality', 'latestAnc', 'ancs',
                'healthpost' => function($q) {
                    $q->select('name', 'hp_code');
                }]);

        return response()->json([
            'collection' => $woman->advancedFilter()
        ]);
    }

    public function passiveIndexOld(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);

        $woman = \DB::connection('mysqldump')->table('women')->where('women.status', 1)
            ->whereIn('women.hp_code', $hpCodes)
            ->leftjoin('ancs', 'women.token', '=', 'ancs.woman_token')
            ->leftjoin('municipalities', 'women.municipality_id', 'municipalities.id')
            ->where('ancs.service_for', '!=', '2')
            ->where('ancs.result', 4)
            ->leftjoin('healthposts', 'women.hp_code', '=', 'healthposts.hp_code')
            ->leftjoin('lab_tests', 'ancs.token', '=', 'lab_tests.sample_token')
            ->select(
                'women.*',
                \DB::raw("count(ancs.id) as ancs_count"),
                \DB::raw('(select ancs.token from ancs where women.token = ancs.woman_token order by ancs.id desc limit 1) as ancs_token'),
                'ancs.created_at as ancs_created_at',
                'ancs.updated_at as ancs_updated_at',
                'ancs.result as ancs_result',
                'lab_tests.token as lab_tests_token',
                'healthposts.name as healthpost_name',
                'municipalities.municipality_name as municipality_name',
                'municipalities.district_name as district_name'
            )
            ->groupBy('women.id')
            ->orderBy(
                request('order_column', 'created_at'),
                request('order_direction', 'desc')
            )
            ->paginate(request('limit', 100));
        return response()->json([
            'collection' => $woman
        ]);
    }

    public function passiveAntigenIndex(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
        
        $woman = SuspectedCase::active()
            ->whereIn('hp_code', $hpCodes)
            ->whereHas('ancs', function($q){
                $q->where('service_for', "2")->where('result', '=', 4);
            })
            ->with(['province', 'district', 'municipality', 'latestAnc', 'ancs',
                'healthpost' => function($q) {
                    $q->select('name', 'hp_code');
                }]);

        return response()->json([
            'collection' => $woman->advancedFilter()
        ]);
    }

    public function passiveAntigenIndexOld(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);

        $woman = \DB::connection('mysqldump')->table('women')->where('women.status', 1)
            ->whereIn('women.hp_code', $hpCodes)
            ->leftjoin('ancs', 'women.token', '=', 'ancs.woman_token')
            ->leftjoin('municipalities', 'women.municipality_id', 'municipalities.id')
            ->where('ancs.service_for', '2')
            ->where('ancs.result', 4)
            ->leftjoin('healthposts', 'women.hp_code', '=', 'healthposts.hp_code')
            ->leftjoin('lab_tests', 'ancs.token', '=', 'lab_tests.sample_token')
            ->select(
                'women.*',
                \DB::raw("count(ancs.id) as ancs_count"),
                \DB::raw('(select ancs.token from ancs where women.token = ancs.woman_token order by ancs.id desc limit 1) as ancs_token'),
                'ancs.created_at as ancs_created_at',
                'ancs.updated_at as ancs_updated_at',
                'ancs.result as ancs_result',
                'lab_tests.token as lab_tests_token',
                'healthposts.name as healthpost_name',
                'municipalities.municipality_name as municipality_name',
                'municipalities.district_name as district_name'
            )
            ->groupBy('women.id')
            ->orderBy(
                request('order_column', 'created_at'),
                request('order_direction', 'desc')
            )
            ->paginate(request('limit', 100));
        return response()->json([
            'collection' => $woman
        ]);
    }

    public function positiveIndex(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
//        $token = SampleCollection::whereIn('hp_code', $hpCodes)->where('result', 3)->pluck('woman_token');

            $woman = SuspectedCase::active()
                ->whereIn('hp_code', $hpCodes)->whereHas('ancs', function($q){
                    $q->where('service_for', '!=' , "2")->where('result', '=', 3);
                })->with(['ancs','healthpost' => function($q) {
                    $q->select('name', 'hp_code');
                }, 'latestAnc', 'district', 'municipality']);
            return response()->json([
                'collection' => $woman->advancedFilter()
            ]);
        
    }

    public function positiveIndexOld(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
//        $token = SampleCollection::whereIn('hp_code', $hpCodes)->where('result', 3)->pluck('woman_token');

        $woman = \DB::connection('mysqldump')->table('women')->where('women.status', 1)
            ->whereIn('women.hp_code', $hpCodes)
            ->leftjoin('ancs', 'women.token', '=', 'ancs.woman_token')
            ->leftjoin('municipalities', 'women.municipality_id', 'municipalities.id')
            ->where('ancs.service_for', '!=', '2')
            ->where('ancs.result', 3)
            ->leftjoin('healthposts', 'women.hp_code', '=', 'healthposts.hp_code')
            ->leftjoin('lab_tests', 'ancs.token', '=', 'lab_tests.sample_token')
            ->select(
                'women.*',
                \DB::raw("count(ancs.id) as ancs_count"),
                \DB::raw('(select ancs.token from ancs where women.token = ancs.woman_token order by ancs.id desc limit 1) as ancs_token'),
                'ancs.created_at as ancs_created_at',
                'ancs.updated_at as ancs_updated_at',
                'ancs.result as ancs_result',
                'lab_tests.token as lab_tests_token',
                'healthposts.name as healthpost_name',
                'municipalities.municipality_name as municipality_name',
                'municipalities.district_name as district_name'
            )
            ->groupBy('women.id')
            ->orderBy(
                request('order_column', 'created_at'),
                request('order_direction', 'desc')
            )
            ->paginate(request('limit', 100));
        return response()->json([
            'collection' => $woman
        ]);
    }

    public function positiveAntigenIndex(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);

        $woman = SuspectedCase::active()
            ->whereIn('hp_code', $hpCodes)
            ->where(function ($query){
                $query->whereHas('ancs', function($q){
                    $q->where('service_for', "2")->where('result', 3);
                });
            })
            ->with(['province', 'district', 'municipality', 'latestAnc', 'ancs',
                'healthpost' => function($q) {
                    $q->select('name', 'hp_code');
                }]);
        return response()->json([
            'collection' => $woman->advancedFilter()
        ]);
    }

    public function positiveAntigenIndexOld(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);

        $woman = \DB::connection('mysqldump')->table('women')->where('women.status', 1)
            ->whereIn('women.hp_code', $hpCodes)
            ->leftjoin('ancs', 'women.token', '=', 'ancs.woman_token')
            ->leftjoin('municipalities', 'women.municipality_id', 'municipalities.id')
            ->where('ancs.service_for', '2')
            ->where('ancs.result', 3)
            ->leftjoin('healthposts', 'women.hp_code', '=', 'healthposts.hp_code')
            ->leftjoin('lab_tests', 'ancs.token', '=', 'lab_tests.sample_token')
            ->select(
                'women.*',
                \DB::raw("count(ancs.id) as ancs_count"),
                \DB::raw('(select ancs.token from ancs where women.token = ancs.woman_token order by ancs.id desc limit 1) as ancs_token'),
                'ancs.created_at as ancs_created_at',
                'ancs.updated_at as ancs_updated_at',
                'ancs.result as ancs_result',
                'lab_tests.token as lab_tests_token',
                'healthposts.name as healthpost_name',
                'municipalities.municipality_name as municipality_name',
                'municipalities.district_name as district_name'
            )
            ->groupBy('women.id')
            ->orderBy(
                request('order_column', 'created_at'),
                request('order_direction', 'desc')
            )
            ->paginate(request('limit', 100));
        return response()->json([
            'collection' => $woman
        ]);
    }

    public function tracingIndex(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
//        $token = SampleCollection::whereIn('hp_code', $hpCodes)->where('result', 3)->pluck('woman_token');

        $tracing_tokens = ContactTracing::whereIn('hp_code', $hpCodes)->pluck('woman_token');

        $woman = SuspectedCase::whereIn('token', $tracing_tokens)->active()->withAll();
        return response()->json([
            'collection' => $woman->advancedFilter()
        ]);
    }

    public function labReceivedIndex(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);

        $woman = SuspectedCase::active()
            ->whereIn('hp_code', $hpCodes)
            ->whereHas('ancs', function($q){
                $q->where('service_for', '!=' , "2")->where('result', '=', 9);
            })->with(['ancs','healthpost' => function($q) {
                $q->select('name', 'hp_code');
            }, 'latestAnc', 'district', 'municipality']);
//        $token = SampleCollection::whereIn('hp_code', $hpCodes)->where('result', 9)->pluck('woman_token');
//        $woman = SuspectedCase::whereIn('token', $token)->active()->withAll();
        return response()->json([
            'collection' => $woman->advancedFilter()
        ]);
    }

    public function labReceivedIndexOld(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);

        $woman = \DB::connection('mysqldump')->table('women')->where('women.status', 1)
            ->whereIn('women.hp_code', $hpCodes)
            ->leftjoin('ancs', 'women.token', '=', 'ancs.woman_token')
            ->leftjoin('municipalities', 'women.municipality_id', 'municipalities.id')
            ->where('ancs.service_for', '!=', '2')
            ->where('ancs.result', 9)
            ->leftjoin('healthposts', 'women.hp_code', '=', 'healthposts.hp_code')
            ->leftjoin('lab_tests', 'ancs.token', '=', 'lab_tests.sample_token')
            ->select(
                'women.*',
                \DB::raw("count(ancs.id) as ancs_count"),
                \DB::raw('(select ancs.token from ancs where women.token = ancs.woman_token order by ancs.id desc limit 1) as ancs_token'),
                'ancs.created_at as ancs_created_at',
                'ancs.updated_at as ancs_updated_at',
                'ancs.result as ancs_result',
                'lab_tests.token as lab_tests_token',
                'healthposts.name as healthpost_name',
                'municipalities.municipality_name as municipality_name',
                'municipalities.district_name as district_name'
            )
            ->groupBy('women.id')
            ->orderBy(
                request('order_column', 'created_at'),
                request('order_direction', 'desc')
            )
            ->paginate(request('limit', 100));
        return response()->json([
            'collection' => $woman
        ]);
    }

    public function labReceivedAntigenIndex(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);

        $woman = SuspectedCase::active()
            ->whereIn('hp_code', $hpCodes)
            ->where(function ($query){
                $query->whereHas('ancs', function($q){
                    $q->where('service_for', "2")->where('result', 9);
                });
            })
            ->with(['province', 'district', 'municipality', 'latestAnc', 'ancs',
                'healthpost' => function($q) {
                    $q->select('name', 'hp_code');
                }]);
        return response()->json([
            'collection' => $woman->advancedFilter()
        ]);
    }

    public function labReceivedAntigenIndexOld(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);

        $woman = \DB::connection('mysqldump')->table('women')->where('women.status', 1)
            ->whereIn('women.hp_code', $hpCodes)
            ->leftjoin('ancs', 'women.token', '=', 'ancs.woman_token')
            ->leftjoin('municipalities', 'women.municipality_id', 'municipalities.id')
            ->where('ancs.service_for', '2')
            ->where('ancs.result', 9)
            ->leftjoin('healthposts', 'women.hp_code', '=', 'healthposts.hp_code')
            ->leftjoin('lab_tests', 'ancs.token', '=', 'lab_tests.sample_token')
            ->select(
                'women.*',
                \DB::raw("count(ancs.id) as ancs_count"),
                \DB::raw('(select ancs.token from ancs where women.token = ancs.woman_token order by ancs.id desc limit 1) as ancs_token'),
                'ancs.created_at as ancs_created_at',
                'ancs.updated_at as ancs_updated_at',
                'ancs.result as ancs_result',
                'lab_tests.token as lab_tests_token',
                'healthposts.name as healthpost_name',
                'municipalities.municipality_name as municipality_name',
                'municipalities.district_name as district_name'
            )
            ->groupBy('women.id')
            ->orderBy(
                request('order_column', 'created_at'),
                request('order_direction', 'desc')
            )
            ->paginate(request('limit', 100));
        return response()->json([
            'collection' => $woman
        ]);
    }

    public function labAddReceivedIndex(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
        $user = auth()->user();
        $sample_token = LabTest::where(function($q) use ($hpCodes, $user) {
                $q->where('checked_by', $user->token)
                    ->orWhereIn('hp_code', $hpCodes);
            })->
            where('sample_test_result', '9')->pluck('sample_token');

        $data = SuspectedCase::active()->whereHas('ancs', function($q) use ($sample_token) {
            $q->whereIn('token', $sample_token);
        })->withAll();

//        $token = SampleCollection::whereIn('token', $sample_token)->pluck('woman_token');
//        $data = SuspectedCase::whereIn('token', $token)->active()->withAll();
        return response()->json([
            'collection' => $data->advancedFilter()
        ]);
    }

    public function labAddResultPositiveIndex(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
        $user = auth()->user();
        $sample_token = LabTest::where(function($q) use ($hpCodes, $user) {
        $q->where('checked_by', $user->token)
            ->orWhereIn('hp_code', $hpCodes);
        })->where('sample_test_result', '3')->pluck('sample_token');
        $data = SuspectedCase::active()->whereHas('ancs', function($q) use ($sample_token) {
            $q->whereIn('token', $sample_token);
        })->withAll();
        return response()->json([
            'collection' => $data->advancedFilter()
        ]);
    }

    public function labAddResultNegativeIndex(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
        $user = auth()->user();
        $sample_token = LabTest::where(function($q) use ($hpCodes, $user) {
            $q->where('checked_by', $user->token)
                ->orWhereIn('hp_code', $hpCodes);
        })->where('sample_test_result', '4')->pluck('sample_token');
        $data = SuspectedCase::active()->whereHas('ancs', function($q) use ($sample_token) {
            $q->whereIn('token', $sample_token);
        })->withAll();
        return response()->json([
            'collection' => $data->advancedFilter()
        ]);
    }

    public function casesRecoveredIndex(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
        $woman = SuspectedCase::whereIn('hp_code', $hpCodes)->active()->casesRecoveredList()
        ->with(['ancs','healthpost' => function($q) {
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
        $woman = SuspectedCase::whereIn('hp_code', $hpCodes)->active()->casesDeathList()->with(['ancs','healthpost' => function($q) {
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
            ->whereNotIn('hp_code', $hp_codes)->has('ancs')
            ->active()->withAll();
        return response()->json([
            'collection' => $woman->advancedFilter()
        ]);
    }

    public function show($token){
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

    public function labExport(){
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
    private function ageUnitCheck($data){
        switch($data){
            case '1':
                return 'Months';
            case '2':
                return 'Days';
            default:
                return 'Years';
        }
    }

    public function casesPaymentIndex(Request $request){
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);

        $data = PaymentCase::whereIn('hp_code', $hpCodes)->whereNull('is_death')->latest()->advancedFilter();
        return response()->json(['collection' => $data]);
    }

    public function casesPaymentDischargeIndex(Request $request){
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);

        $data = PaymentCase::whereIn('hp_code', $hpCodes)->where('is_death', 1)->latest()->advancedFilter();
        return response()->json(['collection' => $data]);
    }

    public function casesPaymentDeathIndex(Request $request){
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);

        $data = PaymentCase::whereIn('hp_code', $hpCodes)->where('is_death', 2)->latest()->advancedFilter();
        return response()->json(['collection' => $data]);
    }

    public function deleteSuspectedCase($id){
        try{
            $patients = SuspectedCase::where('token', $id)->first();
            $ancs = SampleCollection::where('woman_token', $id)->get();
            
            foreach($ancs as $anc) {
                LabTest::where('sample_token', $anc->token)->delete();
                $anc->delete();
            }
            $patients->delete();

            return response()->json(['message' => 'success']);
        }
        catch (\Exception $e){
            return response()->json(['message' => 'error']);
        }
    }
}