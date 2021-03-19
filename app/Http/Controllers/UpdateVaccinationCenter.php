<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organization;
use GuzzleHttp\Client;

class UpdateVaccinationCenter extends Controller
{
    
    public function index(Request $request)
    {
        $response = $client->get('http://httpbin.org/get')->json();
        dd($response);
        
            // $data = Http::get('http://vaccine.mohp.gov.np/api/vaccination-centers')->json();
            // 
            // return view('updateVaccinationCenter',compact('data'));
            
    }
    

}
