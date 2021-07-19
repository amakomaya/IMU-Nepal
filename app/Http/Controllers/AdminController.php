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
use Yagiten\Nepalicalendar\Calendar;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */


    public function index(Request $request)
    {
      $user = auth()->user();
      $userRole = $user->role;
      if($userRole == 'healthpost'){
        $organization = Organization::where('token', $user->token)->first();
        $organizationType = $organization->hospital_type;
      } else if($userRole == 'healthworker') {
        $organization_hp_code = OrganizationMember::where('token', auth()->user()->token)->first()->hp_code;
        $organization = Organization::where('hp_code', $organization_hp_code)->first();
        $organizationType = $organization->hospital_type;
      } else {
        return view('admin');
      }
      switch($organizationType) {
        case 7:
          return view('admin-poe');
          break;
        default:
          return view('admin'); 
      }
    }

    public function newDashbaord()
    {
        return view('admin-new-dashboard');
    }

    public function getDistrictValue(Request $request){
        $id = $request->get('id');
        $district_name = District::where('district_id', $id)->get();
        return $district_name->office_address;
    }

    public function districtSelectByProvince(Request $request)
    {
        $id = $request->get('id');
        echo '<select class="form-control" name="district_id" id="district_id" onchange="districtOnchange($(this).val())">';
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
        echo '<select class="form-control" name="municipality_id" id="municipality_id" onchange="municipalityOnchange($(this).val())">';
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
        echo '<select name="ward_id" class="form-control" id="ward_id" onchange="wardOnchange($(this).val())">';
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
        echo '<select id="hp_code" class="form-control" name="hp_code">';

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
        echo '<select id="organization" class="form-control" name="organization">';
        $organizations = Organization::where('municipality_id', $id)->whereIn('hospital_type', [5,3,6])->orderBy('name', 'asc')->pluck('name')->unique();
        echo "<option value=\"\">Select Organization Name</option>";
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

    public function sidSearch(Request $request) {
        if((Auth::user()->role == 'main' || Auth::user()->role == 'province' || Auth::user()->role == 'municipality' || Auth::user()->role == 'healthpost') && session()->get('permission_id') == 1) {
            if($request->sid) {
                $response = FilterRequest::filter($request);
                $hpCodes = GetHealthpostCodes::filter($response);

                $ancs = SampleCollection::with('woman', 'labreport')
                    ->whereIn('hp_code', $hpCodes)
                    ->where('token', $request->sid)
                    ->first();

            } else {
                $ancs = [1];
            }
            $dateToday = Carbon::now()->format('Y-d-m');
    
            return view('backend.sid-search.edit', compact('ancs', 'dateToday'));
        } else {
            return redirect('/admin');
        }
    }

    public function sidUpdate(Request $request) {
        // if(Auth::user()->role == 'main' || Auth::user()->role == 'province') {
            $reson_for_testing = $request->reson_for_testing ? "[" . implode(', ', $request->reson_for_testing) . "]" : '[]';
            if($request->symptoms_recent == 1) {
                $request->symptoms_comorbidity = $request->symptoms_comorbidity ?? [];
                if($request->symptoms_comorbidity_trimester) {
                    array_push($request->symptoms_comorbidity, $request->symptoms_comorbidity_trimester);
                }
                $symptoms = isset($request->symptoms) ? "[" . implode(', ', $request->symptoms) . "]" : "[]";
                $symptoms_comorbidity = isset($request->symptoms_comorbidity) ? "[" . implode(', ', $request->symptoms_comorbidity) . "]" : "[]";
                
                $symptoms_specific = $request->symptoms_specific;
                $symptoms_comorbidity_specific = $request->symptoms_comorbidity_specific;
                $date_of_onset_of_first_symptom = $request->date_of_onset_of_first_symptom;
            } else {
                $symptoms = "[]";
                $symptoms_specific = "";
                $symptoms_comorbidity = "[]";
                $symptoms_comorbidity_specific = "";
                $date_of_onset_of_first_symptom = "";
            }
            $sample_recv_date_en = '';
            if($request->sample_recv_date) {
                $sample_recv_date_np_array = explode("-", $request->sample_recv_date);
                $sample_recv_date_en = Calendar::nep_to_eng($sample_recv_date_np_array[0], $sample_recv_date_np_array[1], $sample_recv_date_np_array[2])->getYearMonthDay();
            }
            $sample_test_date_en = '';
            if($request->sample_test_date) {
                $sample_test_date_np_array = explode("-", $request->sample_test_date);
                $sample_test_date_en = Calendar::nep_to_eng($sample_test_date_np_array[0], $sample_test_date_np_array[1], $sample_test_date_np_array[2])->getYearMonthDay();
            }
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
                    'date_of_onset_of_first_symptom' => $date_of_onset_of_first_symptom,
                    'occupation' => $request->occupation,
                    'travelled' => $request->travelled,
                    'reson_for_testing' => $reson_for_testing,
                    'symptoms_recent' => $request->symptoms_recent,
                    'symptoms_within_four_week' => $request->symptoms_within_four_week,
                    'symptoms' => $symptoms,
                    'symptoms_specific' => $symptoms_specific,
                    'symptoms_comorbidity' => $symptoms_comorbidity,
                    'symptoms_comorbidity_specific' => $symptoms_comorbidity_specific
                ]);
                
                if($request->sample_recv_date != null && $request->remaining_token != null) {
                    SampleCollection::where('token', $request->sid)->first()->update([
                      'result' => $request->sample_test_result,
                      'received_date_np' => $request->sample_recv_date,
                      'received_date_en' => $sample_recv_date_en,
                      'sample_test_date_np' => $request->sample_test_date,
                      'sample_test_date_en' => $sample_test_date_en,
                      'lab_token' => $request->lab_tests_token
                    ]);
                    $lab_id = LabTest::where('sample_token', $request->sid)->first()->id;
                    LabTest::where('id', $lab_id)->update([
                        'sample_recv_date' => $request->sample_recv_date,
                        'sample_test_date' => $request->sample_test_date,
                        'sample_test_time' => $request->sample_test_time,
                        'sample_test_result' => $request->sample_test_result,
                        'token' => $request->lab_tests_token
                    ]);
                }

                $request->session()->flash('message', 'Data updated successfully');
            } catch(Exception $e) {
                $request->session()->flash('error', "Error on data update. Please retry !");
            }

            return redirect()->back();
        // }
        // else {
        //     return redirect('/admin');
        // }
    }
}