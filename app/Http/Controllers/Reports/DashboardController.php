<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Reports\FilterRequest;
use App\Services\Reports\DashboardService;
use Auth;
use Charts;

class DashboardController extends Controller
{

	protected $service;

	public function __construct(DashboardService $service){
        $this->middleware('auth');
		$this->service = $service;
	}

    public function index(Request $request)
    {
        $response = FilterRequest::filter($request);

        foreach ($response as $key => $value) {
            $$key = $value;
        }

        $data = $this->service->all($response);


        $chart = Charts::create('bar', 'highcharts')
            ->title('Information Chart')
            ->labels(['Registered SuspectedCase', 'Registered Baby' ,'Completed At least One SampleCollection Visit'])
            ->values([$data['registered_woman'], $data['registered_baby'], $data['anc_completed_at_least_one_visit']])
            ->dimensions(1000,500)
            ->responsive(false);

        return view('reports.dashboard', compact('chart','data','provinces','districts','options','ward_or_healthpost', 'municipalities','wards','organizations','province_id','district_id','municipality_id','ward_id','hp_code','from_date','to_date'));
    }
}
