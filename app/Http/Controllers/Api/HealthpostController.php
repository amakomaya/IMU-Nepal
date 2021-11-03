<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Organization;

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
        $hpCode = $request->org_code;
        $heathpost = Organization::where([['org_code', $hpCode],['status', 1]])->get();
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
        $org_code=array();
        foreach ($json as $data) {
            $healthpost = new Organization;
            foreach ($data as $key => $record) {
                $healthpost->$key = $record;
                if($key=="org_code"){
                    $org_code[]['org_code'] = $record;
                }
            }
            $healthpost->save();
        }
        return response()->json($org_code);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        
        $hpCode = $request->org_code;
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
        $org_code=array();
        foreach ($json as $data) {
            $healthpost = $this->findModelHealthpost($data['org_code']);
            if(count($this->msg)>0){
                return response()->json($this->msg);
            }
            if($data['updated_at']>=$healthpost->updated_at){
                foreach ($data as $key => $record) {
                    $healthpost->$key = $record;
                    if($key=="org_code"){
                        $org_code[]['org_code'] = $record;
                    }
                }
                $healthpost->save();
            }
        }
        return response()->json($org_code);
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

    public function findModelHealthpost($org_code){
        if (($model = Organization::where('org_code', $org_code)->get()->first()) !== null) {
            return $model;
        } else {
            $this->msg = ['name'=>'Not Found', 'message'=>'The requested page doesn\'t exist', 'status'=>'404'];
            return $this;
        }
    }
}
