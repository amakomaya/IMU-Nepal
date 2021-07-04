<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Organization;
use App\Models\OrganizationMember;
use Illuminate\Support\Facades\DB;


class LoginController extends Controller
{

    public function v2AmcLogin(Request $request)
    {
        $json = json_decode($request->getContent(), true);
        foreach ($json as $data) {
            $username = $data['username'];
            $password = $data['password']; 
        }

        $user = User::where([['username', $username], ['password', md5($password)], ['role', 'healthworker']])->get()->first();

        if (!empty($user)) {
            $healthworker = OrganizationMember::where('token',$user->token)->get()->first();
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
            $healthworker = OrganizationMember::where('token',$user->token)->get()->first();
            $organization = Organization::where('hp_code', $healthworker->hp_code)->first();
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
                'permissions' => implode(",", $user->getAllPermissions()->pluck('name')->toArray()),
                'organization_type' => $organization->hospital_type,
                'vaccination_center_id' => $organization->vaccination_center_id
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
