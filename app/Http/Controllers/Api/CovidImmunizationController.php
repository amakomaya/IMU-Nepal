<?php

namespace App\Http\Controllers\Api;

use App\CovidImmunization;
use App\Models\MunicipalityInfo;
use App\Models\OrganizationMember;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class CovidImmunizationController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request, CovidImmunization $covidImmunization)
    {
//        $customMessages = [
//            'required' => 'The :attribute field is required.',
//        ];
//
//        $request->validate([
//            'data_list' => 'required',
//        ], $customMessages);

        $data = $request->all();
        $data['hp_code'] = $request->hp_code;
        $data['municipality_id'] = MunicipalityInfo::where('token', auth()->user()->token)->first()->municipality_id;
        $data['data_list'] = $data['data_list'];
        CovidImmunization::create($data);
        $request->session()->flash('message', 'Data Send for Immunization successfully');
        return redirect()->route('health-professional.index');
    }

    public function show(Request $request)
    {
        $hp_code = $request->hp_code;
        $currentDate = Carbon::now()->format('Y-m-d');
        $data = CovidImmunization::where('hp_code', $hp_code)
            ->where('expire_date', '>=', $currentDate)->first();
        return response()->json($data);
    }

    public function edit(CovidImmunization $covidImmunization)
    {
        //
    }

    public function update(Request $request, CovidImmunization $covidImmunization)
    {
        //
    }

    public function destroy(CovidImmunization $covidImmunization)
    {
        //
    }
}
