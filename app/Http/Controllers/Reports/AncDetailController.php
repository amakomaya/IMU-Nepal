<?php

namespace App\Http\Controllers\Reports;

use App\Models\SampleCollection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use App\Helpers\GetHealthpostCodes;
use App\Reports\FilterRequest;

use Carbon\Carbon;

class AncDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SampleCollection  $anc
     * @return \Illuminate\Http\Response
     */
    public function show(SampleCollection $anc)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SampleCollection  $anc
     * @return \Illuminate\Http\Response
     */
    public function edit($token)
    {
        $data = SampleCollection::where('status', '1')->where('token', $token)->first();
        return view('backend.sample.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SampleCollection  $anc
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'token' => 'required',
            'woman_token' => 'required',
            'service_for' => 'required',
            'infection_type' => 'required',
            'service_type' => 'required',
            'result' => 'required',
        ]);

        $sample = SampleCollection::find($id);
        $sample->token = $request->get('token');
        $sample->woman_token = $request->get('woman_token');
        $sample->service_for = $request->get('service_for');
        $sample->sample_type = "[".implode(', ', $request->get('sample_type'))."]";
        $sample->sample_type_specific = $request->get('sample_type_specific');
        $sample->infection_type = $request->get('infection_type');
        $sample->service_type = $request->get('service_type');
        $sample->result = $request->get('result');

        $sample->save();

        return view('backend.woman.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SampleCollection  $anc
     * @return \Illuminate\Http\Response
     */
    public function destroy(SampleCollection $anc)
    {
        //
    }

    public function sampleReport(Request $request) {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
        $filter_date = $this->dataFromAndTo($request);
        $reporting_days = $filter_date['to_date']->diffInDays($filter_date['from_date']);

        foreach ($response as $key => $value) {
            $$key = $value;
        }

        if(Auth::user()->role == 'dho') {
            $reports = SampleCollection::with('labreport')
                ->whereIn('hp_code', $hpCodes);

            if ($response['municipality_id'] !== null){
                $reports->where('healthposts.municipality_id', $response['municipality_id']);
            }
    
            if ($response['hp_code'] !== null){
                $reports->where('healthposts.hp_code', $response['hp_code']);
            }


            $reports = $reports->get()
                ->groupBy('hp_code');
            
            // $data = 

            dd($reports);
            
            return view('backend.sample.report.report', compact('reports','provinces','districts','municipalities','healthposts','province_id','district_id','municipality_id','hp_code','from_date','to_date', 'select_year', 'select_month', 'reporting_days'));
        }
    }

    private function dataFromAndTo(Request $request)
    {
        if (!empty($request['from_date'])) {
            $from_date_array = explode("-", $request['from_date']);
            $from_date_eng = Carbon::parse(Calendar::nep_to_eng($from_date_array[0], $from_date_array[1], $from_date_array[2])->getYearMonthDay())->startOfDay();
        }
        if (!empty($request['to_date'])) {
            $to_date_array = explode("-", $request['to_date']);
            $to_date_eng = Carbon::parse(Calendar::nep_to_eng($to_date_array[0], $to_date_array[1], $to_date_array[2])->getYearMonthDay())->endOfDay();
        }

        return [
            'from_date' =>  $from_date_eng ?? Carbon::now()->subMonth(1)->startOfDay(),
            'to_date' => $to_date_eng ?? Carbon::now()->endOfDay()
        ];
    }
}
