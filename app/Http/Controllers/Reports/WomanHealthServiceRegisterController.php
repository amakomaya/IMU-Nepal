<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Reports\FilterRequest;
use App\Services\Reports\WomanHealthServiceRegisterService;

class WomanHealthServiceRegisterController extends Controller
{

    protected $service;

    public function __construct(WomanHealthServiceRegisterService $service){
        $this->middleware('auth');
        $this->service = $service;
    }

    public function get(Request $request){
        $response = FilterRequest::filter($request);
        foreach ($response as $key => $value) {
            $$key = $value;
        }
        $data = $this->service->all($response);

        return view('reports.woman-health-service-register', compact('data','provinces', 'ward_or_healthpost','districts','municipalities','wards','healthposts','options','province_id','district_id','municipality_id','ward_id','hp_code','from_date','to_date', 'select_year', 'select_month'));
    }
}