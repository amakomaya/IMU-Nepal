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
use App\Models\LabTest;
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

        $woman = Woman::whereIn('hp_code', $hpCodes)->active()->get(['created_at', 'hp_code', 'token', 'cases', 'case_where']);
        $sample_collection = Anc::whereIn('hp_code', $hpCodes)->active()->orderBy('created_at', 'desc');
        $tests = LabTest::active()->orderBy('created_at', 'desc');

        $chartData = $woman->where(\DB::raw("(DATE_FORMAT(created_at,'%Y'))"), date('Y'))
            ;

        $chartWoman = Charts::database($chartData, 'bar', 'highcharts')
            ->title("Total Registered = " . Woman::whereIn('hp_code', $hpCodes)->active()->count())
            ->dimensions(800, 400)
            ->responsive(true)
            ->groupByMonth(date('Y'), true);

        $last_24_hrs_register = $woman->where('created_at', '>', Carbon::now()->subDay())->count();

        $last_24_hrs_sample_collection = $sample_collection->where('created_at', '>', Carbon::now()->subDay())->get()->count();
        
//        $last_24_hrs_tests = $tests->whereIn('hp_code', $hpCodes)->where('created_at', '>', Carbon::now()->subDay())->get()->count();

        $last_24_hrs_tests = 0;
        $last_24_hrs_positive = 0;

//        $last_24_hrs_positive = $tests->whereIn('hp_code', $hpCodes)->where('created_at', '>', Carbon::now()->subDay())->where('sample_test_result', 3)->get()->count();

        $data = [
                'total_register' => $woman->count(),
                'total_sample_collection' => Anc::whereIn('hp_code', $hpCodes)->active()->count(),
                'total_tests' => 0,
                'total_positive' => 0,
                'last_24_hrs_register' => $last_24_hrs_register,
                'last_24_hrs_sample_collection' => $last_24_hrs_sample_collection,
                'last_24_hrs_tests' => $last_24_hrs_tests,
                'last_24_hrs_positive' => $last_24_hrs_positive,
                // 'mild_cases_home' => $woman->where('cases', '1')->where('case_where', '0')->count(),
                // 'severe_cases_home' => $woman->where('cases', '2')->where('case_where', '0')->count()
                'mild_cases_home' => $woman->where('cases', '1')->count(),
                'severe_cases_home' => $woman->where('cases', '2')->count()
        ];

        $update_profile_expiration = Carbon::parse(auth()->user()->updated_at)->addMonth();
//
//        if ($update_profile_expiration < Carbon::now() ) {
//            $request->session()->flash('message', 'Update your account\'s information ! <a href="/admin/profile">Edit Profile</a>');
//        }

        return view('admin', compact('data', 'chartWoman', 'provinces', 'districts', 'options', 'ward_or_healthpost', 'municipalities', 'wards', 'healthposts', 'province_id', 'district_id', 'municipality_id', 'ward_id', 'hp_code', 'from_date', 'to_date'));
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