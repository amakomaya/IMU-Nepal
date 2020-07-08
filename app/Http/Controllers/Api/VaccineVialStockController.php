<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\VaccineVialStock;
use Illuminate\Support\Facades\DB;

class VaccineVialStockController extends Controller
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
        $data_ids = VaccineVialStock::select(DB::raw('max(id) as id'))->where('hp_code', $hpCode)
                        ->groupBy('name')
                        ->get();

        $data = VaccineVialStock::whereIn('id', $data_ids)->get();
        return response()->json($data);
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
            foreach ($data as $key => $record) {
                VaccineVialStock::updateOrCreate(
                    [
                        'token'                 => $data['token']                       
                    ],
                    [   
                        'name'                  => $data['name'],
                        'hp_code'               => $data['hp_code'],
                        'dose_in_stock'         => $data['dose_in_stock'],
                        'new_dose'              => $data['new_dose'],
                        'reuseable_dose'        => $data['reuseable_dose'],
                        'vaccination_ending_at' => $data['vaccination_ending_at'],
                        'status'                => $data['status'],
                        'created_at'            => $data['created_at'],
                        'updated_at'            => $data['updated_at']     
                    ]);
            }
        }
        return response()->json("1");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $token = $request->token;
        $model = $this->findModelVaccineVialStock($token);
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
    public function update(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $response = [];
        try{
                if (isset($data)){
                    foreach ($data as $datum){
                        try{

                            VaccineVialStock::updateOrCreate(['token' => $datum['token']], $datum);
                            $success = [
                                'token' => $datum['token'],
                                'status' => 'success',
                                'response' => [
                                    'message' => 'Successfully updated',
                                    'type' => 200
                                ]
                            ];
                            array_push($response, $success);
                        }catch (Exception $e){
                            $error = [
                                'token' => $datum['token'],
                                'status' => 'error',
                                'response' => [
                                    'message' => $e->getMessage(),
                                    'type' => $e->getCode()
                                ]
                            ];
                            array_push($response, $error);
                        }
                    }
                }
            }catch(Exception $e){
                DB::rollback();
                return response()->json(['message' => "Opp's Something went wrong. Please try again"]);
            }
            return response()->json($response);
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

    public function findModelVaccineVialStock($token){
        if (($model = VaccineVialStock::where('token', $token)->get()->first()) !== null) {
            return $model;
        } else {
            $this->msg = ['name'=>'Not Found', 'message'=>'The requested page doesn\'t exist', 'status'=>'404'];
            return $this;
        }
    }
}
