<?php

namespace App\Http\Controllers\Reports;

use App\Http\Requests\WomenRequest;
use App\Models\Province;
use App\Models\SampleCollection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SuspectedCase;
use Illuminate\Support\Str;


class CaseDetailController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    function getCaseDetail(Request $request)
    {
        $token = $request->token;
        $data = SuspectedCase::with(['ancs', 'healthworker', 'healthpost', 'district',
            'municipality', 'caseManagement', 'clinicalParameter', 'contactDetail',
            'contactFollowUp', 'contactTracing' , 'laboratoryParameter', 'registerBy', 'symptomsRelation'
        ])
        ->where('token', $token)->first();

        return view('backend.patient.detail', compact('data'));
    }

    function getCaseDetailOld(Request $request)
    {
        $token = $request->token;
        $data = \DB::connection('mysqldump')->table('women')
            ->where('women.token', $token)
            ->leftjoin('healthposts', 'women.hp_code', '=', 'healthposts.hp_code')
            ->leftjoin('health_workers', 'women.created_by', '=', 'health_workers.token')
            ->leftjoin('ancs', 'women.token', '=', 'ancs.woman_token')
            ->leftjoin('lab_tests', 'ancs.token', '=', 'lab_tests.sample_token')
            ->leftjoin('provinces', 'women.province_id', '=', 'provinces.id')
            ->leftjoin('districts', 'women.district_id', '=', 'districts.id')
            ->leftjoin('municipalities', 'women.municipality_id', '=', 'municipalities.id')
            ->select(
                'women.*',
                \DB::raw("count(ancs.id) as ancs_count"),
                'ancs.id as ancs_id',
                'ancs.token as ancs_token',
                'ancs.created_at as ancs_created_at',
                'ancs.service_for as ancs_service_for',
                'ancs.sample_type as ancs_sample_type',
                'ancs.sample_type_specific as ancs_sample_type_specific',
                'ancs.infection_type as ancs_infection_type',
                'ancs.checked_by_name as ancs_checked_by_name',
                'ancs.result as ancs_result',
                'ancs.service_type as ancs_service_type',

                'lab_tests.checked_by_name as lab_tests_checked_by_name',
                'lab_tests.sample_recv_date as lab_tests_sample_recv_date',
                'lab_tests.sample_test_date as lab_tests_sample_test_date',
                'lab_tests.sample_test_time as lab_tests_sample_test_time',

                'healthposts.phone as healthpost_phone',
                'healthposts.created_at as healthpost_created_at',
                'health_workers.name as health_workers_name',
                'provinces.province_name as province_name',
                'districts.district_name as district_name',
                'municipalities.municipality_name as municipality_name'
            )
            ->groupBy('ancs_id')
            ->get();
            
        return view('backend.patient.detail-old', compact('data'));
    }


    public function cictDetail(Request $request){
        $token = $request->token;
        $data = SuspectedCase::with(['ancs', 'healthworker', 'healthpost', 'district',
            'municipality', 'caseManagement', 'clinicalParameter', 'contactDetail',
            'contactFollowUp', 'contactTracing' , 'laboratoryParameter', 'registerBy', 'symptomsRelation'
        ])
            ->where('token', $token)->first();
        return view('backend.patient.cict_detail', compact('data'));
    }


    function edit($token)
    {
        $data = SuspectedCase::withAll()->where('token', $token)->first();
        $data['symptoms'] = json_decode($data->symptoms ?: []);
        $data['symptoms_comorbidity'] = json_decode($data->symptoms_comorbidity ?: []);

        // @php $reasons = json_decode(isset($ancs) ? $ancs->reson_for_testing : [] ); @endphp

        $samples = SampleCollection::where('status', '1')->where('woman_token', $token)->get();
//
//        $provinces = province::all();
//        foreach ($provinces as $province)
//            dd($province->id);

        return view('backend.patient.edit', compact('data','samples'));
    }

    public function update(Request $request, $id)
    {
        $woman = SuspectedCase::find($id);
        $row = $request->all();
        $row['reson_for_testing'] = $row['reson_for_testing'] ? "[".implode(', ', $row['reson_for_testing'])."]" : '[]';
        if($request->symptoms_recent == 1) {
            if($request->symptoms_comorbidity_trimester) {
                array_push($row['symptoms_comorbidity'], $request->symptoms_comorbidity_trimester);
            }
            $row['symptoms'] = isset($row['symptoms']) ? "[" . implode(', ', $row['symptoms']) . "]" : "[]";
            $row['symptoms_comorbidity'] = isset($row['symptoms_comorbidity']) ? "[" . implode(', ', $row['symptoms_comorbidity']) . "]" : "[]";
        } else {
            $row['symptoms'] = "[]";
            $row['symptoms_specific'] = "";
            $row['symptoms_comorbidity'] = "[]";
            $row['symptoms_comorbidity_specific'] = "";
            $row['date_of_onset_of_first_symptom'] = "";
        }
        unset($row['symptoms_comorbidity_trimester']);
        $woman->update($row);
        $request->session()->flash('message', 'Data Updated successfully');
        return redirect()->back();
    }
}
