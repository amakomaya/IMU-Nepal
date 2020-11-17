<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Healthpost;
use App\Models\HealthWorker;
use Illuminate\Support\Facades\DB;


class LoginController extends Controller
{
    public function login(Request $request)
    {
        $json = json_decode($request->getContent(), true);
        foreach ($json as $data) {
            $username = $data['username'];
            $password = $data['password'];
            $imei     = $data['imei'];
        }

        $user = User::where([['username', $username], ['password', md5($password)], ['role', 'healthworker']])->get()->first();

        if (!empty($user)) {
            $healthworker = HealthWorker::where('token',$user->token)->get()->first();
//            if($healthworker->hp_code == "amh" || $healthworker->district_id == 65 || $request->root() == 'http://demo.aamakomaya.com'){
                $response = ['id'=>$healthworker->id,'name'=>$healthworker->name,'role'=>$healthworker->role,'token'=>$user->token,'hp_code'=>$healthworker->hp_code,  'municipality_id'=>$healthworker->municipality_id, 'district_id'=>$healthworker->district_id ];
//            }else{
//                $check_imei = User::where('imei', $imei)->get()->first();
//                if(isset($check_imei)){
//                    $response = ['name'=>$healthworker->name,'role'=>$healthworker->role,'token'=>$user->token,'hp_code'=>$healthworker->hp_code,  'municipality_id'=>$healthworker->municipality_id, 'district_id'=>$healthworker->district_id ];
//                }else{
//                    $response = ['token'=>'98', 'message'=>'The device is not authorized'];
//                }
//            }
            return response()->json($response);           
        }
        $response = ['token'=>'0', 'message'=>'The requested username or password doesn\'t exist', 'status'=>'404'];
        return response()->json($response);
    }

    public function vtcLogin(Request $request)
    {
        $username = $request->username;
        $password = $request->password;
        $imei = $request->imei;
    	$user = User::where([
                        ['username', $username],
                        ['password', md5($password)],
                        ['role', 'healthpost']])->get()->first();
       
        if (!empty($user)) {
            $healthpost = Healthpost::where('token',$user->token)->get()->first();
            if($healthpost->hp_code == "amh"){
                $response = [
                    'success' => 1,
                    'message' => "Successfully Logged in !!!",
                    'name'=>$healthpost->name,
                    'phone' => $healthpost->phone,
                    'email' => $healthpost->email,
                    'district' => $healthpost->district_id,
                    'address'  => $healthpost->address,
                    'hp_code'=>$healthpost->hp_code
                ];
            }else{
                $check_imei = User::where('imei', $imei)->get()->first();
                if(isset($check_imei)){
                    $response = [
                        'success' => 1,
                        'message' => "Successfully Logged in !!!",
                        'name'=>$healthpost->name,
                        'phone' => $healthpost->phone,
                        'email' => $healthpost->email,
                        'district' => $healthpost->district_id,
                        'address'  => $healthpost->address,
                        'hp_code'=>$healthpost->hp_code
                    ];
                }else{
                    $response = ['success'=>'98', 'message'=>'The device is not authorized'];
                }
            }
            return response()->json($response);           
        }
        $response = ['success'=>'0', 'message'=>'The requested username or password doesn\'t exist', 'status'=>'404'];
        return response()->json($response);
    }

    public function v2AmcLogin(Request $request)
    {
        $json = json_decode($request->getContent(), true);
        foreach ($json as $data) {
            $username = $data['username'];
            $password = $data['password']; 
        }

        $user = User::where([['username', $username], ['password', md5($password)], ['role', 'healthworker']])->get()->first();

        if (!empty($user)) {
            $healthworker = HealthWorker::where('token',$user->token)->get()->first();
            $response = [
                'id' => $healthworker->id,
                'name'=>$healthworker->name,
                'role'=>$healthworker->role,
                'token'=>$user->token,
                'hp_code'=>$healthworker->hp_code,
                'hp_name'=>$healthworker->getHealthpost($healthworker->hp_code),
                'province_id'=>$healthworker->province_id,
                'municipality_id'=>$healthworker->municipality_id,
                'district_id'=>$healthworker->district_id
            ];
            $position = \Location::get();
            $detect = \Browser::detect();
            $details_log = ['location'=> $position, 'browser' => $detect];
            activity('login')
                ->causedBy($user)
                ->performedOn(new User())
                ->withProperties($request[0])
                ->log(json_encode($details_log));

            return response()->json($response);
        }
        $response = ['token'=>'0', 'message'=>'The requested username or password doesn\'t exist', 'status'=>'404'];
        return response()->json($response);
    }

    public function v3AmcLogin(Request $request){
        $json = json_decode($request->getContent(), true);
        foreach ($json as $data) {
            $username = $data['username'];
            $password = $data['password'];
        }

        $user = User::where([['username', $username], ['password', md5($password)], ['role', 'healthworker']])->get()->first();

        if (!empty($user)) {
            $healthworker = HealthWorker::where('token',$user->token)->get()->first();
            $response = [
                'id' => $healthworker->id,
                'name'=>$healthworker->name,
                'role'=>$healthworker->role,
                'token'=>$user->token,
                'hp_code'=>$healthworker->hp_code,
                'hp_name'=>$healthworker->getHealthpost($healthworker->hp_code),
                'province_id'=>$healthworker->province_id,
                'municipality_id'=>$healthworker->municipality_id,
                'district_id'=>$healthworker->district_id,
                'permissions' => implode(",", $user->getAllPermissions()->pluck('name')->toArray())
            ];
            $position = \Location::get();
            $detect = \Browser::detect();
            $details_log = ['location'=> $position, 'browser' => $detect];
            activity('login')
                ->causedBy($user)
                ->performedOn(new User())
                ->withProperties($request[0])
                ->log(json_encode($details_log));

            return response()->json($response);
        }
        $response = ['token'=>'0', 'message'=>'The requested username or password doesn\'t exist', 'status'=>'404'];
        return response()->json($response);
    }
}
