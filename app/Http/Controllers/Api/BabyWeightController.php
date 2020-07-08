<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BabyWeight;

class BabyWeightController extends Controller
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
        $heathpost = BabyWeight::where([['hp_code', $hpCode],['status', 1]])->get();
        return response()->json($heathpost);
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
        foreach ($json as $data) {
            BabyWeight::updateOrCreate(
                [
                    'token'                 => $data['token']                       
                ],
                [   
                    'baby_token'            => $data['baby_token'],
                    'hp_code'               => $data['hp_code'],
                    'weight'               => $data['weight'],
                    'weighed_date'       => $data['weighed_date'],
                    'status'                => $data['status'],
                    'created_at'            => $data['created_at'],
                    'updated_at'            => $data['updated_at']     
                ]);
            }   
        return response()->json('1');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $token = $request->baby_token;
        $model = $this->findModelBabyWeightByBabyToken($token);
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
            $BabyWeight = $this->findModelBabyWeight($data['token']);
            if(count($this->msg)>0){
                return response()->json($this->msg);
            }
            if($data['updated_at']>=$BabyWeight->updated_at){
                foreach ($data as $key => $record) {
                    $BabyWeight->$key = $record;
                    if($key=="token"){
                        $token[]['token'] = $record;
                    }
                }
                $BabyWeight->save();
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

    public function findModelBabyWeight($token){
        if (($model = BabyWeight::where('token', $token)->get()->first()) !== null) {
            return $model;
        } else {
            $this->msg = ['name'=>'Not Found', 'message'=>'The requested page doesn\'t exist', 'status'=>'404'];
            return $this;
        }
    }

    public function findModelBabyWeightByBabyToken($token){
        if (($model = BabyWeight::where('baby_token', $token)->get()->first()) !== null) {
            return $model;
        } else {
            $this->msg = ['name'=>'Not Found', 'message'=>'The requested page doesn\'t exist', 'status'=>'404'];
            return $this;
        }
    }
}
