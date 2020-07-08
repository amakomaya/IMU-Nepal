<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\VialDetail\VialDetail;

class VialDetailController extends Controller
{
    public function index(Request $request)
    {
        $hpCode = $request->hp_code;
        $vial_details = VialDetail::where('hp_code', $hpCode)->latest()->get();
        return response()->json($vial_details);
    }

    public function batchStore(Request $request)
    {
        $json = json_decode($request->getContent(), true);
        try{
            VialDetail::insert($json);
            return response()->json(1);
        }catch(\Exception $e){
            return response()->json(0);
        }
    }

    public function batchUpdate(Request $request)
    {
        $json = json_decode($request->getContent(), true);
        foreach($json as $data){
            $vial_image = $data['vial_image'];
            VialDetail::where('vial_image', $vial_image)->update($data);
        }
        return response()->json(1);
    }
}