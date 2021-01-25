<?php

namespace App\Http\Controllers\Api;

use App\Models\HealthProfessional;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\District;

class VaccinationController extends Controller
{
    public function index()
    {
        $responses = HealthProfessional::latest()->take(20)->get();
        return response()->json($responses);
    }

    public function qrSearch(Request $request)
    {
        $token = $request->token;
        $responses = HealthProfessional::where('id', $token)->with('vaccinationRecord')->first();
        if (!empty($responses)){
            return response()->json($responses);
        }else{
            return response()->json(['message' => 'Data Not Found']);
        }
    }
}
