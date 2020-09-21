<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\MunicipalityRequest;
use App\Models\MunicipalityInfo;
use App\Models\Province;
use App\Models\District;
use App\Models\Municipality;
use App\User;
use Auth;

class MunicipalityController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       // if(User::checkAuthForIndexShowDho()===false && User::checkAuthForIndexShowMunicipality()===false){
       //      return redirect('/admin');
       // }
       if(Auth::user()->role=="province"){
            $province_id = Province::modelProvinceInfo(Auth::user()->token)->province_id;
            $municipalityInfos = MunicipalityInfo::where('province_id', $province_id)->latest()->get();
       }elseif(Auth::user()->role=="dho"){
            $district_id = District::modelDistrictInfo(Auth::user()->token)->district_id;
            $municipalityInfos = MunicipalityInfo::where('district_id', $district_id)->latest()->get();
       }else{
            $municipalityInfos = MunicipalityInfo::latest()->get();
       }
        
        return view('backend.municipality.index',compact('municipalityInfos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // if(User::checkAuthForCreateUpdateDelProvince()===false){
        //     return redirect('/admin');
        // }
        $provinces = Province::all();
        $districts = District::all();
        $municipalities = Municipality::all();
        if(Auth::user()->role=="province"){
            $province_id = Province::modelProvinceInfo(Auth::user()->token)->province_id;
            $districts = $districts->where('province_id', $province_id);
            $provinces = $provinces->where('id', $province_id);
            $municipalities = $municipalities->where('province_id', $province_id);
       }
       if(Auth::user()->role=="dho"){
            $district = District::modelDistrictInfo(Auth::user()->token)->first();
            $districts = $districts->where('id', $district->district_id);
            $provinces = $provinces->where('id', $districts->first()->province_id);

            $municipalities = $municipalities->where('district_id', $district->district_id);

           // $municipalityInfos = MunicipalityInfo::where('district_id', $district_id)->latest()->get();
       }
        return view('backend.municipality.create',compact('provinces','districts','municipalities'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MunicipalityRequest $request)
    {
        // if(User::checkAuthForCreateUpdateDelProvince()===false){
        //     return redirect('/admin');
        // }

         $municipality = MunicipalityInfo::create([
            'token'               => uniqid().time(),
            'phone'               => $request->get('phone'),
            'province_id'               => $request->get('province_id'),
            'district_id'               => $request->get('district_id'),
            'municipality_id'               => $request->get('municipality_id'),
            'office_address'               => $request->get('office_address'),
            'office_longitude'               => $request->get('office_longitude'),
            'office_lattitude'               => $request->get('office_lattitude'),
            'status'               => $request->get('status'),
        ]);



         User::create([
            'token'               => $municipality->token,
            'username'               => $request->get('username'),
            'email'               => $request->get('email'),
            'password'               => md5($request->get('password')),
            'role'               => "municipality",
        ]);

        $request->session()->flash('message', 'Data Inserted successfully');

        return redirect()->route('municipality.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       //  if(User::checkAuthForIndexShowProvince()===false && User::checkAuthForIndexShowDho()===false && User::checkAuthForIndexShowMunicipality()===false && User::checkAuthForShowWard()===false && User::checkAuthForShowHealthpost()===false){
       //      return redirect('/admin');
       // }

        $data = $this->findModel($id);
        return view('backend.municipality.show',compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // if(User::checkAuthForCreateUpdateDelProvince()===false){
        //     return redirect('/admin');
        // }

        $data = $this->findModel($id);
        $provinces = Province::all();
        $districts = District::all();
        $municipalities = Municipality::all();

        if(Auth::user()->role=="province"){
            $province_id = Province::modelProvinceInfo(Auth::user()->token)->province_id;
            $districts = $districts->where('province_id', $province_id);
            $provinces = $provinces->where('id', $province_id);
            $municipalities = $municipalities->where('province_id', $province_id);
       }

        $user = $this->findModelUser($data->token);
        return view('backend.municipality.edit', compact('data','provinces','districts','municipalities','user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MunicipalityRequest $request, $id)
    {         
        // if(User::checkAuthForCreateUpdateDelProvince()===false){
        //     return redirect('/admin');
        // }

        $municipalityInfo = $this->findModel($id);        
        
        $municipalityInfo->update([
            'phone'               => $request->get('phone'),
            'province_id'               => $request->get('province_id'),
            'district_id'               => $request->get('district_id'),
            'municipality_id'               => $request->get('municipality_id'),
            'office_address'               => $request->get('office_address'),
            'office_longitude'               => $request->get('office_longitude'),
            'office_lattitude'               => $request->get('office_lattitude'),
            'status'               => $request->get('status'),
        ]);

        $user = $this->findModelUser($municipalityInfo->token);

        $user->update([
            'email'               => $request->get('email'),

        ]);
        $request->session()->flash('message', 'Data Updated successfully');

        return redirect()->route('municipality.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        // if(User::checkAuthForCreateUpdateDelProvince()===false){
        //     return redirect('/admin');
        // }

        $municipalityInfo = $this->findModel($id);
        
        $municipalityInfo->delete();

        $user = $this->findModelUser($municipalityInfo->token);

        $user->delete();

        $request->session()->flash('message', 'Data Deleted successfully');

        return redirect()->route('municipality.index');
    }

    protected function findModel($id){

        if(MunicipalityInfo::find($id)===null)
        {
            abort(404,'Page not found');
        }else{
            return $model = MunicipalityInfo::find($id);
        }
    }

    protected function findModelUser($token){

        if(User::where('token', $token)->get()->first()===null)
        {
            abort(404,'Page not found');
        }else{
            return $model = User::where('token', $token)->get()->first();
        }
    }
}