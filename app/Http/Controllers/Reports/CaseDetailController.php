<?php

namespace App\Http\Controllers\Reports;

use App\Http\Requests\WomenRequest;
use App\Models\province;
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

    function edit($token)
    {
        $data = SuspectedCase::withAll()->where('token', $token)->first();

        $samples = SampleCollection::where('status', '1')->where('woman_token', $token)->get();
//
//        $provinces = province::all();
//        foreach ($provinces as $province)
//            dd($province->id);

        return view('backend.patient.edit', compact('data','samples'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'token' => 'required',
            'case_id' => 'required',
            'name' => 'required',
            'age' => 'required',
            'age_unit' => 'required',
            'caste' => 'required',
            'sex' => 'required',
            'province_id' => 'required',
            'district_id' => 'required',
            'municipality_id' => 'required',
            'ward' => 'required',
            'tole' => 'required',
            'travelled' => 'required',
            'occupation' => 'required',
        ]);

        $woman = SuspectedCase::find($id);
        $woman->token = $request->get('token');
        $woman->case_id = $request->get('case_id');
        $woman->name = $request->get('name');
        $woman->age = $request->get('age');
        $woman->age_unit = $request->get('age_unit');
        $woman->caste = $request->get('caste');
        $woman->sex = $request->get('sex');
        $woman->province_id = $request->get('province_id');
        $woman->district_id = $request->get('district_id');
        $woman->municipality_id = $request->get('municipality_id');
        $woman->ward = $request->get('ward');
        $woman->tole = $request->get('tole');
        $woman->emergency_contact_one = $request->get('emergency_contact_one');
        $woman->emergency_contact_two = $request->get('emergency_contact_two');
        $woman->occupation = $request->get('occupation');
        $woman->travelled = $request->get('travelled');

        $woman->save();

        return view('backend.woman.index');
    }

//    public function update(Request $request, Blogs $blog)
//    {
//        // $data = $request->validate([
//        //     'title' => 'required',
//        //     'description' => 'required',
//        // ]);
//
//        $blog->update($this->validateBlog());
//
//        return redirect()->route('blogs.index')
//            ->with('success','Blog updated successfully');
//    }
}
