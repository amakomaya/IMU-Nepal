<?php

namespace App\Http\Controllers\Api;

use App\CovidImmunization;
use App\Models\HealthProfessional;
use App\Models\MunicipalityInfo;
use App\Models\OrganizationMember;
use App\Models\VaccinationRecord;
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
        try{
            $hp_code = $request->hp_code;
            $currentDate = Carbon::now()->format('Y-m-d');
            $data = CovidImmunization::where('hp_code', $hp_code)
                ->where('expire_date', '>=', $currentDate)
                ->pluck('data_list');

            $id_list = array();

            foreach($data as $item){
                $id_list[] = explode(",", $item);
            }

            $id_list = collect($id_list)->flatten()->unique();

            $response = [];
            $response['health_professional'] = HealthProfessional::whereIn('id', $id_list)->get();
            $response['vaccination_records'] = VaccinationRecord::whereIn('vaccinated_id', $id_list)->get();

            return response()->json($response);

        }catch (\Exception $exception){
            return response()->json(['message' => 'Data Not Found']);
        }
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
