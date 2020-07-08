<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LabTest;

class LabTestController extends Controller
{
    public function index(Request $request)
    {
        $hpCode = $request->hp_code;
        $data = LabTest::where([['hp_code', $hpCode],['status', 1]])->get();
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $json = json_decode($request->getContent(), true);
        try{
            LabTest::insert($json);
            return response()->json(1);
        }catch(\Exception $e){
            return response()->json(0);
        }
    }

    public function update(Request $request, $id)
    {
        $json = json_decode($request->getContent(), true);
        try{
            foreach($json as $data){
                $token = $data['token'];
                LabTest::where('token', $token)->update($data);
            }
            return response()->json(1);
        }catch(\Exception $e){
            return response()->json(0);
        }
    }
}