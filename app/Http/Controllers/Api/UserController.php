<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Healthpost;
use App\Models\HealthWorker;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{
    public $msg = array();

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::latest()->get();
        return response()->json($user);
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
            if(User::where(['username' => $data['username'], 'role' => 'woman' ])->exists()){
                $data['username'] = '';
            }

            $data['password'] = md5($data['password']);
            try{
                activity()->disableLogging();
                User::create($data);
                array_push($token, $data['token']); 
            }catch(\Exception $e){

            }
      }
        return response()->json($token);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $hpCode = $request->token;
        $model = $this->findModelUser($hpCode);
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
        $token = array();
        foreach ($json as $data) {
            $User = $this->findModelUser($data['token']);
            if(count($this->msg)>0){
                return response()->json($this->msg);
            }
            if($data['updated_at']>=$User->updated_at){
                foreach ($data as $key => $record) {
                    $User->$key = $record;
                    if($key=="token"){
                        $token[]['token'] = $record;
                    }
                }
                $User->save();
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
                        ['password', md5($password)]
                    ])
                    ->get()
                    ->first();
        if(count($user)>0){
            $healthpost = Healthpost::where('token',$user->token)->get()->first();
            if(count($healthpost)>0)
            {
                $userInfo = ['name'=>$healthpost->name,'role'=>$user->role,'token'=>$user->token,'hp_code'=>$healthpost->hp_code, 'municipality_id'=>$healthpost->municipality_id, 'district_id'=>$healthpost->district_id ];
            }
            $healthworker = HealthWorker::where('token',$user->token)->get()->first();
            if(count($healthworker)>0)
            {
                $userInfo = ['name'=>$healthworker->name,'role'=>$healthworker->role,'token'=>$user->token,'hp_code'=>$healthworker->hp_code,  'municipality_id'=>$healthworker->municipality_id, 'district_id'=>$healthworker->district_id ];
            }
            return response()->json($userInfo);
        }else
        {
            $response = ['name'=>'Not Found', 'message'=>'The requested usename or password doesn\'t exist', 'status'=>'404'];
            return response()->json($response);
        }
    }

    public function findModelUser($token){
        if (($model = User::where('token', $token)->get()->first()) !== null) {
            return $model;
        } else {
            $this->msg = ['name'=>'Not Found', 'message'=>'The requested page doesn\'t exist', 'status'=>'404'];
            return $this;
        }
    }
}
