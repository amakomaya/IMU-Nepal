<?php

namespace App\Http\Controllers\Reports;

use App\Http\Requests\WomenRequest;
use App\Models\Province;
use App\Models\SampleCollection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SuspectedCase;


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
        }
        unset($row['symptoms_comorbidity_trimester']);
        $woman->update($row);
        $request->session()->flash('message', 'Data Updated successfully');
        return redirect()->back();
    }
}
