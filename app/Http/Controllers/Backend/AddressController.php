<?php

namespace App\Http\Controllers\Backend;

use App\Models\District;
use App\Models\Municipality;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AddressController extends Controller
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

    public function districtSelectByProvince(Request $request)
    {
        $id = $request->get('id');
        echo '<select class="form-control" name="district_id" id="district_id" onchange="cDistrictOnchange($(this).val())" required>';
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
        echo '<select class="form-control" name="municipality_id" id="municipality_id" required>';
        $municipalities = Municipality::where('district_id', $id)->orderBy('municipality_name', 'asc')->get();
        echo "<option value=\"\">Select Municipality</option>";
        foreach ($municipalities as $municipality) {
            echo "<option value=\"$municipality->id\">$municipality->municipality_name</option>";
        }
        echo '<select>';
    }

    public function permDistrictSelectByProvince(Request $request)
    {
        $id = $request->get('id');
        echo '<select class="form-control" name="perm_district_id" id="perm_district_id" onchange="pDistrictOnchange($(this).val())" required>';
        $districts = District::where('province_id', $id)->orderBy('district_name', 'asc')->get();;
        echo "<option value=\"\">Select District</option>";
        foreach ($districts as $district) {
            echo "<option value=\"$district->id\">$district->district_name</option>";
        }
        echo '<select>';
    }

    public function permMunicipalitySelectByDistrict(Request $request)
    {
        $id = $request->get('id');
        echo '<select class="form-control" name="perm_municipality_id" id="perm_municipality_id" required>';
        $municipalities = Municipality::where('district_id', $id)->orderBy('municipality_name', 'asc')->get();
        echo "<option value=\"\">Select Municipality</option>";
        foreach ($municipalities as $municipality) {
            echo "<option value=\"$municipality->id\">$municipality->municipality_name</option>";
        }
        echo '<select>';
    }
}