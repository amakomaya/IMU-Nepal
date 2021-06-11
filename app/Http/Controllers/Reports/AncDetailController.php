<?php

namespace App\Http\Controllers\Reports;

use App\Models\SampleCollection;
use App\Models\Organization;
use App\Models\District;
use App\Models\LabTest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use App\Helpers\GetHealthpostCodes;
use App\Reports\FilterRequest;
use Yagiten\Nepalicalendar\Calendar;
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
        $request->session()->flash('message', 'Data Updated successfully');

        // return view('backend.woman.index');
        return redirect()->back();
    }

    public function delete(Request $request, $id) {

        SampleCollection::where('token', $id)->delete();
        LabTest::where('sample_token',$id)->delete();
        $request->session()->flash('message', 'Data Deleted successfully');

        return redirect()->back();
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

    public function sampleAncsReport(Request $request) {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
        $filter_date = $this->dataFromAndTo($request);
        $reporting_days = $filter_date['to_date']->diffInDays($filter_date['from_date']);

        foreach ($response as $key => $value) {
            $$key = $value;
        }

        $reports = SampleCollection::leftjoin('healthposts', 'ancs.hp_code', '=', 'healthposts.hp_code')
            ->whereIn('ancs.hp_code', $hpCodes)
            ->whereIn('ancs.result', [3, 4])
            ->whereIn('healthposts.hospital_type', [2, 3])
            ->whereBetween(\DB::raw('DATE(ancs.updated_at)'), [$filter_date['from_date']->toDateString(), $filter_date['to_date']->toDateString()]);

        if ($response['province_id'] !== null){
            $reports = $reports->where('healthposts.province_id', $response['province_id']);
        }

        if($response['district_id'] !== null){
            $reports = $reports->where('healthposts.district_id', $response['district_id']);
        }
        if($response['municipality_id'] !== null){
            $reports = $reports->where('healthposts.municipality_id', $response['municipality_id']);
        }

        $reports = $reports->get()
            ->groupBy('hp_code');

        
        $data = [];
        foreach($reports as $key => $report) {
            $district_name = District::where('id', $report[0]->district_id)->pluck('district_name')[0];
            $healthpost_name = $report[0]->name;
            $data[$key]['healthpost_name'] = $healthpost_name;
            $data[$key]['district_name'] = $district_name;
            $data[$key]['total_test'] = $report->count();
            $data[$key]['pcr_count'] = $report->where('service_for', '1')->count();
            $data[$key]['antigen_count'] = $report->where('service_for', '2')->count();
            $data[$key]['pcr_postive_cases_count'] = $report->where('service_for', '1')->where('result', 3)->count();
            $data[$key]['pcr_negative_cases_count'] = $report->where('service_for', '1')->where('result', 4)->count();
            $data[$key]['antigen_postive_cases_count'] = $report->where('service_for', '2')->where('result', 3)->count();
            $data[$key]['antigen_negative_cases_count'] = $report->where('service_for', '2')->where('result', 4)->count();
        }
            
        return view('backend.sample.report.report', compact('data','provinces','districts','municipalities','healthposts','province_id','district_id','municipality_id','hp_code','from_date','to_date', 'select_year', 'select_month', 'reporting_days'));
    }



    public function sampleLabReport(Request $request) {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
        $filter_date = $this->dataFromAndTo($request);
        $reporting_days = $filter_date['to_date']->diffInDays($filter_date['from_date']);

        foreach ($response as $key => $value) {
            $$key = $value;
        }

        $user = auth()->user();
        $reports = LabTest::leftjoin('healthposts', 'lab_tests.hp_code', '=', 'healthposts.hp_code')
            ->whereIn('lab_tests.hp_code', $hpCodes)
            // ->where(function($q) use ($hpCodes, $user) {
            //     $q->where('lab_tests.checked_by', $user->token)
            //         ->orWhereIn('lab_tests.hp_code', $hpCodes);
            // })
            ->whereIn('lab_tests.sample_test_result', ['3', '4'])
            ->whereBetween(\DB::raw('DATE(lab_tests.updated_at)'), [$filter_date['from_date']->toDateString(), $filter_date['to_date']->toDateString()]);


        if ($response['province_id'] !== null){
            $reports = $reports->where('healthposts.province_id', $response['province_id']);
        }

        if($response['district_id'] !== null){
            $reports = $reports->where('healthposts.district_id', $response['district_id']);
        }
        if($response['municipality_id'] !== null){
            $reports = $reports->where('healthposts.municipality_id', $response['municipality_id']);
        }

        $reports = $reports->get()
            ->groupBy('hp_code');
        
        $data = [];
        foreach($reports as $key => $report) {
            $district_name = District::where('id', $report[0]->district_id)->pluck('district_name')[0];
            $healthpost_name = $report[0]->name;
            $data[$key]['healthpost_name'] = $healthpost_name;
            $data[$key]['district_name'] = $district_name;
            $data[$key]['total_test'] = $report->count();
            $data[$key]['postive_cases_count'] = $report->where('sample_test_result', '3')->count();
            $data[$key]['negative_cases_count'] = $report->where('sample_test_result', '4')->count();
        }
            
        return view('backend.sample.report.lab-report', compact('data','provinces','districts','municipalities','healthposts','province_id','district_id','municipality_id','hp_code','from_date','to_date', 'select_year', 'select_month', 'reporting_days'));
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
