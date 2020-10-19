<?php


namespace App\Http\Controllers\Data\Api;

use App\Helpers\GetHealthpostCodes;
use App\Http\Controllers\Controller;
use App\Models\Anc;
use App\Models\Delivery;
use App\Models\LabTest;
use App\Models\Woman;
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
        $woman = Woman::whereIn('hp_code', $hpCodes)->active()->withAll();
        return response()->json([
            'collection' => $woman->advancedFilter()
        ]);
    }

    public function show($token){

        $data = Woman::withAll()->where('token', $token)->first();
        $date_array = explode("-", $data['lmp_date_en']);
        $data['lmp_date_en'] = Calendar::eng_to_nep($date_array[0], $date_array[1], $date_array[2])->getYearMonthDay();
        foreach ($data->ancs as $anc){
            $date_array = explode("-", $anc['visit_date']);
            $anc['visit_date'] = Calendar::eng_to_nep($date_array[0], $date_array[1], $date_array[2])->getYearMonthDay();
        }

        return response()->json([
            'record' => $data
        ]);
    }

    public function update(Request $request, $token) {
        $data = $request->only((new Woman)->getFillable());
        $date_array = explode("-", $data['lmp_date_en']);
        $data['lmp_date_en'] = Calendar::nep_to_eng($date_array[0], $date_array[1], $date_array[2])->getYearMonthDay();
        Woman::where('token', $token)->update($data);
        return response()->json();
    }

    public function updateAnc(Request $request, $token) {
        $data = $request->only((new Anc())->getFillable());
        $date_array = explode("-", $data['visit_date']);
        $data['visit_date'] = Calendar::nep_to_eng($date_array[0], $date_array[1], $date_array[2])->getYearMonthDay();
        Anc::where('token', $token)->update($data);
        return response()->json();
    }

    public function deleteAnc($id) {
        Anc::findOrFail($id)->delete();
        return response()->json();
    }

    public function updateDelivery(Request $request, $token) {
        $data = $request->only((new Delivery())->getFillable());
//        $date_array = explode("-", $data['visit_date']);
//        $data['visit_date'] = Calendar::nep_to_eng($date_array[0], $date_array[1], $date_array[2])->getYearMonthDay();
        Delivery::where('token', $token)->update($data);
        return response()->json();
    }

    public function deleteDelivery($id) {
        Delivery::findOrFail($id)->delete();
        return response()->json();
    }

    public function updateUser(Request $request, $token)
    {
        $data = $request->all(); 

        try{
            $validation = $request->validate([
                'username' => ['required', 'max:255',
                    Rule::unique('users')->where('role', 'woman')->ignore($data['id']),
                ],
            ]);

            if (array_key_exists('changed_password',$data) && $data['changed_password'] !== null) {
                $data['password'] = md5($data['changed_password']);
            }

            if ($data['password'] == null) {
                $data['password'] = md5(123456);
            }

            $data['role'] = 'woman';

            // User::updateOrCreate(['id', $data['id']], $data);

            if ($data['id'] !== null) {
                User::findOrFail($data['id'])->fill($data)->save();
            }else{
                User::create($data);
            }

            return response()->json(['message' => 'Record was successfully Updated', 'success' => 1]);
        }catch(\Exception $e){
            return response()->json(['message' => 'The username already exists. Please use a different username', 'success' => 0]);
        }        
    }

    public function export(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
        $woman = Woman::whereIn('hp_code', $hpCodes)->active()->withAll()->with('municipality', 'district');

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
            $data['current_hospital'] = $item['healthpost']['name'];
            $data['swab_id'] = $item['latestAnc']['token'];
            $data['lab_id'] = $item['latestAnc']['labreport']['formated_token'];
            $data['created_at'] = Carbon::parse($item['created_at'])->format('Y-m-d');
            return $data;
        })->values();

        return response()->json($formated_data);

        return response()->json($woman);
    }

    public function labExport(){
        $user = auth()->user();
        $sample_token = LabTest::where('checked_by', $user->token)->pluck('sample_token');
        $token = Anc::whereIn('token', $sample_token)->pluck('woman_token');
        $data = Woman::whereIn('token', $token)->latest()->active()->withAll()->get();

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
            $data['current_hospital'] = $item['healthpost']['name'];
            $data['swab_id'] = $item['latestAnc']['token'];
            $data['lab_id'] = $item['latestAnc']['labreport']['formated_token'];
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