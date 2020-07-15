<?php

namespace App\Http\Controllers;

use App\Helpers\GetHealthpostCodes;
use App\Helpers\ViewHelper;
use App\Models\Anc;
use App\Models\BabyDetail;
use App\Models\District;
use App\Models\Healthpost;
use App\Models\Municipality;
use App\Models\VaccinationRecord;
use App\Models\Ward;
use App\Models\Woman;
use App\Reports\FilterRequest;
use Carbon\Carbon;
use Charts;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);

        foreach ($response as $key => $value) {
            $$key = $value;
        }

        $woman = Woman::whereIn('hp_code', $hpCodes)->active();


        $chartData = $woman->where(\DB::raw("(DATE_FORMAT(created_at,'%Y'))"), date('Y'))
            ->get();

        $chartWoman = Charts::database($chartData, 'bar', 'highcharts')
            ->title("Total Registered = " . Woman::whereIn('hp_code', $hpCodes)->active()->count())
            ->dimensions(800, 400)
            ->responsive(true)
            ->groupByMonth(date('Y'), true);
            
        $tests = collect(Anc::whereIn('hp_code', $hpCodes)->active()->orderBy('created_at', 'desc')->groupBy('woman_token')->get());

        $ancCount = $tests->count();

        // $forInfected = $tests->filter(function($q) {
        //           if($q->pcr_test == 1 or $q->rdt_test == 1){
        //             return $q->pcr_result == 1 or $q->rdt_result == 1;
        //           };
        //       });


        $data = [
                'situation_normal' => $tests->where('situation', 1)->count(),
                'situation_possible' => $tests->where('situation', 2)->count(),
                'situation_danger' => $tests->where('situation', 3)->count(),

                // 'infected' => $forInfected->count(),


                'pcr_positive' => $tests->where('pcr_test', 1)->count(),
                'rdt_positive' => $tests->where('rdt_test', 1)->count(),
                'both_positive' => $tests->where('pcr_test', 1)->where('rdt_test', 1)->count(),

                // 'notTravelled' => $forInfected->where('situation', 3)->count(),
                // 'domesticTravel' => $forInfected->where('situation', 3)->count(),
                // 'internationalTravel' => $forInfected->where('situation', 3)->count(),

                'totalOrgQuarintine' => $tests->where('current_address', 0)->count(),
                'totalOrgIsolation' => $tests->where('current_address', 1)->count(),
                'totalHealthInstitude' => $tests->where('current_address', 2)->count(),
                'totalHomeQuarintine' => $tests->where('current_address', 3)->count(),
                'totalHomeIsolation' => $tests->where('current_address', 4)->count(),
                'totalOther' => $tests->where('current_address', 5)->count(),
        ];

        return view('admin', compact('data','ancCount', 'chartWoman', 'provinces', 'districts', 'options', 'ward_or_healthpost', 'municipalities', 'wards', 'healthposts', 'province_id', 'district_id', 'municipality_id', 'ward_id', 'hp_code', 'from_date', 'to_date'));
    }

    public function districtSelectByProvince(Request $request)
    {
        $id = $request->get('id');
        echo '<select class="form-control" name="district_id" id="district_id" onchange="districtOnchange($(this).val())" required>';
        $districts = District::where('province_id', $id)->orderBy('district_name', 'asc')->get();;
        echo "<option value=\"\">Select District</option>";
        foreach ($districts as $district) {
            echo "<option value=\"$district->id\">$district->district_name</option>";
        }
        echo '<select>';
    }

    public function municipalitySelectByDistrict(Request $request)
    {
        $id = $request->get('id');
        echo '<select class="form-control" name="municipality_id" id="municipality_id" onchange="municipalityOnchange($(this).val())" required>';
        $municipalities = Municipality::where('district_id', $id)->orderBy('municipality_name', 'asc')->get();
        echo "<option value=\"\">Select Municipality</option>";
        foreach ($municipalities as $municipality) {
            echo "<option value=\"$municipality->id\">$municipality->municipality_name</option>";
        }
        echo '<select>';
    }

    public function wardSelectByMunicipality(Request $request)
    {
        $id = $request->get('id');
        echo '<select name="ward_id" class="form-control" id="ward_id" onchange="wardOnchange($(this).val())" required>';
        $wards = Ward::where('municipality_id', $id)->orderBy('ward_no', 'asc')->get();;
        echo "<option value=\"\">Select Ward</option>";
        foreach ($wards as $ward) {
            echo "<option value=\"$ward->id\">$ward->ward_no</option>";
        }
        echo '<select>';
    }


    public function healthpostSelectByWard(Request $request)
    {
        $id = $request->get('id');
        $ward_no = Ward::getWardNo($id);
        $municipality_id = $request->get('municipality_id');
        echo '<select id="hp_code" class="form-control" name="hp_code" required>';

        $healthposts = Healthpost::where([['municipality_id', $municipality_id], ['ward_no', $ward_no]])->orderBy('name', 'asc')->get();

        echo "<option value=\"\">Select Healthpost</option>";
        foreach ($healthposts as $healthpost) {
            echo "<option value=\"$healthpost->hp_code\">$healthpost->name</option>";
        }
        echo '<select>';
    }


    public function healthpostSelect(Request $request)
    {
        $id = $request->get('id');
        echo '<select id="hp_code" class="form-control" name="hp_code" required>';
        $healthposts = Healthpost::where('municipality_id', $id)->orderBy('name', 'asc')->get();
        echo "<option value=\"\">Select Healthpost</option>";
        foreach ($healthposts as $Healthpost) {
            echo "<option value=\"$Healthpost->hp_code\">$Healthpost->name</option>";
        }
        echo '<select>';
    }

    public function selectFromTo(Request $request)
    {
        $from_date = ($request->get('from_date')) ? $request->get('from_date') : ViewHelper::convertEnglishToNepali(Carbon::now()->subMonth()->format('Y-m-d'));
        $to_date = ($request->get('to_date')) ? $request->get('to_date') : ViewHelper::convertEnglishToNepali(Carbon::now()->format('Y-m-d'));
        echo "
        <script src=\"" . asset('bower_components/jquery/dist/jquery.min.js') . "\"></script> 
        <script type=\"text/javascript\" src=\"" . asset('js/nepali.datepicker.v2.2.min.js') . "\"></script>

        <link rel=\"stylesheet\" type=\"text/css\" href=\"" . asset('css/nepali.datepicker.v2.2.min.css') . "\" />

        <script type=\"text/javascript\">
           $(document).ready(function(){
                $('#from_date').nepaliDatePicker({
                    npdMonth: true,
                    npdYear: true,
                    npdYearCount: 10
                });

                $('#to_date').nepaliDatePicker({
                    npdMonth: true,
                    npdYear: true,
                    npdYearCount: 10
                });
            });
        </script>

        <div class=\"form-group col-sm-3\" id=\"from\">
            <input class=\"form-control\" name=\"from_date\" id=\"from_date\" value=\"$from_date\" placeholder=\"\">
        </div>
        <div class=\"form-group col-sm-3\">
            <input class=\"form-control\" name=\"to_date\"  id=\"to_date\" value=\"$to_date\" placeholder=\"To Date\">
        </div>

        ";
    }
}