<?php


namespace App\Http\Controllers\Data\Api;

use App\Helpers\GetHealthpostCodes;
use App\Http\Controllers\Controller;
use App\Models\Anc;
use App\Models\Delivery;
use App\Models\Woman;
use App\Reports\FilterRequest;
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
        $woman = Woman::whereIn('hp_code', $hpCodes)->active()->with('user');
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
}