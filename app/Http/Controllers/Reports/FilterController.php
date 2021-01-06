<?php

namespace App\Http\Controllers\Reports;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Organization;
use App\Models\Municipality;
use App\Models\District;
use App\Models\Province;
use App\Models\Ward;

class FilterController extends Controller
{
    public function districtSelectByProvince(Request $request){
        $id = $request->get('id');
        echo '<select class="form-control" name="district_id" id="district_id" onchange="districtOnchange($(this).val())">';
        $districts = District::where('province_id', $id)->orderBy('district_name', 'asc')->get();;
            echo "<option value=\"\">Select District</option>";
            foreach($districts as $district)
            {
                echo"<option value=\"$district->id\">$district->district_name</option>";
            }
        echo '<select>';
    }

    public function municipalitySelectByDistrict(Request $request)
    {
        $id = $request->get('id');
        echo '<select class="form-control" name="municipality_id" id="municipality_id" onchange="municipalityOnchange($(this).val())">';
        $municipalities = Municipality::where('district_id', $id)->orderBy('municipality_name', 'asc')->get();
            echo "<option value=\"\">Select Municipality</option>";
            foreach($municipalities as $municipality)
            {
                echo"<option value=\"$municipality->id\">$municipality->municipality_name</option>";
            }
        echo '<select>';
    }

    public function WardOrHealthpostByMunicipality(Request $request){
        $id = $request->get('id');
        echo '<select name="ward_or_healthpost" class="form-control" id="ward_or_healthpost_id" onchange="wardOrHealthpostOnchange($(this).val())">';
//        $options = ['ward' => 'By ward', 'healthpost' => 'By healthpost'];
        $options = ['healthpost' => 'By healthpost'];
            echo "<option value=\"\">Select all Organization</option>";
            foreach($options as $key => $option)
            {
                $value = $key.$id;
                echo"<option value=\"$value\">$option</option>";
            }
        echo '<select>';
    }

    public function wardSelectByMunicipality(Request $request){
        $id = preg_replace('/[^0-9]/', '', $request->get('id'));
        echo '<select name="ward_id" class="form-control" id="ward_id" onchange="wardOnchange($(this).val())">';
        $wards = Ward::where('municipality_id', $id)->orderBy('ward_no', 'asc')->get();
            echo "<option value=\"\">Select all Ward</option>";
            foreach($wards as $ward)
            {
                echo"<option value=\"$ward->ward_no\">$ward->ward_no</option>";
            }
        echo '<select>';
    }

    public function healthpostSelectByMunicipality(Request $request){
        $id = preg_replace('/[^0-9]/', '', $request->get('id'));
        echo '<select id="hp_code" class="form-control" name="hp_code" required>';
        $healthposts = Organization::where([['municipality_id', $id]])->orderBy('name', 'asc')->get();
            echo "<option value=\"\">Select all Organization</option>";
            foreach($healthposts as $healthpost)
            {
                echo"<option value=\"$healthpost->hp_code\">$healthpost->name</option>";
            }
        echo '<select>';
    }

    public function selectFromTo(Request $request){
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');
        echo "
        <script src=\"". asset('bower_components/jquery/dist/jquery.min.js')."\"></script> 
        <script type=\"text/javascript\" src=\"". asset('js/nepali.datepicker.v2.2.min.js')."\"></script>

        <link rel=\"stylesheet\" type=\"text/css\" href=\"".asset('css/nepali.datepicker.v2.2.min.css')."\" />

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
            <input class=\"form-control\" name=\"from_date\" id=\"from_date\" value=\"$from_date\" placeholder=\"From Date\">
        </div>
        <div class=\"form-group col-sm-3\">
            <input class=\"form-control\" name=\"to_date\"  id=\"to_date\" value=\"$to_date\" placeholder=\"To Date\">
        </div>

        ";
    }
}
