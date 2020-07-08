<?php

namespace App\Http\Controllers\Api\v2;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Healthpost;

class HealthpostController extends Controller
{ 
    public function get(Request $request)
    {
        if($request->has('district_id')){
            $heathpost = Healthpost::where([['district_id', $request->district_id],['status', 1]])->get();
            return response()->json($heathpost);        
        }

        if($request->has('municipality_id')){
            $heathpost = Healthpost::where([['municipality_id', $request->municipality_id],['status', 1]])->get();
            return response()->json($heathpost);  
        }
        $heathpost = Healthpost::where('status', 1)->get();
        return response()->json($heathpost);      
    }
}