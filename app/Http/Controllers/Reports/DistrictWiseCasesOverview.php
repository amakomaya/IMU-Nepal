<?php

namespace App\Http\Controllers\Reports;

use App\Helpers\GetHealthpostCodes;
use App\Models\PaymentCase;
use App\Models\ProvinceInfo;
use App\Models\DistrictInfo;
use App\Models\SampleCollection;
use App\Reports\FilterRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DistrictWiseCasesOverview extends Controller
{
    public function provinceDistrictwiseReport(Request $request){
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
            }elseif($request->date_selected == '8') {
                $date_chosen = Carbon::now()->subDays(7)->toDateString();
            }else {
                $date_chosen = Carbon::now()->toDateString();
            }
        }

        $province_id = ProvinceInfo::where('token', auth()->user()->token)->first()->province_id;
        $reports = DB::select(DB::raw("SELECT districts.district_name, ancs.result, ancs.service_for FROM `suspected_cases` 
            LEFT JOIN ancs ON suspected_cases.token = ancs.woman_token
            LEFT JOIN districts on suspected_cases.district_id = districts.id
            WHERE suspected_cases.province_id = :province_id AND ancs.result in (3,4) and Date(ancs.reporting_date_en) = :date"), array(
            'province_id' => $province_id,
            'date' => $date_chosen
        ));
        $reports = collect($reports)->sortBy('district_name')->groupBy('district_name');
        $data = [];
        foreach($reports as $key => $report) {
            $data[$key]['district_name'] = $key;
            $data[$key]['pcr_postive_cases_count'] = $report->where('service_for', '1')->where('result', 3)->count();
            $data[$key]['pcr_negative_cases_count'] = $report->where('service_for', '1')->where('result', 4)->count();
            $data[$key]['antigen_postive_cases_count'] = $report->where('service_for', '2')->where('result', 3)->count();
            $data[$key]['antigen_negative_cases_count'] = $report->where('service_for', '2')->where('result', 4)->count();
        }
        return view('backend.sample.report.district-wise', compact('data'));
    }

    public function provinceMunicipalitywiseReport(Request $request){
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
            }elseif($request->date_selected == '8') {
                $date_chosen = Carbon::now()->subDays(7)->toDateString();
            }else {
                $date_chosen = Carbon::now()->toDateString();
            }
        }

        $province_id = ProvinceInfo::where('token', auth()->user()->token)->first()->province_id;
        $reports = DB::select(DB::raw("SELECT municipalities.municipality_name, municipalities.district_name, ancs.result, ancs.service_for FROM `suspected_cases` 
            LEFT JOIN ancs ON suspected_cases.token = ancs.woman_token
            LEFT JOIN municipalities on suspected_cases.municipality_id = municipalities.id
            WHERE suspected_cases.province_id = :province_id AND ancs.result in (3,4) and Date(ancs.reporting_date_en) = :date"), array(
            'province_id' => $province_id,
            'date' => $date_chosen
        ));
        $reports = collect($reports)->sortBy('municipality_name')->groupBy('municipality_name');
        $data = [];
        foreach($reports as $key => $report) {
            $data[$key]['district_name'] = $report->first()->district_name;
            $data[$key]['municipality_name'] = $key;
            $data[$key]['pcr_postive_cases_count'] = $report->where('service_for', '1')->where('result', 3)->count();
            $data[$key]['pcr_negative_cases_count'] = $report->where('service_for', '1')->where('result', 4)->count();
            $data[$key]['antigen_postive_cases_count'] = $report->where('service_for', '2')->where('result', 3)->count();
            $data[$key]['antigen_negative_cases_count'] = $report->where('service_for', '2')->where('result', 4)->count();
        }
        return view('backend.sample.report.province-municipality-wise', compact('data'));
    }

    public function municipalityReport(Request $request){
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
            }elseif($request->date_selected == '8') {
                $date_chosen = Carbon::now()->subDays(7)->toDateString();
            }else {
                $date_chosen = Carbon::now()->toDateString();
            }
        }

        $district_id = DistrictInfo::where('token', auth()->user()->token)->first()->district_id;
        $reports = DB::select(DB::raw("SELECT municipalities.municipality_name, ancs.result, ancs.service_for FROM `suspected_cases` 
            LEFT JOIN ancs ON suspected_cases.token = ancs.woman_token
            LEFT JOIN municipalities on suspected_cases.municipality_id = municipalities.id
            WHERE suspected_cases.district_id = :district_id AND ancs.result in (3,4) and Date(ancs.reporting_date_en) = :date"), array(
            'district_id' => $district_id,
            'date' => $date_chosen
        ));
        $reports = collect($reports)->sortBy('municipality_name')->groupBy('municipality_name');
        $data = [];
        foreach($reports as $key => $report) {
            $data[$key]['municipality_name'] = $key;
            $data[$key]['pcr_postive_cases_count'] = $report->where('service_for', '1')->where('result', 3)->count();
            $data[$key]['pcr_negative_cases_count'] = $report->where('service_for', '1')->where('result', 4)->count();
            $data[$key]['antigen_postive_cases_count'] = $report->where('service_for', '2')->where('result', 3)->count();
            $data[$key]['antigen_negative_cases_count'] = $report->where('service_for', '2')->where('result', 4)->count();
        }
        return view('backend.sample.report.municipality-wise', compact('data'));
    }
}
