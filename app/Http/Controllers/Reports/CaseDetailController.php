<?php

namespace App\Http\Controllers\Reports;

use App\Http\Requests\WomenRequest;
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

    function edit($token)
    {
        $data = SuspectedCase::withAll()->where('token', $token)->first();
        return view('backend.patient.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $woman = SuspectedCase::find($id);
        $woman->update($request->all());
        $request->session()->flash('message', 'Data Updated successfully');
        return redirect()->back();
    }
}
