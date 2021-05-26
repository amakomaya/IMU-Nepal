<?php

namespace App\Http\Controllers;

use App\Helpers\GetHealthpostCodes;
use App\Helpers\ViewHelper;
use App\Models\HealthProfessional;
use App\Models\MunicipalityInfo;
use App\Models\Province;
use App\Models\SampleCollection;
use App\Models\BabyDetail;
use App\Models\District;
use App\Models\Organization;
use App\Models\OrganizationMember;
use App\Models\Municipality;
use App\Models\VaccinationRecord;
use App\Models\Ward;
use App\Models\SuspectedCase;
use App\Models\LabTest;
use App\Reports\FilterRequest;
use Carbon\Carbon;
use Charts;
use DB;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */


    public function index()
    {
        return view('admin');
    }

    public function getDistrictValue(Request $request){
        $id = $request->get('id');
        $district_name = District::where('district_id', $id)->get();
        return $district_name->office_address;
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

        $healthposts = Organization::where([['municipality_id', $municipality_id], ['ward_no', $ward_no]])->orderBy('name', 'asc')->get();

        echo "<option value=\"\">Select Organization</option>";
        foreach ($healthposts as $healthpost) {
            echo "<option value=\"$healthpost->hp_code\">$healthpost->name</option>";
        }
        echo '<select>';
    }


    public function healthpostSelect(Request $request)
    {
        $id = $request->get('id');
        echo '<select id="hp_code" class="form-control" name="hp_code">';
        $healthposts = Organization::where('municipality_id', $id)->orderBy('name', 'asc')->get();
        echo "<option value=\"\">Select Organization</option>";
        foreach ($healthposts as $Healthpost) {
            echo "<option value=\"$Healthpost->hp_code\">$Healthpost->name</option>";
        }
        echo '<select>';
    }

    public function organizationSelect(Request $request)
    {
        $id = $request->get('id');
        echo '<label for="organization">Select working Organization</label>';
        echo '<select id="organization" class="form-control" name="organization">';
//        $healthposts = Organization::where('municipality_id', $id)->orderBy('name', 'asc')->get();
        $municipality_token = MunicipalityInfo::where('municipality_id', $id)->first()->token;
        $organization_token = Organization::where('municipality_id', $id)->pluck('token');
        $list = collect($organization_token)->merge($municipality_token);
        $organizations = HealthProfessional::whereIn('checked_by', $list)->orderBy('organization_name', 'asc')->pluck('organization_name')->unique();
        echo "<option value=\"\">Select All Organization Name</option>";
        foreach ($organizations as $Healthpost) {
            echo "<option value=\"$Healthpost\">$Healthpost</option>";
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

    public function ancsSearch(Request $request) {
        if(Auth::user()->role == 'main' || Auth::user()->role == 'province') {
            if($request->sid) {
                // $response = FilterRequest::filter($request);
                // $hpCodes = GetHealthpostCodes::filter($response);

                $ancs = SampleCollection::leftjoin('lab_tests', 'ancs.token', '=', 'lab_tests.sample_token')
                    ->leftjoin('women', 'ancs.woman_token', '=', 'women.token')
                    ->where('ancs.token', $request->sid)
                    ->where('lab_tests.sample_token', $request->sid)
                    ->select('women.*', 'ancs.token as ancs_token', 'lab_tests.token as lab_tests_token', 'lab_tests.sample_recv_date', 'lab_tests.sample_test_date', 'lab_tests.sample_test_time', 'lab_tests.sample_test_result')
                    ->first();
            } else {
                $ancs = [1];
            }
            $dateToday = Carbon::now()->format('Y-d-m');
    
            return view('backend.ancs-search.edit', compact('ancs', 'dateToday'));
        } else {
            return redirect('/admin');
        }
    }

    public function ancsUpdate(Request $request) {
        if(Auth::user()->role == 'main' || Auth::user()->role == 'province') {
            $reson_for_testing = $request->reson_for_testing ? "[" . implode(', ', $request->reson_for_testing) . "]" : '[]';
            try{
                $woman_id = SuspectedCase::where('token', $request->woman_token)->first()->id;
                SuspectedCase::where('id', $woman_id)->update([
                    'name' => $request->name,
                    'age' => $request->age,
                    'age_unit' => $request->age_unit,
                    'caste' => $request->caste,
                    'sex' => $request->sex,
                    'ward' => $request->ward,
                    'tole' => $request->tole,
                    'emergency_contact_one' => $request->emergency_contact_one,
                    'emergency_contact_two' => $request->emergency_contact_two,
                    'date_of_onset_of_first_symptom' => $request->date_of_onset_of_first_symptom,
                    'occupation' => $request->occupation,
                    'travelled' => $request->travelled,
                    'reson_for_testing' => $reson_for_testing
                ]);

                $lab_id = LabTest::where('sample_token', $request->sid)->first()->id;
                LabTest::where('id', $lab_id)->update([
                    'sample_recv_date' => $request->sample_recv_date,
                    'sample_test_date' => $request->sample_test_date,
                    'sample_test_time' => $request->sample_test_time,
                    'sample_test_result' => $request->sample_test_result,
                    'token' => $request->lab_tests_token,
                ]);

                $request->session()->flash('message', 'Data updated successfully');
            } catch(Exception $e) {
                $request->session()->flash('error', "Error on data update. Please retry !");
            }

            return redirect()->back();
        } else {
            return redirect('/admin');
        }
    }
}