<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Fchv;

class FchvController extends Controller
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
        $heathpost = Fchv::where([['hp_code', $hpCode],['status', 1]])->get();
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
            $Fchv = new Fchv;
            foreach ($data as $key => $record) {
                $Fchv->$key = $record;
                if($key=="token"){
                    $token[]['token'] = $record;
                }
            }
            $Fchv->save();
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
        $token = $request->token;
        $model = $this->findModelFchv($token);
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
            $Fchv = $this->findModelFchv($data['token']);
            if(count($this->msg)>0){
                return response()->json($this->msg);
            }
            if($data['updated_at']>=$Fchv->updated_at){
                foreach ($data as $key => $record) {
                    $Fchv->$key = $record;
                    if($key=="token"){
                        $token[]['token'] = $record;
                    }
                }
                $Fchv->save();
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

    public function findModelFchv($token){
        if (($model = Fchv::where('token', $token)->get()->first()) !== null) {
            return $model;
        } else {
            $this->msg = ['name'=>'Not Found', 'message'=>'The requested page doesn\'t exist', 'status'=>'404'];
            return $this;
        }
    }
}
