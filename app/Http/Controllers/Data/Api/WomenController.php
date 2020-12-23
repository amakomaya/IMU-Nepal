<?php


namespace App\Http\Controllers\Data\Api;

use App\Helpers\GetHealthpostCodes;
use App\Http\Controllers\Controller;
use App\Models\SampleCollection;
use App\Models\LabTest;
use App\Models\SuspectedCase;
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
        $woman = SuspectedCase::whereIn('hp_code', $hpCodes)->active()
            ->withAll();
        return response()->json([
            'collection' => $woman->advancedFilter()
        ]);
    }

    public function activeIndex(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
        $woman = SuspectedCase::whereIn('hp_code', $hpCodes)->active()->activePatientList()->withAll();
        return response()->json([
            'collection' => $woman->advancedFilter()
        ]);
    }

    public function passiveIndex(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
        $woman = SuspectedCase::whereIn('hp_code', $hpCodes)->active()->passivePatientList()->withAll();
        return response()->json([
            'collection' => $woman->advancedFilter()
        ]);
    }
    public function positiveIndex(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
        $woman = SuspectedCase::whereIn('hp_code', $hpCodes)->active()->positivePatientList()->withAll();
        return response()->json([
            'collection' => $woman->advancedFilter()
        ]);
    }

    public function labReceivedIndex(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
        $woman = SuspectedCase::whereIn('hp_code', $hpCodes)->active()->labReceivedList()->withAll();
        return response()->json([
            'collection' => $woman->advancedFilter()
        ]);
    }

    public function labAddReceivedIndex(Request $request)
    {
        $user = auth()->user();
        $sample_token = LabTest::where('checked_by', $user->token)->pluck('sample_token');
        $token = SampleCollection::whereIn('token', $sample_token)->pluck('woman_token');
        $data = SuspectedCase::whereIn('token', $token)->active()->withAll()->labAddReceived();
        return response()->json([
            'collection' => $data->advancedFilter()
        ]);
    }

    public function labAddResultPositiveIndex(Request $request)
    {
        $user = auth()->user();
        $sample_token = LabTest::where('checked_by', $user->token)->pluck('sample_token');
        $token = SampleCollection::whereIn('token', $sample_token)->pluck('woman_token');
        $data = SuspectedCase::whereIn('token', $token)->active()->withAll()->labAddReceivedPositive();
        return response()->json([
            'collection' => $data->advancedFilter()
        ]);
    }

    public function labAddResultNegativeIndex(Request $request)
    {
        $user = auth()->user();
        $sample_token = LabTest::where('checked_by', $user->token)->pluck('sample_token');
        $token = SampleCollection::whereIn('token', $sample_token)->pluck('woman_token');
        $data = SuspectedCase::whereIn('token', $token)->active()->withAll()->labAddReceivedNegative();
        return response()->json([
            'collection' => $data->advancedFilter()
        ]);
    }

    public function casesRecoveredIndex(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
        $woman = SuspectedCase::whereIn('hp_code', $hpCodes)->active()->casesRecoveredList()->withAll();
        return response()->json([
            'collection' => $woman->advancedFilter()
        ]);
    }

    public function casesDeathIndex(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
        $woman = SuspectedCase::whereIn('hp_code', $hpCodes)->active()->casesDeathList()->withAll();
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
}