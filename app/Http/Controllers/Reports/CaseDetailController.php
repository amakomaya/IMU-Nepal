<?php

namespace App\Http\Controllers\Reports;

use App\Http\Requests\WomenRequest;
use App\Models\Province;
use App\Models\SampleCollection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SuspectedCase;
use App\Models\SuspectedCaseOld;
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
            'municipality', 'caseManagement', 'clinicalParameter',
            'laboratoryParameter', 'registerBy', 'symptomsRelation',
            'contactTracing' => function ($q) {
                $q->with('contactDetail', 'contactFollowUp');
            }
        ])
        ->where('token', $token)->first();

        if(empty($data)) {
            $data = SuspectedCaseOld::with(['ancs', 'healthworker', 'healthpost', 'district',
            'municipality', 'caseManagement', 'clinicalParameter', 'contactDetail',
            'contactFollowUp', 'contactTracing' , 'laboratoryParameter', 'registerBy', 'symptomsRelation'
            ])
            ->where('token', $token)->first();
        }

        return view('backend.patient.detail', compact('data'));
    }


    public function cictDetail(Request $request){
        $token = $request->token;
        $data = SuspectedCase::with(['ancs', 'healthworker', 'healthpost', 'district',
                'municipality', 'caseManagement', 'clinicalParameter', 'laboratoryParameter', 'registerBy', 'symptomsRelation', 
                'contactTracing' => function ($q) {
                    $q->with('contactDetail', 'contactFollowUp');
                }
            ])
            ->where('token', $token)
            ->first();

        return view('backend.patient.cict_detail', compact('data'));
    }


    function edit($token)
    {
        $data = SuspectedCase::withAll()->where('token', $token)->first();
        if(empty($data)) {
            $data = SuspectedCaseOld::withAll()->where('token', $token)->first();
        }
        $samples = SampleCollection::where('status', '1')->where('woman_token', $token)->get();

        return view('backend.patient.edit', compact('data','samples'));
    }

    public function update(Request $request, $id)
    {
        $woman = SuspectedCase::find($id);
        if(empty($woman)) {
            $woman = SuspectedCaseOld::find($id);
        }
        $row = $request->all();
        $row['reson_for_testing'] = $row['reson_for_testing'] ? "[".implode(', ', $row['reson_for_testing'])."]" : '[]';
        if($request->symptoms_recent == 1) {
            $request->symptoms_comorbidity = $request->symptoms_comorbidity ?? [];
            if($request->symptoms_comorbidity_trimester) {
                array_push($request->symptoms_comorbidity, $request->symptoms_comorbidity_trimester);
            }
            $row['symptoms'] = isset($request->symptoms) ? "[" . implode(', ', $request->symptoms) . "]" : "[]";
            $row['symptoms_comorbidity'] = isset($request->symptoms_comorbidity) ? "[" . implode(', ', $request->symptoms_comorbidity) . "]" : "[]";
            $row['symptoms_specific'] = $request->symptoms_specific;
            $row['symptoms_comorbidity_specific'] = $request->symptoms_comorbidity_specific;
            $row['date_of_onset_of_first_symptom'] = $request->date_of_onset_of_first_symptom;
        } else {
            $row['symptoms'] = "[]";
            $row['symptoms_specific'] = "";
            $row['symptoms_comorbidity'] = "[]";
            $row['symptoms_comorbidity_specific'] = "";
            $row['date_of_onset_of_first_symptom'] = "";
        }
        // dd($row);
        unset($row['symptoms_comorbidity_trimester']);
        $woman->update($row);
        $request->session()->flash('message', 'Data Updated successfully');
        return redirect()->back();
    }
}
