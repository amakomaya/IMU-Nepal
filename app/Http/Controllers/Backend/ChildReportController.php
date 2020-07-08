<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\GetHealthpostCodes;
use App\Reports\FilterRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\VaccinationRecord;
use App\Models\BabyDetail;
use Auth;
use Yagiten\Nepalicalendar\Calendar;
use Charts;
use App\Reports\DateFromToRequest;

class ChildReportController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }

    public function vaccineReceivedUsageWastage(Request $request){
        
        $response = (new VaccinationRecord)->vaccineReceivedUsageWastage($request);

        $monthlyreport = $response[0];
        $responses = $response[1];
        
        foreach ($responses as $key => $value) {
            $$key = $value;
        }

        return view('backend.child-report.vaccine-received-usage-wastage',compact('monthlyreport','provinces','districts','municipalities','wards','healthposts','province_id','district_id','municipality_id','ward_id','hp_code','fiscal_year','ficalYearList'));
    }

    public function registeredChild(Request $request){

        $response = (new BabyDetail)->registeredChild($request);

        $monthlyreport = $response[0];
        $responses = $response[1];
        
        foreach ($responses as $key => $value) {
            $$key = $value;
        }

        return view('backend.child-report.registered-child',compact('monthlyreport','provinces','districts','municipalities','wards','healthposts','province_id','district_id','municipality_id','ward_id','hp_code','fiscal_year','ficalYearList'));

    }

    public function immunizedChild(Request $request){

        $response = (new VaccinationRecord)->immunizedChildRawFormat($request);

        $monthlyreport = $response[0];
        $responses = $response[1];
        
        foreach ($responses as $key => $value) {
            $$key = $value;
        }

        return view('backend.child-report.immunized-child',compact('monthlyreport','provinces','districts','municipalities','wards','healthposts','province_id','district_id','municipality_id','ward_id','hp_code','fiscal_year','ficalYearList'));
    }

    public function droupoutChild(Request $request){
        $exceed_time = 1;
        if(isset($request->exceed_time)){
            $exceed_time = $request->exceed_time;
        }
        $response = (new VaccinationRecord)->droupoutChild($request, $exceed_time);

        $monthlyreport = $response[0];
        $responses = $response[1];
        
        foreach ($responses as $key => $value) {
            $$key = $value;
        }

        return view('backend.child-report.droupout-child',compact('monthlyreport','provinces','districts','municipalities','wards','healthposts','province_id','district_id','municipality_id','ward_id','hp_code','fiscal_year','ficalYearList','exceed_time'));
    }

    public function eligibleChild(Request $request){

        $response = (new VaccinationRecord)->eligibleChild($request);

        $monthlyreport = $response[0];
        $responses = $response[1];
        
        foreach ($responses as $key => $value) {
            $$key = $value;
        }

        return view('backend.child-report.eligible-child',compact('monthlyreport','provinces','districts','municipalities','wards','healthposts','province_id','district_id','municipality_id','ward_id','hp_code','fiscal_year','ficalYearList'));
        
    }

    public function healthReport(Request $request, $id)
    {
        $child = BabyDetail::findOrFail($id);
        $label = [];
        for ($i=1; $i <= $child->babyWeights->pluck('weight')->count(); $i++) { 
            $label[] = convertToNepali($i);
        }
        
        $chart = Charts::create('line', 'highcharts')
                    ->title('उमेर अनुसार बृद्धि अनुमान चार्ट')
                    ->xAxisTitle('उमेर (महिनामा)')
                    ->elementLabel('तौल (के. जि. मा)')
                    ->labels($label)
                    ->values($child->babyWeights->pluck('weight')->all())
                    ->dimensions(1000,500)
                    ->responsive(true);
        return view('backend.child-report.health-report-card', compact('child', 'chart'));
    }

    public function vaccinatedChildReport(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
        $date = $this->dataFromAndTo($request);

        $vaccinated_baby = VaccinationRecord::whereIn('hp_code', $hpCodes)
                            ->vaccinatedFromToDate($date['from_date'], $date['to_date'])
                            ->active()->pluck('baby_token')->unique();
        
        $data = BabyDetail::whereIn('token', $vaccinated_baby)
                            ->with('vaccinations','aefis', 'weights')
                            ->active()->get();

        foreach ($response as $key => $value) {
            $$key = $value;
        }
        return view('backend.child-report.vaccinated-detail-raw-list', compact('data','provinces', 'ward_or_healthpost','districts','municipalities','wards','healthposts','options','province_id','district_id','municipality_id','ward_id','hp_code','from_date','to_date'));
    }

    private function dataFromAndTo($request)
    {
        return DateFromToRequest::dateFromTo($request);
    }
}