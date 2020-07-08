<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Survey;
use Carbon\Carbon;
use App\User;
use App\Models\MunicipalityInfo;
use App\Models\provinceInfo;
use App\Models\Ward;
use App\Models\DistrictInfo;
use App\Exports\SurveyExport;
use Excel;

class SurveyController extends Controller
{
    public function index(Request $request)
    {

        if ($request->session()->has('user_role_for_survey')) {
            $role = $request->session()->get('user_role_for_survey');
            if ($role == 'main') {
                $data = Survey::pluck('data')->unique()->map(function ($survey){
                        return json_decode($survey, true);
                    })->sortByDesc('created_at')->groupBy(function ($item){
                    return Carbon::parse($item['created_at'])->format('Y-m-d'); 
                }); 

            return view('backend.survey.index')->with(['data' => $data]);
            }

            if ($role == 'province') {
                $province_id = $request->session()->get('user_province_id_for_survey');
                $data = Survey::pluck('data')->unique()->map(function ($survey) use ($province_id){
                        return json_decode($survey, true);

                    })->where('province_id', $province_id)
                    ->sortByDesc('created_at')->groupBy(function ($item){
                        return Carbon::parse($item['created_at'])->format('Y-m-d'); 
                    });
                return view('backend.survey.index')->with(['data' => $data]);
            }

            if ($role == 'dho') {
                $district_id = $request->session()->get('user_district_id_for_survey');

                $data = Survey::pluck('data')->unique()->map(function ($survey) use ($district_id){
                        return json_decode($survey, true);
                    })->where('district_id', $district_id)
                    ->sortByDesc('created_at')->groupBy(function ($item){
                        return Carbon::parse($item['created_at'])->format('Y-m-d'); 
                    });
                return view('backend.survey.index')->with(['data' => $data]);
            }

            if ($role == 'municipality') {
                $municipality_id = $request->session()->get('user_municipality_id_for_survey');

                $data = Survey::pluck('data')->unique()->map(function ($survey) use ($municipality_id){
                        return json_decode($survey, true);
                    })->where('municipality_id', $municipality_id)
                    ->sortByDesc('created_at')->groupBy(function ($item){
                        return Carbon::parse($item['created_at'])->format('Y-m-d'); 
                    });
                return view('backend.survey.index')->with(['data' => $data]);
            }

            if ($role == 'ward') {
                $municipality_id = $request->session()->get('user_municipality_id_for_survey');
                $ward_no = $request->session()->get('user_ward_no_for_survey');

                $data = Survey::pluck('data')->unique()->map(function ($survey) use ($municipality_id, $ward_no){
                        return json_decode($survey, true);
                    })->where('municipality_id', $municipality_id)->where('ward', $ward_no)
                    ->sortByDesc('created_at')->groupBy(function ($item){
                        return Carbon::parse($item['created_at'])->format('Y-m-d'); 
                    });

                return view('backend.survey.index')->with(['data' => $data]);
            }
            
        }

        return view('backend.survey.login');
    }

    public function login(Request $request){

        $user = \App\Models\SurveyUser::where('username', $request->username)
                  ->where('password',$request->password)
                  ->first();

        if (!empty($user)) {
            $role = '';
            $municipality_id = '';
            $ward_no = '';
            $district_id = '';
            $provience_id = '';

            if (!empty($user->is_provience) && !empty($user->is_district) && !empty($user->is_municipality) && !empty($user->is_ward)) {
                $role = 'ward';
                $municipality_id = $user->is_municipality;
                $ward_no = $user->is_ward;
            }

            if (!empty($user->is_provience) && !empty($user->is_district) && !empty($user->is_municipality) && empty($user->is_ward)) {
                $role = 'municipality';
                $municipality_id = $user->is_municipality;
            }

            if (!empty($user->is_provience) && !empty($user->is_district) && empty($user->is_municipality) && empty($user->is_ward) ) {
                $role = 'dho';
                $district_id = $user->is_district;
            }

            if (!empty($user->is_provience) && empty($user->is_district) && empty($user->is_municipality) && empty($user->is_ward)) {
                $role = 'province';
                $provience_id = $user->is_provience;
            }

            if (empty($user->is_provience) && empty($user->is_district) && empty($user->is_municipality) && empty($user->is_ward)) {
                $role = 'main';
            }

            $request->session()->put('user_role_for_survey', $role);
            $request->session()->put('user_municipality_id_for_survey', $municipality_id);
            $request->session()->put('user_ward_no_for_survey', $ward_no);
            $request->session()->put('user_province_id_for_survey', $provience_id);
            $request->session()->put('user_district_id_for_survey', $district_id);
            return redirect()->back();       
        }

        $request->session()->flash('error_message', 'Username or Password Incorrect!');
        return redirect()->back();
    }

    public function logout(Request $request){
        $request->session()->forget('user_role_for_survey');
        $request->session()->forget('user_municipality_id_for_survey');
        $request->session()->forget('user_ward_no_for_survey');
        $request->session()->forget('user_province_id_for_survey');
        $request->session()->forget('user_district_id_for_survey');
        return redirect()->back();
    }

    public function export(Request $request) 
    {
        return Excel::download(new SurveyExport($request->all()['data']), 'survey-'.time().'.xlsx');
    }

    public function user()
    {
        $data = \App\Models\SurveyUser::all();
        return view('backend.survey.user', compact('data'));
    }

    public function userStore(Request $request)
    {
        \App\Models\SurveyUser::create($request->all());
        return redirect()->back();
    }
}