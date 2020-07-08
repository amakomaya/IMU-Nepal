<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Woman;
use App\User;
use Illuminate\Support\Facades\DB;
use Validator;
use Yagiten\Nepalicalendar\Calendar;
use App\Mail\SendSelfEvaluationMail;
use Mail;
use Illuminate\Support\Arr;

class WomanController extends Controller
{
    public $msg = array();

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $hpCode = $request->hp_code;
        $women = DB::table('women')
            ->where([['hp_code', $hpCode],['status', 1]])
            ->where('deleted_at', null)
            ->whereDate('lmp_date_en', '>', Carbon::now()->subYear())
            ->get();
        return response()->json($women);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $json = json_decode($request->getContent(), true);
        $token=array();
        foreach ($json as $data) {
            $Woman = new Woman;
            foreach ($data as $key => $record) {
                $Woman->$key = $record;
                if($key=="token"){
                    $token[]['token'] = $record;
                }
            }
            $Woman->save();
        }
        return response()->json($token);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $token = $request->token;
        $model = DB::table('users')
                      ->select('women.*', 'users.username as username', 'users.role as role', 'users.password as password', 'users.email as email')
                      ->join('women','users.token','=','women.token')
                      ->where('users.token', $token)
                      ->first();

        if(count($this->msg)>0){
            return response()->json($this->msg);
        }
        return response()->json($model);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $json = json_decode($request->getContent(), true);
        $token=array();
        foreach ($json as $data) {
            $Woman = $this->findModelWoman($data['token']);
            if(count($this->msg)>0){
                return response()->json($this->msg);
            }
            if($data['updated_at']>=$Woman->updated_at){
                foreach ($data as $key => $record) {
                    $Woman->$key = $record;
                    if($key=="token"){
                        $token[]['token'] = $record;
                    }
                }
                $Woman->save();
            }
        }
        return response()->json($token);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function login(Request $request)
    {
        $json = json_decode($request->getContent(), true);
        foreach ($json as $data) {
            $username = $data['username'];
            $password = $data['password'];
        }
        $user = User::where([
                        ['username', $username],
                        ['password', md5($password)],
                        ['role', 'woman']
                    ])
                    ->get()
                    ->first();
        if(count($user)>0){
            $woman = Woman::where('token', $user->token)->get()->first();
            $userInfo = ['token'=>$user->token,'name'=>$woman->name,'age'=>$woman->age,'mobile'=>$woman->mobile,'email'=>$user->email,'district_id'=>$woman->district_id,'municipality_id'=>$woman->municipality_id,'ward'=>$woman->ward,'tole'=>$woman->tole,'lmp_date_en'=>$woman->lmp_date_en,'role'=>$user->role,'hp_code'=>$woman->hp_code,'fchv_code'=>$woman->fchv_code];
            $response = ['status'=>'1','message'=>'Logged in Successfully', 'data'=>$userInfo];
            return response()->json($response);
        }else
        {
            $response = [ 'status'=>'0','message'=>'Invalid Username or Password'];
            return response()->json($response);
        }
    }

    public function womanRregistrationStore(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $object = (Object) $data;

        $check_username = User::where('role','woman')->where('username', $object->username)->count();
        $check_username_woman_registration = \App\Models\Woman\WomanRegisterApp::where('username', $object->username)->count();

        if ($check_username >= 1 OR $check_username_woman_registration >= 1) {
            $response = ['message'=>'Username already taken. Please try another.'];
            return response()->json($response, 422); 
        }
        $data['token'] = 'guest-'.md5(uniqid(rand(), true));
        $response_data = [
            'token' => $data['token'],
            'name' => $object->name,
            'age' => $object->age,        
            'height' => '',        
            'district_id' => $object->district_id,        
            'municipality_id' => $object->municipality_id,        
            'ward' => '',        
            'tole' => $object->tole,        
            'phone' => $object->phone,        
            'bloodGroup' => '',        
            'husband_name' => '',
            'lmp_date_en' => $object->lmp_date_en,
            'lmp_date_np' => $object->lmp_date_np,
            'healthpost_name' => '',
            'hp_district' => 0,
            'hp_municipality' => 0,
            'hp_ward' => 0,
            'chronic_illness' => '',
            'current_healthpost' => '',
            'no_of_pregnant_before' => '',
            'mool_darta_no' => '',
            'sewa_darta_no' => '',
            'orc_darta_no' => '',
            'health_worker_full_name' => '',                            
            'health_worker_post' => '',                            
            'health_worker_phone' => '',   
        ];
        $data['password'] = md5($object->password);
        \App\Models\Woman\WomanRegisterApp::create($data);
        $response = ['message'=>'Registered Successfully.', 'user' => $response_data];
        return response()->json($response, 200); 
    }

    public function womanUpdate(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $womanInfo = new \App\Models\Woman\WomanInfoForContainApp();
        $msg = $womanInfo->updateWomanInfo($data);
        return response()->json($msg, 200);
    }

    public function womanLogin(Request $request)
    {
        $username = $request->username;
    	$password = $request->password;
        $user = User::where([
                        ['username', $username],
                        ['password', md5($password)],
                        ['role', 'woman']
                    ])
                    ->get()
                    ->first();
        $guest_user = \App\Models\Woman\WomanRegisterApp::where([
                            ['username', $username],
                            ['password', md5($password)]
                        ])->get()->first();
        if(count($user)>0 || count($guest_user)>0){
            $womanInfo = new \App\Models\Woman\WomanInfoForContainApp();
            $token = isset($user) ? $user->token : $guest_user->token;
            $user = $womanInfo->getGeneralInfo($token);
            $response = ['message'=>'Logged in Successfully', 
                                'user'=>$user,
                        ];
            return response()->json($response, 200);
        }
        $response = ['message'=>'Invalid Username or Password'];
        return response()->json($response, 401);
    }

    public function womanQRLogin(Request $request)
    {
        $type = $request->type;
        $token = $request->token;

        if ($type == 1) {
            try {
                $womanInfo = new \App\Models\Woman\WomanInfoForContainApp();
                $token = $token;
                $user = $womanInfo->getGeneralInfo($token);
                $response = ['message'=>'Logged in Successfully', 
                                'user'=>$user,
                        ];
            return response()->json($response, 200);
            } catch (\Exception $e) {
                $response = ['message'=>'Invalid QR Code'];
                return response()->json($response, 401);

            }
        }

        $response = ['message'=>'Invalid QR Code'];
        return response()->json($response, 401);
       
    }

    public function womanAnc(Request $request, $token)
    {
        $womanInfo = new \App\Models\Woman\WomanInfoForContainApp();
        $response = $womanInfo->getANCInfo($token);
        return response()->json($response, 200);
    }

    public function womanVaccination(Request $request, $token)
    {
        $womanInfo = new \App\Models\Woman\WomanInfoForContainApp();
        $response = $womanInfo->getWomanVaccinationInfo($token);
        return response()->json($response, 200);
    }

    public function womanDelivery(Request $request, $token)
    {
        $womanInfo = new \App\Models\Woman\WomanInfoForContainApp();
        $response = $womanInfo->getDeliveryInfo($token);
        return response()->json($response, 200);
    }

    public function womanPnc(Request $request, $token)
    {
        $womanInfo = new \App\Models\Woman\WomanInfoForContainApp();
        $response = $womanInfo->getPncInfo($token);
        return response()->json($response, 200);
    }

    public function womanLabTest(Request $request, $token)
    {
        $womanInfo = new \App\Models\Woman\WomanInfoForContainApp();
        $response = $womanInfo->getLabTestInfo($token);
        return response()->json($response, 200);
    }


    public function babyDetails(Request $request, $token)
    {
        $womanInfo = new \App\Models\Woman\WomanInfoForContainApp();
        $response = $womanInfo->getBabyDetails($token);
        return response()->json($response, 200);
    }

    public function babyVaccination(Request $request, $token)
    {
        $womanInfo = new \App\Models\Woman\WomanInfoForContainApp();
        $response = $womanInfo->babyVaccination($token);
        return response()->json($response, 200);
    }

    public function babyWeight(Request $request, $token)
    {
        $womanInfo = new \App\Models\Woman\WomanInfoForContainApp();
        $response = $womanInfo->babyWeight($token);
        return response()->json($response, 200);
    }

    public function babyAefi(Request $request, $token)
    {
        $womanInfo = new \App\Models\Woman\WomanInfoForContainApp();
        $response = $womanInfo->babyAefi($token);
        return response()->json($response, 200);
    }

    public function findModelWoman($token){
        if (($model = Woman::where('token', $token)->get()->first()) !== null) {
            return $model;
        } else {
            $this->msg = ['name'=>'Not Found', 'message'=>'The requested page doesn\'t exist', 'status'=>'404'];
            return $this;
        }
    }

    public function registrationWoman(Request $request){
        $input = json_decode($request->getContent(), true);

        $rules = [
            'name' => 'required|min:2|max:191',
            'age' => 'required|numeric|min:10|max:99',
            'lmp_date_en' => 'required|date',
            'email' => 'nullable|email',
            'phone' => 'required|min:5',
            'address' => 'required|min:2',
            'ward_no' => 'required|min:1|max:35'
        ];

        $message = [
            'required' => 'The :attribute field is required.',
            'min' => 'The :attribute must have minimum :min value',
            'numeric' => 'The :attribute must be valid integer',
            'max' => 'The :attribute must have maximum :max value',
            'date' => 'The :attribute must be date format. i.e ("YYYY-mm-dd")'
        ];

        $validator = Validator::make($input, $rules, $message); 

        $errors = [];
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json(['status' => 'error','errors' => $errors, 'data' => $input]);
        }

        $check_username = User::where('role','woman')->where('username', $input['phone'])->count();
        $check_username_woman_registration = \App\Models\Woman\WomanRegisterApp::where('username', $input['phone'])->count();

        if ($check_username >= 1 OR $check_username_woman_registration >= 1) {
            $errors['phone'] = ['Invalid phone or Phone Number already exists. Please try with another phone.'];
        }

        if (!empty($errors)) {
            return response()->json(['status' => 'error','errors' => $errors, 'data' => $input]);
        }

        $input['token'] = 'guest-api-'.md5(uniqid(rand(), true));
        $input['username'] = $input['phone'];
        $input['password'] = md5('123456');
        $input['tole'] = $input['address'];
        $lmp_date_explode = explode('-', $input['lmp_date_en']);
        $input['status'] = 2;
        $input['lmp_date_np'] = Calendar::eng_to_nep($lmp_date_explode[0], $lmp_date_explode[1], $lmp_date_explode[2])->getYearMonthDayEngToNep();
        $input['mis_data'] = json_encode($input);

        try {
            \App\Models\Woman\WomanRegisterApp::create($input);
            return response()->json(['status' => 'success','errors' => $errors, 'data' => collect($input)->only(['name', 'age', 'lmp_date_en', 'email', 'phone','address','ward_no'])->toArray()]);
        } catch (\Exception $e) {
            $errors['any'] = ['Something went wrong. Please try again.'];
            return response()->json(['status' => 'error','errors' => $errors, 'data' => collect($input)->only(['name', 'age', 'lmp_date_en', 'email', 'phone','address','ward_no'])->toArray()]);
        }
    }

    public function WomanSurvey(Request $request)
    {
        $data = $request->json()->all();
        foreach ($data as $value) {
            $record = json_encode($value);
            \App\Models\ContentApp\WomanSurvey::create(['data' => $record ]);
            try{
                $old_data = $value['baby_movement'] ?? '';
                if (!empty($old_data)) {
                    $arr_no_danger =  Arr::pull($value, 'baby_movement');
                }
                if (in_array(1, Arr::flatten($value), true) or $arr_no_danger == 0) {
                    if (starts_with($value['women_token'], 'guest')) {
                      $woman = \App\Models\Woman\WomanRegisterApp::where('token', $value['women_token'])->get()->first();
                      $woman['ward'] = $woman['ward_no'];
                    }else{
                        $woman = \App\Models\Woman::where('token', $value['women_token'])->get()->first();
                    }
                    $value['baby_movement'] = $old_data;
                    if($woman){
                        Mail::to("amakomaya2020@gmail.com")
                        ->send(new SendSelfEvaluationMail($value, $woman));
                    }
                }
            } catch (\Exception $e){
            }
        }
        return response()->json(['message' => 'Data Sussessfully Sync']);
        }
}