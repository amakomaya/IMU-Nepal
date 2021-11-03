<?php

namespace App\Http\Controllers\Reports;

use App\Models\SampleCollection;
use App\Models\Organization;
use App\Models\District;
use App\Models\LabTest;
use App\Models\ContactTracing;
use App\Models\ContactTracingOld;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use DB;
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
            // 'token' => 'required',
            'service_for' => 'required',
            'infection_type' => 'required',
            'service_type' => 'required',
            // 'result' => 'required',
        ]);
        
        
        $sample = SampleCollection::find($id);
        // $sample->token = $request->get('token');
        // $sample->case_token = $request->get('case_token');
        $sample->service_for = $request->get('service_for');
        $sample->sample_type = $request->get('sample_type') ? "[".implode(', ', $request->get('sample_type'))."]" : "[]";
        $sample->sample_type_specific = $request->get('sample_type_specific');
        $sample->infection_type = $request->get('infection_type');
        $sample->service_type = $request->get('service_type');
        // $sample->result = $request->get('result');


        $sample->save();

        // LabTest::where('sample_token', $id)->update([
        //     'sample_test_result' => $request->get('result')
        // ]);
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
        $reporting_days = $filter_date['to_date']->diffInDays($filter_date['from_date']) + 1;

        foreach ($response as $key => $value) {
            $$key = $value;
        }

        $reports = SampleCollection::leftjoin('organizations', 'sample_collection.org_code', '=', 'organizations.org_code')
            ->whereIn('sample_collection.org_code', $hpCodes)
            ->whereIn('sample_collection.result', [3, 4])
            ->whereIn('organizations.hospital_type', [2, 3])
            ->whereBetween(\DB::raw('DATE(sample_collection.sample_test_date_en)'), [$filter_date['from_date']->toDateString(), $filter_date['to_date']->toDateString()]);

        if ($response['province_id'] !== null){
            $reports = $reports->where('organizations.province_id', $response['province_id']);
        }

        if($response['district_id'] !== null){
            $reports = $reports->where('organizations.district_id', $response['district_id']);
        }
        if($response['municipality_id'] !== null){
            $reports = $reports->where('organizations.municipality_id', $response['municipality_id']);
        }

        $reports = $reports->get()
            ->groupBy('org_code');

        
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
            
        return view('backend.sample.report.report', compact('data','provinces','districts','municipalities','organizations','province_id','district_id','municipality_id','org_code','from_date','to_date', 'select_year', 'select_month', 'reporting_days'));
    }



    public function sampleLabReport(Request $request) {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
        $filter_date = $this->dataFromAndTo($request);
        $reporting_days = $filter_date['to_date']->diffInDays($filter_date['from_date']) + 1;

        foreach ($response as $key => $value) {
            $$key = $value;
        }

        $user = auth()->user();
        $reports = SampleCollection::leftjoin('organizations', 'sample_collection.received_by_hp_code', '=', 'organizations.org_code')
            ->whereIn('sample_collection.received_by_hp_code', $hpCodes)
            ->whereIn('sample_collection.result', ['3', '4'])
            ->whereBetween(\DB::raw('DATE(sample_collection.sample_test_date_en)'), [$filter_date['from_date']->toDateString(), $filter_date['to_date']->toDateString()])
            ->get()
            ->groupBy('org_code');
        
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
            
        return view('backend.sample.report.lab-report', compact('data','provinces','districts','municipalities','organizations','province_id','district_id','municipality_id','org_code','from_date','to_date', 'select_year', 'select_month', 'reporting_days'));
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

    public function labVisualizationReport(Request $request) {
        $response = FilterRequest::filter($request);
        $response['hospital_type'] = [2, 3];
        $hpCodes = GetHealthpostCodes::filter($response);
        foreach ($response as $key => $value) {
            $$key = $value;
        }


        $filter_date = $this->dataFromOnly($request);

        $organizations = Organization::whereIn('org_code', $hpCodes)
            ->where('status', 1)
            ->select('name', 'token', 'org_code')
            ->get()
            ->keyBy('org_code')
            ->toArray();

        $lab_organizations = SampleCollection::
           whereIn('sample_collection.received_by_hp_code', $hpCodes)
            ->whereIn('service_for', ['1', '2'])
            ->whereIn('result', ['3', '4'])
            ->whereDate('reporting_date_en', $filter_date['from_date']->toDateString())
            ->where('sample_collection.status', 1)
            ->select('received_by_hp_code', 'service_for', 'result', 'registered_device')
            ->get()
            ->groupBy('received_by_hp_code');

        $mapped_data = collect($lab_organizations)->map(function ($lab, $key) use ($organizations) {
            $return = [];
            $return['name'] = $organizations[$key]['name'];
            $return['token'] = $organizations[$key]['token'];
            $return['pcr_postive_today'] = $lab->where('service_for', '1')->where('result', '3')->count();
            $return['pcr_negative_today'] = $lab->where('service_for', '1')->where('result', '4')->count();
            $return['antigen_positive_today'] = $lab->where('service_for', '2')->where('result', '3')->count();
            $return['antigen_negative_today'] = $lab->where('service_for', '2')->where('result', '4')->count();

            $api_data_today = $lab->where('registered_device', 'api')->count();
            if($api_data_today > 0) {
                $return['api_today'] = 'Yes';
            } else {
                $return['api_today'] = 'No';
            }

            return $return;
        })->toArray();
        $empty_organizations = array_diff_key($organizations, $mapped_data);
        $data = array_merge($mapped_data, $empty_organizations);
        return view('backend.sample.report.lab-visualization', compact('data', 'from_date'));

    }

    private function dataFromOnly(Request $request)
    {
        if (!empty($request['from_date'])) {
            $from_date_array = explode("-", $request['from_date']);
            $from_date_eng = Carbon::parse(Calendar::nep_to_eng($from_date_array[0], $from_date_array[1], $from_date_array[2])->getYearMonthDay())->startOfDay();
        }

        return [
            'from_date' =>  $from_date_eng ?? Carbon::now()->startOfDay(),
        ];
    }

    public function organizationRegdevCount(Request $request) {
        $response = FilterRequest::filter($request);
        $response['hospital_type'] = [1, 2, 3, 5, 6];
        $hpCodes = GetHealthpostCodes::filter($response);
        
        $date_chosen = Carbon::now()->toDateString();
        if($request->date_selected){
            if($request->date_selected == '2') {
                $date_chosen = Carbon::now()->subDays(1)->toDateString();
            }elseif($request->date_selected == '3') {
                $date_chosen = Carbon::now()->subDays(2)->toDateString();
            }elseif($request->date_selected == '4') {
                $date_chosen = Carbon::now()->subDays(3)->toDateString();
            }elseif($request->date_selected == '5') {
                $date_chosen = Carbon::now()->subDays(4)->toDateString();
            }elseif($request->date_selected == '6') {
                $date_chosen = Carbon::now()->subDays(5)->toDateString();
            }elseif($request->date_selected == '7') {
                $date_chosen = Carbon::now()->subDays(6)->toDateString();
            }else {
                $date_chosen = Carbon::now()->toDateString();
            }
        }

        $organizations = Organization::whereIn('org_code', $hpCodes)
            ->where('status', 1)
            ->select('name', 'org_code')
            ->get()
            ->keyBy('org_code')
            ->toArray();

        $mainquery = SampleCollection::whereIn('org_code', $hpCodes)
            ->whereDate('collection_date_en', $date_chosen)
            ->select('org_code', 'registered_device');

        $other_registered_device_data = $mainquery->get()
            ->groupBy('org_code');
            
        $other_registered_device_data_count = $other_registered_device_data->map(function ($sample, $key) use($organizations) {
            $return = [];
            $return['name'] = $organizations[$key]['name'];
            $return['web_count'] = $sample->where('registered_device', 'web')->count();
            $null_count = $sample->where('registered_device', null)->count();
            $mobile_count = $sample->where('registered_device', 'mobile')->count();
            $return['mobile_count'] = $null_count + $mobile_count;
            $return['api_count'] = $sample->where('registered_device', 'api')->count();
            return $return;
        })->toArray();

        $excel_data_count = $mainquery
            ->where('registered_device', 'like', '%' . 'excel' . '%')
            ->select('org_code', \DB::raw('COUNT(*) as excel_count'))
            ->groupBy('org_code')
            ->get()
            ->keyBy('org_code')
            ->toArray();
        $final_excel_data = isset($excel_data_count[""]) ? [] : $excel_data_count;
        
        $merged_data = array_merge_recursive($other_registered_device_data_count, $final_excel_data);
        $empty_organizations = array_diff_key($organizations, $merged_data);
        $data = array_merge($merged_data, $empty_organizations);

        return view('backend.sample.report.regdev', compact('data'));
    }

    public function organizationContactTracing(Request $request) {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
        $contact_tracing = ContactTracing::whereIn('org_code', $hpCodes)
            ->get()
            ->groupBy('org_code');
        $contact_tracing_hpcodes = [];
        $contact_tracing_hpcodes = $contact_tracing->map(function ($item, $key) {return $key;})->values()->toArray();
        
        $contact_tracing_dump = ContactTracingOld::whereIn('org_code', $hpCodes)
            ->get()
            ->groupBy('org_code');
        $contact_tracing_dump_hpcodes = [];
        $contact_tracing_dump_hpcodes = $contact_tracing_dump->map(function ($item, $key) {return $key;})->values()->toArray();
        array_merge($contact_tracing_hpcodes, $contact_tracing_dump_hpcodes);
        
        $organizations = Organization::whereIn('org_code', $contact_tracing_hpcodes)
            ->get()
            ->groupBy('org_code');
        
        $data = [];
        foreach($organizations as $key => $organization) {
            $current_count = isset($contact_tracing[$key]) ? $contact_tracing[$key]->count() : 0;
            $dump_count = isset($contact_tracing_dump[$key]) ? $contact_tracing_dump[$key]->count() : 0;
            $data[$key]['count'] = $current_count + $dump_count;
            $data[$key]['organization_name'] = $organization->first()->name;
        }

        return view('backend.sample.report.contact-tracing', compact('data'));
    }
}
