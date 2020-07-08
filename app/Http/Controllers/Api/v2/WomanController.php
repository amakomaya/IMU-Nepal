<?php

namespace App\Http\Controllers\Api\v2;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Woman;

class WomanController extends Controller
{ 
    public function get(Request $request)
    {
        if($request->has('district_id')){
            $woman = Woman::where([['district_id', $request->district_id],['status', 1]])->get();
            return response()->json($woman);        
        }

        if($request->has('municipality_id')){
            $woman = Woman::where([['municipality_id', $request->municipality_id],['status', 1]])->get();
            return response()->json($woman);  
        }

        if($request->has('token')){
            $woman = Woman::where([['token', $request->token],['status', 1]])->get();
            return response()->json($woman);        
        }

        if($request->has('hp_code')){
            $woman = Woman::where([['hp_code', $request->hp_code],['status', 1]])->get();
            return response()->json($woman);        
        }

        $woman = Woman::where('status', 1)->get();
        return response()->json($woman);      
    }
}