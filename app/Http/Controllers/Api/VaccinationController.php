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
        $responses = HealthProfessional::where('id', $token)->first();
        if (!empty($responses)){
            $data['info'] = $responses->getOriginal();
            $data['vaccination'] = $responses->vaccinationRecord;
            return response()->json($data);
        }else{
            return response()->json(['message' => 'Data Not Found']);
        }
    }
}
