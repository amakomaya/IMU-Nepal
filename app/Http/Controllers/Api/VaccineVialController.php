<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\VaccineVial;

class VaccineVialController extends Controller
{
    public $msg = array();

    public function index(Request $request)
    {
        $hpCode = $request->hp_code;
        $heathpost = VaccineVial::where('hp_code', $hpCode)->get();
        return response()->json($heathpost);
    }

    public function store(Request $request)
    {
        $json = json_decode($request->getContent(), true);
        foreach ($json as $data) {
            VaccineVial::updateOrCreate(
                [
                    'vial_image'            => $data['vial_image'],
                ],
                [   
                    'hp_code'               => $data['hp_code'],
                    'vaccine_name'          => $data['vaccine_name'],
                    'maximum_doses'         => $data['maximum_doses'],
                    'status'                => $data['status'],
                    'created_at'            => $data['created_at'],
                    'updated_at'            => $data['updated_at']     
                ]);
        }
        return response()->json('1');
    }

    public function show(Request $request)
    {
        $token = $request->token;
        $model = $this->findModelVaccineVial($token);
        if(count($this->msg)>0){
            return response()->json($this->msg);
        }
        return response()->json($model);
    }

    public function update(Request $request, $id)
    {
        $json = json_decode($request->getContent(), true);
        $token=array();
        foreach ($json as $data) {
            $VaccineVial = $this->findModelVaccineVial($data['token']);
            if(count($this->msg)>0){
                return response()->json($this->msg);
            }
            if($data['updated_at']>=$VaccineVial->updated_at){
                foreach ($data as $key => $record) {
                    $VaccineVial->$key = $record;
                    if($key=="token"){
                        $token[]['token'] = $record;
                    }
                }
                $VaccineVial->save();
            }
        }
        return response()->json($token);
    }

    public function destroy($id)
    {
        //
    }

    public function findModelVaccineVial($token){
        if (($model = VaccineVial::where('token', $token)->get()->first()) !== null) {
            return $model;
        } else {
            $this->msg = ['name'=>'Not Found', 'message'=>'The requested page doesn\'t exist', 'status'=>'404'];
            return $this;
        }
    }
}
