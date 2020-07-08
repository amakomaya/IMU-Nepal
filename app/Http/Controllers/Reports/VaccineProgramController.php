<?php

namespace App\Http\Controllers\Reports;

use App\Reports\FilterRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Reports\VaccinationProgramService;
use function MongoDB\BSON\toJSON;

class VaccineProgramController extends Controller
{

    protected $service;

    public function __construct(VaccinationProgramService $service){
        $this->middleware('auth');
        $this->service = $service;
    }
    public function vaccinationProgram(Request $request)
    {

        $response = FilterRequest::filter($request);
        foreach ($response as $key => $value) {
            $$key = $value;
        }

        $data = $this->service->all($response)[0];

        return view('reports.vaccination-program', compact('data','provinces', 'ward_or_healthpost','districts','municipalities','wards','healthposts','options','province_id','district_id','municipality_id','ward_id','hp_code','from_date','to_date', 'select_year', 'select_month'));
    }
}
