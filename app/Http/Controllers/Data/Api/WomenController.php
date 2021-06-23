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

        if($request->db_switch == '2') {
            $woman = SuspectedCaseOld::active();
        } else{
            $woman = SuspectedCase::active();
        }

        if(Auth::user()->can('poe-registration')){
            $woman->where('case_type', '3');
        } else {
            $woman->where('case_type', '!=', '3');
        }

        $woman = $woman->whereIn('hp_code', $hpCodes)
            ->doesnthave('ancs')
            ->with(['province', 'district', 'municipality', 'latestAnc', 'ancs',
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

        if(Auth::user()->can('poe-registration')){
            $woman->where('case_type', '3');
        } else {
            $woman->where('case_type', '!=', '3');
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

    public function activeAntigenPendingIndex(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
 
        if($request->db_switch == '2') {
            $woman = SuspectedCaseOld::active();
        } else{
            $woman = SuspectedCase::active();
        }

        if(Auth::user()->can('poe-registration')){
            $woman->where('case_type', '3');
        } else {
            $woman->where('case_type', '!=', '3');
        }
        $woman->whereIn('hp_code', $hpCodes)
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

    // Negative

    public function passiveIndex(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
//        $token = SampleCollection::whereIn('hp_code', $hpCodes)->where('result', 4)->pluck('woman_token');
//        $woman = SuspectedCase::whereIn('token', $token)->active()->withAll();

        if($request->db_switch == '2') {
            $woman = SuspectedCaseOld::active();
        } else{
            $woman = SuspectedCase::active();
        }

        if(Auth::user()->can('poe-registration')){
            $woman->where('case_type', '3');
        } else {
            $woman->where('case_type', '!=', '3');
        }
        $woman->whereIn('hp_code', $hpCodes)
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

    public function passiveAntigenIndex(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
        
        if($request->db_switch == '2') {
            $woman = SuspectedCaseOld::active();
        } else{
            $woman = SuspectedCase::active();
        }

        if(Auth::user()->can('poe-registration')){
            $woman->where('case_type', '3');
        } else {
            $woman->where('case_type', '!=', '3');
        }
        $woman->whereIn('hp_code', $hpCodes)
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
    
    public function positiveIndex(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
//        $token = SampleCollection::whereIn('hp_code', $hpCodes)->where('result', 3)->pluck('woman_token');

            if($request->db_switch == '2') {
                $woman = SuspectedCaseOld::active();
            } else{
                $woman = SuspectedCase::active();
            }

            if(Auth::user()->can('poe-registration')){
                $woman->where('case_type', '3');
            } else {
                $woman->where('case_type', '!=', '3');
            }
            $woman->whereIn('hp_code', $hpCodes)->whereHas('ancs', function($q){
                    $q->where('service_for', '!=' , "2")->where('result', '=', 3);
                })->with(['ancs','healthpost' => function($q) {
                    $q->select('name', 'hp_code');
                }, 'latestAnc', 'district', 'municipality']);
            return response()->json([
                'collection' => $woman->advancedFilter()
            ]);
        
    }

    public function positiveAntigenIndex(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);

        if($request->db_switch == '2') {
            $woman = SuspectedCaseOld::active();
        } else{
            $woman = SuspectedCase::active();
        }

        if(Auth::user()->can('poe-registration')){
            $woman->where('case_type', '3');
        } else {
            $woman->where('case_type', '!=', '3');
        }
        $woman->whereIn('hp_code', $hpCodes)
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

    public function tracingIndex(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
//        $token = SampleCollection::whereIn('hp_code', $hpCodes)->where('result', 3)->pluck('woman_token');

        $tracing_tokens = ContactTracing::whereIn('hp_code', $hpCodes)->pluck('woman_token');

        if($request->db_switch == '2') {
            $woman = SuspectedCaseOld::active();
        } else{
            $woman = SuspectedCase::active();
        }

        $woman = $woman->whereIn('token', $tracing_tokens)->withAll();
        return response()->json([
            'collection' => $woman->advancedFilter()
        ]);
    }

    public function labReceivedIndex(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);

        if($request->db_switch == '2') {
            $woman = SuspectedCaseOld::active();
        } else{
            $woman = SuspectedCase::active();
        }

        if(Auth::user()->can('poe-registration')){
            $woman->where('case_type', '3');
        } else {
            $woman->where('case_type', '!=', '3');
        }
        $woman->whereIn('hp_code', $hpCodes)
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

    public function labReceivedAntigenIndex(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);

        if($request->db_switch == '2') {
            $woman = SuspectedCaseOld::active();
        } else{
            $woman = SuspectedCase::active();
        }

        if(Auth::user()->can('poe-registration')){
            $woman->where('case_type', '3');
        } else {
            $woman->where('case_type', '!=', '3');
        }
        $woman->whereIn('hp_code', $hpCodes)
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

    public function labAddReceivedIndex(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
        $user = auth()->user();
        $sample_token = SampleCollection::where(function($q) use ($hpCodes, $user) {
                $q->where('received_by', $user->token)
                    ->orWhereIn('received_by_hp_code', $hpCodes);
            })
            ->where('result', '9')->pluck('woman_token');

        $data = SuspectedCase::active()->whereIn('token', $sample_token)->withAll();

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
        $sample_token = SampleCollection::where(function($q) use ($hpCodes, $user) {
            $q->where('received_by', $user->token)
                ->orWhereIn('received_by_hp_code', $hpCodes);
            })
            ->where('result', '3')->pluck('woman_token');
        $data = SuspectedCase::active()->whereIn('token', $sample_token)->withAll();
        return response()->json([
            'collection' => $data->advancedFilter()
        ]);
    }

    public function labAddResultNegativeIndex(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
        $user = auth()->user();
        $sample_token = SampleCollection::where(function($q) use ($hpCodes, $user) {
            $q->where('received_by', $user->token)
                ->orWhereIn('received_by_hp_code', $hpCodes);
            })->where('result', '4')->pluck('woman_token');
        $data = SuspectedCase::active()->whereIn('token', $sample_token)->withAll();
        return response()->json([
            'collection' => $data->advancedFilter()
        ]);
    }

    public function casesRecoveredIndex(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);

        if($request->db_switch == '2') {
            $woman = SuspectedCaseOld::active();
        } else{
            $woman = SuspectedCase::active();
        }

        $woman = $woman->whereIn('hp_code', $hpCodes)->casesRecoveredList()
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

        if($request->db_switch == '2') {
            $woman = SuspectedCaseOld::active();
        } else{
            $woman = SuspectedCase::active();
        }
        
        $woman = $woman->whereIn('hp_code', $hpCodes)
            ->casesDeathList()
            ->with(['ancs','healthpost' => function($q) {
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
            $patients = SuspectedCase::with('ancs', 'latestAnc')->where('token', $id)->first();
            if($patients->latestAnc){
                if($patients->latestAnc->result == '3' || $patients->latestAnc->result == '4') {
                    if($patients->latestAnc->service_for == '2') {
                        SampleCollection::where('token', $patients->latestAnc->token)->update([
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
                        LabTest::where('sample_token', $patients->latestAnc->token)->delete();
                    } else {
                        SampleCollection::where('token', $patients->latestAnc->token)->update([
                            'result' => '9',
                            'sample_test_date_en' => null,
                            'sample_test_date_np' => null,
                            'sample_test_time' => null,
                            'reporting_date_en' => null,
                            'reporting_date_np' => null
                        ]);
                        LabTest::where('sample_token', $patients->latestAnc->token)->update([
                            'sample_test_result' => '9',
                            'sample_test_date' => null,
                            'sample_test_time' => null,
                        ]);
                    }
                }
                elseif($patients->latestAnc->result == '9') {
                    SampleCollection::where('token', $patients->latestAnc->token)->update([
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
                    LabTest::where('sample_token', $patients->latestAnc->token)->delete();
                }else {
                    LabTest::where('sample_token', $patients->latestAnc->token)->delete();
                    SampleCollection::where('woman_token', $patients->token)->delete();
                    $patients->delete();
                }
            } else {
                $patients->delete();
            }
            
            return response()->json(['message' => 'success']);
        }
        catch (\Exception $e){
            return response()->json(['message' => 'error']);
        }
    }

    public function deleteLabSuspectedCase($id){
        try{
            $patients = SuspectedCase::with('ancs', 'latestAnc')->where('token', $id)->first();
            if($patients->latestAnc){
                if($patients->latestAnc->result == '3' || $patients->latestAnc->result == '4') {
                    SampleCollection::where('token', $patients->latestAnc->token)->update([
                        'result' => '9',
                        'sample_test_date_en' => null,
                        'sample_test_date_np' => null,
                        'sample_test_time' => null,
                        'reporting_date_en' => null,
                        'reporting_date_np' => null
                    ]);
                    LabTest::where('sample_token', $patients->latestAnc->token)->update([
                        'sample_test_result' => '9',
                        'sample_test_date' => null,
                        'sample_test_time' => null,
                    ]);
                }
                elseif($patients->latestAnc->result == '9') {
                    SampleCollection::where('token', $patients->latestAnc->token)->update([
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
                    LabTest::where('sample_token', $patients->latestAnc->token)->delete();
                }else {
                    LabTest::where('sample_token', $patients->latestAnc->token)->delete();
                    SampleCollection::where('woman_token', $patients->token)->delete();
                    $patients->delete();
                }
            } else {
                $patients->delete();
            }
            
            return response()->json(['message' => 'success']);
        }
        catch (\Exception $e){
            return response()->json(['message' => 'error']);
        }
    }
}