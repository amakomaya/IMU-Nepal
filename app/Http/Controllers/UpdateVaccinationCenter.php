<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class UpdateVaccinationCenter extends Controller
{

    public function index(Request $request)
    {
        // $client = new \GuzzleHttp\Client();
        // $request =
        // $client->request('GET', 'http://vaccine.mohp.gov.np/api/vaccination-centers');
        // $response = $request->getBody();
        // $json = Response::json($request);
        // dd($json);

        // return view('updateVaccinationCenter', compact('response'));

        // return view('updateVaccinationCenter',['response'=> $response]);

        // $data = Http::get('http://vaccine.mohp.gov.np/api/vaccination-centers')->json();
        //
        // $organization = Organization::where()
        $municipality = \App\Models\MunicipalityInfo::where('token', auth()->user()->token)->first();

        $organizations = \App\Models\Organization::where('municipality_id', $municipality->municipality_id)->get();
        
        $vaccination_centers_api = json_decode(file_get_contents('http://vaccine.mohp.gov.np/api/vaccination-centers'));

        // $data = array();
        $vaccination_centers = collect($vaccination_centers_api)->where('municipality_id', $municipality->municipality_id);
        return view('updateVaccinationCenter',compact('organizations', 'vaccination_centers'));

    }

}
