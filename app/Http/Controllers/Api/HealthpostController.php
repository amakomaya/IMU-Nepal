<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Healthpost;

class HealthpostController extends Controller
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
        $heathpost = Healthpost::where([['hp_code', $hpCode],['status', 1]])->get();
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
        $hp_code=array();
        foreach ($json as $data) {
            $healthpost = new Healthpost;
            foreach ($data as $key => $record) {
                $healthpost->$key = $record;
                if($key=="hp_code"){
                    $hp_code[]['hp_code'] = $record;
                }
            }
            $healthpost->save();
        }
        return response()->json($hp_code);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        
        $hpCode = $request->hp_code;
        $model = $this->findModelHealthpost($hpCode);
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
        $hp_code=array();
        foreach ($json as $data) {
            $healthpost = $this->findModelHealthpost($data['hp_code']);
            if(count($this->msg)>0){
                return response()->json($this->msg);
            }
            if($data['updated_at']>=$healthpost->updated_at){
                foreach ($data as $key => $record) {
                    $healthpost->$key = $record;
                    if($key=="hp_code"){
                        $hp_code[]['hp_code'] = $record;
                    }
                }
                $healthpost->save();
            }
        }
        return response()->json($hp_code);
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

    public function findModelHealthpost($hp_code){
        if (($model = Healthpost::where('hp_code', $hp_code)->get()->first()) !== null) {
            return $model;
        } else {
            $this->msg = ['name'=>'Not Found', 'message'=>'The requested page doesn\'t exist', 'status'=>'404'];
            return $this;
        }
    }
}
