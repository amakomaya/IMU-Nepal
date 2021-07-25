<?php

namespace App\Http\Controllers\Reports;

use App\Helpers\GetHealthpostCodes;
use App\Models\PaymentCase;
use App\Models\ProvinceInfo;
use App\Models\SampleCollection;
use App\Reports\FilterRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DistrictWiseCasesOverview extends Controller
{
    public function all(Request $request){
        if(auth()->user()->role == 'province'){
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

            $data = DB::select(DB::raw("SELECT districts.district_name, ancs.result, ancs.service_for, ancs.sample_test_date_en, COUNT(*) as total FROM `women` 
LEFT JOIN ancs ON women.token = ancs.woman_token
LEFT JOIN districts on women.district_id = districts.id
WHERE women.province_id = :province_id AND ancs.result in (3,4)
GROUP BY districts.district_name, ancs.result, ancs.service_for, ancs.sample_test_date_en"), array(
                'province_id' => $province_id,
            ));
            return view('backend.reports.district-wise', compact('data'));
        }
        return redirect()->back();
    }
}
