<?php

namespace App\Http\Controllers\Api\v3;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\BabyDetail;
use App\Models\HealthWorker;

class ContentAppController extends Controller
{
    public function login(Request $request)
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
                        ])->where('status', 1)->get()->first();
        if(count($user)>0 || count($guest_user)>0){
            $womanInfo = new \App\Models\Woman\WomanInfoForContainApp();
            $token = isset($user) ? $user->token : $guest_user->token;
            $user = $womanInfo->getGeneralInfo($token);

            $user['register_as'] = $guest_user->register_as ?? 'mom';
            $response = [
            		'message'=>'Logged in Successfully',   
                    'user'=>$user
                ];
            return response()->json($response, 200);
        }
        $response = ['message'=>'Invalid Username or Password'];
        return response()->json($response, 401);
    }

    public function qrlogin(Request $request)
    {
        $type = $request->type;
        $token = $request->token;

        if ($type == 1) {
            try {
                $user = $this->getGeneralInfo($token);
                $user['register_as'] = $user->register_as ?? 'mom';
                $response = ['message'=>'Logged in Successfully', 
            					'user'=>$user
                        ];
            return response()->json($response, 200);
            } catch (\Exception $e) {
                $response = ['message'=>'Invalid QR Code'];
                return response()->json($response, 401);

            }
        }

        if ($type == 2) {
            try {

                $guest_user = \App\Models\Woman\WomanRegisterApp::where('register_as', 'baby')->where('token', $token)->get()->first();

                if ($guest_user) {
                    $user = [ 
                        'token' => $guest_user->token,
                        'name' => $guest_user->name,
                        'no_of_pregnant_before' => '',
                        'age' => $guest_user->age,        
                        'height' => $guest_user->height,        
                        'district_id' => $guest_user->district_id,        
                        'municipality_id' => $guest_user->municipality_id,        
                        'ward' => $guest_user->ward ?? '',        
                        'tole' => $guest_user->tole,        
                        'phone' => $guest_user->phone,        
                        'bloodGroup' => '',        
                        'husband_name' => '',
                        'lmp_date_en' => $guest_user->lmp_date_en,
                        'lmp_date_np' => $guest_user->lmp_date_np,
                        'hp_code' => '',
                        'healthpost_name' => '',
                        'hp_district' => 0,
                        'hp_municipality' => 0,
                        'hp_ward' => 0,
                        'chronic_illness' => '',
                        'current_healthpost' => '',
                        'mool_darta_no' => '',
                        'sewa_darta_no' => '',
                        'orc_darta_no' => '',
                        'health_worker_full_name' =>'',   
                        'health_worker_post' => '',                            
                        'health_worker_phone' => '',   
                    ];

                    $user['register_as'] = 'baby';
                    $response = ['message'=>'Logged in Successfully', 
                                'user'=>$user
                        ];
                    return response()->json($response, 200);
                }
                
                
                    $baby_detail = BabyDetail::where('token', $token)->first();

                    $user = [ 
                        'token' => $baby_detail->token,
                        'name' => $baby_detail->baby_name,
                        'no_of_pregnant_before' => '',
                        'age' => 0,        
                        'height' => '',        
                        'district_id' => 0,        
                        'municipality_id' => 0,        
                        'ward' => $baby_detail->ward_no ?? 0,        
                        'tole' => $baby_detail->tole,        
                        'phone' => $baby_detail->contact_no,        
                        'bloodGroup' => '',        
                        'husband_name' => '',
                        'lmp_date_en' => $baby_detail->dob_en,
                        'lmp_date_np' => $baby_detail->dob_np,
                        'hp_code' => $baby_detail->hp_code,
                        'healthpost_name' => '',
                        'hp_district' => 0,
                        'hp_municipality' => 0,
                        'hp_ward' => 0,
                        'chronic_illness' => '',
                        'current_healthpost' => '',
                        'mool_darta_no' => '',
                        'sewa_darta_no' => '',
                        'orc_darta_no' => '',
                        'health_worker_full_name' =>'',   
                        'health_worker_post' => '',                            
                        'health_worker_phone' => '',   
                    ];
                $user['register_as'] = 'baby';
                $response = ['message'=>'Logged in Successfully', 
            					'user'=>$user
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

    public function register(Request $request)
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
            'register_as' =>  $object->register_as  
        ];
        $data['password'] = md5($object->password);
        \App\Models\Woman\WomanRegisterApp::create($data);
        $response = ['message'=>'Registered Successfully.', 'user' => $response_data];
        return response()->json($response, 200); 
    }

    public function getGeneralInfo($token){

        $woman = \App\Models\Woman::where('token', $token)->get()->first();

        $guest_user = \App\Models\Woman\WomanRegisterApp::where('token', $token)->where('register_as', 'mom')->where('status', 1)->get()->first();

        $woman_token =  $woman->token ?? $guest_user->token;
        $full_name = $woman->name ?? $guest_user->name;
        $age = $woman->age ?? $guest_user->age;
        try {
            $no_of_pregnant_before = isset($guest_user->mis_data) ? json_decode($guest_user->mis_data)->no_of_pregnant_before : '';
        } catch (\Exception $e) {
            $no_of_pregnant_before = 0;
        }
        $height = $woman->height ?? '';
        $district = $woman->district_id ?? $guest_user->district_id;
        $municipality = $woman->municipality_id ?? $guest_user->municipality_id;
        $ward = $woman->ward ?? $guest_user->ward_no;
        $tole = $woman->tole ?? $guest_user->tole;
        $phone = $woman->phone ?? $guest_user->phone;
        $bloodGroup = isset($woman) ? $woman->getBloodGroup($woman->blood_group) : '';
        $husband_name = $woman->husband_name ?? '';
        $lmp_date_en = $woman->lmp_date_en ?? $guest_user->lmp_date_en;
        $lmp_date_np = isset($woman) ? $woman->getLMPNP($woman->lmp_date_en) : $guest_user->lmp_date_en;
        $hp_code = isset($woman) ? $woman->hp_code : '';
        $health_post = isset($woman) ? $woman->getHealthpost($woman->hp_code) : '';
        $hp_district = isset($woman) ? $woman->getHealthPostInfo($woman->hp_code)->district_id : 0;
        $hp_municipality_name = isset($woman) ? $woman->getHealthPostInfo($woman->hp_code)->municipality_id : 0;
        $hp_ward = isset($woman) ? $woman->getWard($woman->hp_code) : 0;

        $chronic_illness = isset($guest_user->mis_data) ? json_decode($guest_user->mis_data)->chronic_illness : '';
        $current_healthpost = isset($guest_user->mis_data) ? json_decode($guest_user->mis_data)->current_healthpost : '';

        $mool_darta_no = $woman->mool_darta_no ?? '';
        $sewa_darta_no = $woman->sewa_darta_no ?? '';
        $orc_darta_no = $woman->orc_darta_no ?? '';
        $healthWorkerFullName = isset($woman) ? HealthWorker::findHealthWorkerByToken($woman->created_by) : '';
        $healthWorkerPost = isset($woman) ? HealthWorker::findHealthWorkerPostByToken($woman->created_by) : '';
        $healthWorkerPhone = isset($woman) ? HealthWorker::findHealthPhoneByToken($woman->created_by) : '';

        try {
            $data = json_decode($guest_user->mis_data);
            $height = $data->height ?? '';
            $bloodGroup = $data->bloodGroup ?? '';
            $husband_name = $data->husband_name ?? '';
        } catch (\Exception $e) {
                       
        }

        $generalInfo = [ 
                        'token' => $woman_token,
                        'name' => $full_name,
                        'no_of_pregnant_before' => $no_of_pregnant_before ?? '',
                        'age' => $age,        
                        'height' => $height,        
                        'district_id' => $district,        
                        'municipality_id' => $municipality,        
                        'ward' => $ward ?? '',        
                        'tole' => $tole,        
                        'phone' => $phone,        
                        'bloodGroup' => $bloodGroup,        
                        'husband_name' => $husband_name,
                        'lmp_date_en' => $lmp_date_en,
                        'lmp_date_np' => $lmp_date_np,
                        'hp_code' => $hp_code,
                        'healthpost_name' => $health_post,
                        'hp_district' => $hp_district,
                        'hp_municipality' => $hp_municipality_name,
                        'hp_ward' => $hp_ward,
                        'chronic_illness' => $chronic_illness ?? '',
                        'current_healthpost' => $current_healthpost ?? '',
                        'mool_darta_no' => $mool_darta_no,
                        'sewa_darta_no' => $sewa_darta_no,
                        'orc_darta_no' => $orc_darta_no,
                        'health_worker_full_name' => $healthWorkerFullName,                            
                        'health_worker_post' => $healthWorkerPost,                            
                        'health_worker_phone' => $healthWorkerPhone,   
                    ];
        return $generalInfo; 
    }

}
