<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Province;
use App\Models\District;
use App\Models\Municipality;
use App\Models\MunicipalityInfo;
use App\Models\Ward;
use App\User;
use Auth;

class HealthpostController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (User::checkAuthForIndexShowHealthpost() === false) {
            return redirect('/admin');
        }
        if (Auth::user()->role == "province") {
            $province_id = Province::modelProvinceInfo(Auth::user()->token)->province_id;
            $healthposts = Organization::where('province_id', $province_id)->latest()->get();
        } elseif (Auth::user()->role == "dho") {
            $district_id = District::modelDistrictInfo(Auth::user()->token)->district_id;
            $healthposts = Organization::where('district_id', $district_id)->latest()->get();
        } elseif (Auth::user()->role == "municipality") {
            $municipality_id = Municipality::modelMunicipalityInfo(Auth::user()->token)->municipality_id;
            $healthposts = Organization::where('municipality_id', $municipality_id)->latest()->get();
        } elseif (Auth::user()->role == "ward") {
            $ward_id = Ward::modelWard(Auth::user()->token)->id;
            $ward_no = Ward::getWardNo($ward_id);
            $municipality_id = Ward::modelWard(Auth::user()->token)->municipality_id;
            $healthposts = Organization::where([['municipality_id', $municipality_id], ['ward_no', $ward_no]])->latest()->get();
        } else {
            $healthposts = Organization::latest()->get();
        }

        return view('backend.healthpost.index', compact('healthposts'));
    }

    public function create()
    {
        if (User::checkAuthForCreateUpdateDelHealthpost() === false) {
            return redirect('/admin');
        }
        $token = Auth::user()->token;
        $municipalities = MunicipalityInfo::where('token', $token)->first();
        $provinces = Province::where('id', $municipalities->province_id)->get();
        $districts = District::where('id', $municipalities->district_id)->get();
        $hospital_type = '4';
        return view('backend.healthpost.create', compact('municipalities', 'provinces', 'districts', 'hospital_type'));
    }

    public function store(Request $request)
    {
        if (User::checkAuthForCreateUpdateDelHealthpost() === false) {
            return redirect('/admin');
        }

        $this->validateForm($request, $scenario = "create");

        $healthpost = Organization::create([
            'name' => $request->get('name'),
            'token' => uniqid() . time(),
            'province_id' => $request->get('province_id'),
            'district_id' => $request->get('district_id'),
            'municipality_id' => $request->get('municipality_id'),
            'ward_no' => $request->get('ward_no'),
            'hp_code' => $request->get('hp_code'),
            'phone' => $request->get('phone'),
            'address' => $request->get('address'),
            'longitude' => $request->get('longitude'),
            'lattitude' => $request->get('lattitude'),
            'hmis_uid' => $request->get('hmis_uid'),
            'status' => $request->get('status'),
            'no_of_beds' => $request->get('no_of_beds'),
            'no_of_ventilators' => $request->get('no_of_ventilators'),
            'no_of_icu' => $request->get('no_of_icu'),
            'hospital_type' => $request->get('hospital_type')
        ]);

        User::create([
            'token' => $healthpost->token,
            'username' => $request->get('username'),
            'email' => $request->get('email'),
            'password' => md5($request->get('password')),
            'role' => "healthpost",
        ]);

        $request->session()->flash('message', 'Data Inserted successfully');
        return redirect()->route('healthpost.index');
    }

    public function show($id)
    {
        if (User::checkAuthForIndexShowProvince() === false && User::checkAuthForIndexShowDho() === false && User::checkAuthForIndexShowMunicipality() === true && User::checkAuthForShowWard() === false) {
            return redirect('/admin');
        }

        $data = $this->findModel($id);
        $user = $this->findModelUser($data->token);
        return view('backend.healthpost.show', compact('data', 'user'));
    }

    public function edit($id)
    {
        if (User::checkAuthForCreateUpdateDelHealthpost() === false) {
            return redirect('/admin');
        }

        $data = $this->findModel($id);
        $token = Auth::user()->token;
        $municipalities = MunicipalityInfo::where('token', $token)->first();
        $provinces = Province::where('id', $municipalities->province_id)->get();
        $districts = District::where('id', $municipalities->district_id)->get();
        $wards = [];
        $user = $this->findModelUser($data->token);
        return view('backend.healthpost.edit', compact('data', 'provinces', 'districts', 'municipalities', 'wards', 'user'));
    }

    public function update(Request $request, $id)
    {
        if (User::checkAuthForCreateUpdateDelHealthpost() === false) {
            return redirect('/admin');
        }

        $this->validateForm($request, $scenario = "update");

        $healthpost = $this->findModel($id);

        $healthpost->update([
            'name' => $request->get('name'),
            'ward_no' => $request->get('ward_no'),
            'phone' => $request->get('phone'),
            'address' => $request->get('address'),
            'longitude' => $request->get('longitude'),
            'lattitude' => $request->get('lattitude'),
            'hmis_uid' => $request->get('hmis_uid'),
            'status' => $request->get('status'),
            'no_of_beds' => $request->get('no_of_beds'),
            'no_of_ventilators' => $request->get('no_of_ventilators'),
            'no_of_icu' => $request->get('no_of_icu'),
            'hospital_type' => $request->get('hospital_type')
        ]);

        $user = $this->findModelUser($healthpost->token);

        $user->update([
            'email' => $request->get('email'),
        ]);

        $request->session()->flash('message', 'Data Updated successfully');

        return redirect()->route('healthpost.index');
    }

    public function destroy($id, Request $request)
    {
        if (User::checkAuthForCreateUpdateDelHealthpost() === false) {
            return redirect('/admin');
        }

        $healthpost = $this->findModel($id);

        $healthpost->delete();

        $user = $this->findModelUser($healthpost->token);

        $user->delete();

        $request->session()->flash('message', 'Data Deleted successfully');

        return redirect()->route('healthpost.index');
    }

    protected function findModel($id)
    {

        if (Organization::find($id) === null) {
            abort(404, 'Page not found');
        } else {
            return $model = Organization::find($id);
        }
    }

    protected function findModelUser($token)
    {
        if (User::where('token', $token)->get()->first() === null) {
            abort(404, 'Page not found');
        } else {
            return $model = User::where('token', $token)->get()->first();
        }
    }

    public function organizationUpdate(Request $request)
    {     
        
        $data = $request->all();
        $orgaization = Organization::where('id', $data['organization_id'])->update(["vaccination_center_id" => $data['vaccinationCenter_id']]);
        $request->session()->flash('message', 'Data Update successfully');
        return redirect()->back();
    }

    protected function validateForm(Request $request, $scenario)
    {

        if ($scenario == "create") {
            $attributes = array(
                'name' => 'name',
                'province_id' => 'province',
                'district_id' => 'district',
                'ward_no' => 'ward no',
                'address' => 'address',
                'status' => 'status',
                'phone' => 'phone',
                'username' => 'Username',
                'password' => 'Password',
                're_password' => 'Confirm Password',
                'email' => 'Email'
            );

            $this->validate($request, [
                'name' => 'required|string',
                'province_id' => 'required|string',
                'district_id' => 'required|string',
                'hp_code' => 'required|unique:healthposts',
                'ward_no' => 'required|string',
                'address' => 'required|string',
                'status' => 'required|string',
                'phone' => 'nullable|regex:/(^([+0-9]+)(\d+)?$)/u|max:15',
                'username' => 'required|string|unique:users',
                'password' => 'required|string',
                're_password' => 'required|same:password',
                'email' => 'nullable|string|email|'
            ], array(), $attributes);

        } else {
            $attributes = array(
                'name' => 'name',
                'phone' => 'phone',
                'address' => 'address',
                'status' => 'status',
                'email' => 'Email'
            );

            $this->validate($request, [
                'name' => 'required|string',
                'phone' => 'nullable|regex:/(^([+0-9]+)(\d+)?$)/u|max:15',
                'address' => 'required|string',
                'status' => 'required|string',
                'email' => 'nullable|string|email|'
            ], array(), $attributes);
        }
    }
}