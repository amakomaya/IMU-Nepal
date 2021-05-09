<?php

namespace App\Http\Controllers\Backend;

use App\Models\HealthProfessional;
use App\Models\Municipality;
use App\Models\MunicipalityInfo;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Province;
use App\Models\District;
use App\Http\Requests\DHORequest;
use App\Models\DistrictInfo;
use App\User;
use Auth;

class DHOController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // if(User::checkAuthForIndexShowProvince()===false && User::checkAuthForIndexShowDho()===false){
        //      return redirect('/admin');
        // }
        if (Auth::user()->role == "province") {
            $province_id = Province::modelProvinceInfo(Auth::user()->token)->province_id;
            $districtsList = Province::districtList($province_id);
            $data = DistrictInfo::whereIn('district_id', $districtsList)->latest()->get();
        } else {
            $data = DistrictInfo::latest()->get();
        }
        return view('backend.dho.index', [
            'data' => $data
        ]);
    }

    public function create()
    {
        // if(User::checkAuthForCreateUpdateDelProvince()===false){
        //     return redirect('/admin');
        // }

        $districts = District::all();

        if (Auth::user()->role == "province") {
            $province_id = Province::modelProvinceInfo(Auth::user()->token)->province_id;
            $districts = $districts->where('province_id', $province_id);
        }

        return view('backend.dho.create', compact('districts'));
    }

    public function store(DHORequest $request)
    {
        // if(User::checkAuthForCreateUpdateDelProvince()===false){
        //     return redirect('/admin');
        // }

        $dho_info = [
            'district_id' => $request->get('district_id'),
            'token' => uniqid() . time(),
            'phone' => $request->get('phone'),
            'office_address' => $request->get('office_address'),
            'office_longitude' => $request->get('office_longitude'),
            'office_lattitude' => $request->get('office_lattitude'),
            'status' => $request->get('status'),
        ];

        $dho = DistrictInfo::create($dho_info);

        $user = [
            'token' => $dho->token,
            'username' => $request->get('username'),
            'email' => $request->get('email'),
            'password' => md5($request->get('password')),
            'role' => "dho",
        ];

        User::create($user);

        $request->session()->flash('message', 'Data Inserted successfully');
        return redirect()->route('dho.index');

    }

    public function show($id)
    {
        // if(User::checkAuthForIndexShowProvince()===false && User::checkAuthForIndexShowDho()===false){
        //      return redirect('/admin');
        // }

        $data = $this->findModel($id);
        $user = $this->findModelUser($data->token);
        return view('backend.dho.show', compact('data', 'user'));
    }

    public function edit($id)
    {
        // if(User::checkAuthForCreateUpdateDelProvince()===false){
        //     return redirect('/admin');
        // }

        $data = $this->findModel($id);
        $user = $this->findModelUser($data->token);
        $districts = District::all();
        if (Auth::user()->role == "province") {
            $province_id = Province::modelProvinceInfo(Auth::user()->token)->province_id;
            $districts = $districts->where('province_id', $province_id);
        }
        return view('backend.dho.edit', compact('data', 'user', 'districts'));
    }

    public function update(DHORequest $request, $id)
    {
        // if(User::checkAuthForCreateUpdateDelProvince()===false){
        //     return redirect('/admin');
        // }

        $dho = $this->findModel($id);

        $data = [
            'name' => $request->get('name'),
            'phone' => $request->get('phone'),
            'office_address' => $request->get('office_address'),
            'office_longitude' => $request->get('office_longitude'),
            'office_lattitude' => $request->get('office_lattitude'),
            'status' => $request->get('status'),
        ];

        $dho->update($data);

        $user = $this->findModelUser($dho->token);

        $user->update([
            'email' => $request->get('email'),
        ]);

        $request->session()->flash('message', 'Data Updated successfully');
        return redirect()->route('dho.index');
    }

    public function destroy(Request $request, $id)
    {
        // if(User::checkAuthForCreateUpdateDelProvince()===false){
        //     return redirect('/admin');
        // }

        $dho = $this->findModel($id);
        $dho->delete();
        $user = $this->findModelUser($dho->token);
        $user->delete();

        $request->session()->flash('message', 'Data Deleted successfully');
        return redirect()->back();
    }

    protected function findModel($id)
    {

        if (DistrictInfo::find($id) === null) {
            abort(404);
        } else {
            return $model = DistrictInfo::find($id);
        }
    }

    protected function findModelUser($token)
    {
        if (User::where('token', $token)->get()->first() === null) {
            abort(404);
        } else {
            return $model = User::where('token', $token)->get()->first();
        }
    }

    public function findMunicipalities()
    {
        if (auth()->user()->role == 'dho'){
            $district_id = District::modelDistrictInfo(Auth::user()->token)->district_id;
            $data = MunicipalityInfo::where('district_id', $district_id)->get();
            $datas = $data->map(
                function ($items) {
                    $data['name'] = $items->name;
                    $data['office_address'] = $items->office_address;
                    $data['token'] = $items->token;
                    return $data;
                }
            );
            $organizations = Organization::where('district_id', $district_id)->get();
            $checked_by = collect($data->pluck('token'))->merge($organizations->pluck('token'));
            $org_user = HealthProfessional::whereIn('checked_by', $checked_by)->groupBy('organization_name')
                ->select(['organization_name','organization_phn', 'organization_address'])
                ->orderBy('organization_name', 'asc')
                ->get();
        }
        if (auth()->user()->role == 'municipality'){
            $municipality_id = MunicipalityInfo::where('token',Auth::user()->token)->first()->municipality_id;
            $data = Organization::where('municipality_id', $municipality_id)->get();
            $datas = $data->map(
                function ($items) {
                    $data['name'] = $items->name;
                    $data['office_address'] = $items->address;
                    $data['token'] = $items->token;
                    return $data;
                }
            );
            $organizations = Organization::where('municipality_id', $municipality_id)->get();
            $checked_by = collect($data->pluck('token'))->merge($organizations->pluck('token'));
            $org_user = HealthProfessional::whereIn('checked_by', $checked_by)->groupBy('organization_name')
                ->select(['organization_name','organization_phn', 'organization_address'])
                ->orderBy('organization_name', 'asc')
                ->get();
        }

        return view('backend.dho.vaccination', compact('datas', 'organizations', 'org_user'));
    }

    public function findAllHealthProfessionalDatas(Request $request)
    {
        $array = explode(',', $request->token);
        if (auth()->user()->role == 'dho'){
            $municipality_ids = MunicipalityInfo::whereIn('token', $array)->pluck('municipality_id');
            $organizations_token = Organization::whereIn('municipality_id', $municipality_ids)->pluck('token');
            $checked_by_tokens = collect($array)->merge($organizations_token)->toArray();
            $data = HealthProfessional::whereIn('checked_by', $checked_by_tokens)->whereNull('vaccinated_status')->pluck('id');
            $data2 = HealthProfessional::whereIn('organization_name', $array)->whereNull('vaccinated_status')->pluck('id');

            $data_total = collect($data)->merge($data2)->toArray();
            echo implode(',', $data_total);
        }else{
            $data = HealthProfessional::whereIn('checked_by', $array)->whereNull('vaccinated_status')->pluck('id')->toArray();
            echo implode(',', $data);
        }
    }
}