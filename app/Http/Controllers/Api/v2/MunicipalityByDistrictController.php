<?php

namespace App\Http\Controllers\Api\v2;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Municipality;

class MunicipalityByDistrictController extends Controller
{ 
    public function get(Request $request)
    {
        if($request->has('district_id')){
            $municipality = Municipality::where([['district_id', $request->district_id]])->get();
            return response()->json($municipality);        
        }
        return response()->json([]);      
    }
}