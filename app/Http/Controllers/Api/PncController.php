<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Pnc;

class PncController extends Controller
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
        $heathpost = Pnc::where([['hp_code', $hpCode],['status', 1]])->get();
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
        $token=array();
        foreach ($json as $data) {
            $Pnc = new Pnc;
            foreach ($data as $key => $record) {
                $Pnc->$key = $record;
                if($key=="token"){
                    $token[]['token'] = $record;
                }
            }
            $Pnc->save();
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
        $hpCode = $request->token;
        $model = $this->findModelPnc($hpCode);
        if(count($this->msg)>0){
            return response()->json($this->msg);
        }
        return response()->json($model);
    }

    public function showPncByWomanToken(Request $request){
        $womanToken = $request->woman_token;
        $model = $this->findModelPncByWomanToken($womanToken);
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
        try{
            foreach($json as $data){
                $token = $data['token'];
                Pnc::where('token', $token)->update($data);
            }
            return response()->json(1);
        }catch(\Exception $e){
            return response()->json(0);
        }
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

    public function findModelPnc($token){
        if (($model = Pnc::where('token', $token)->get()->first()) !== null) {
            return $model;
        } else {
            $this->msg = ['name'=>'Not Found', 'message'=>'The requested page doesn\'t exist', 'status'=>'404'];
            return $this;
        }
    }

    public function findModelPncByWomanToken($woman_token){
        if (($model = Pnc::where('woman_token', $woman_token)->get()) !== null) {
            return $model;
        } else {
            $this->msg = ['name'=>'Not Found', 'message'=>'The requested page doesn\'t exist', 'status'=>'404'];
            return $this;
        }
    }
}
