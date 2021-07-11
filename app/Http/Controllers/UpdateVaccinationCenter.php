<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class UpdateVaccinationCenter extends Controller
{

    public function index(Request $request)
    {
        $municipality = \App\Models\MunicipalityInfo::where('token', auth()->user()->token)->first();

        $organizations = \App\Models\Organization::where('municipality_id', $municipality->municipality_id)->get();
        
        $vaccination_centers_api = json_decode(file_get_contents('http://vaccine2.mohp.gov.np/api/vaccination-centers'));

        $vaccination_centers = collect($vaccination_centers_api)->where('municipality_id', $municipality->municipality_id);
        return view('UpdateVaccinationCenter',compact('organizations', 'vaccination_centers'));

    }

}
