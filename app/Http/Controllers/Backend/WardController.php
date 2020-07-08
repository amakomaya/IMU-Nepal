<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\WardRequest;
use App\Models\Ward;
use App\Models\Province;
use App\Models\District;
use App\Models\Municipality;
use App\Models\MunicipalityInfo;
use App\User;
use Auth;

class WardController extends Controller
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
        if(User::checkAuthForIndexShowWard()===false){
            return redirect('/admin');
        }

       if(Auth::user()->role=="province"){
            $province_id = Province::modelProvinceInfo(Auth::user()->token)->province_id;
            $wards = Ward::where('province_id', $province_id)->latest()->get();
       }elseif(Auth::user()->role=="dho"){
            $district_id = District::modelDistrictInfo(Auth::user()->token)->district_id;
            $wards = Ward::where('district_id', $district_id)->latest()->get();
       }elseif(Auth::user()->role=="municipality"){
            $municipality_id = Municipality::modelMunicipalityInfo(Auth::user()->token)->municipality_id;
            $wards = Ward::where('municipality_id', $municipality_id)->latest()->get();
       }else{
            $wards = Ward::latest()->get();
       }

        
        return view('backend.ward.index',compact('wards'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        if(\App\User::getFirstLoggedInRole(\Request::session()->get('user_token')) == 'Main'){
            $token = Auth::user()->token;
            $municipality = MunicipalityInfo::where('token', $token)->get()->first();
            $provinces = Province::where('id', $municipality->province_id)->get();
            $districts = District::where('id', $municipality->district_id)->get();
            $municipalities = Municipality::where('id', $municipality->municipality_id)->get();
            return view('backend.ward.create',compact('provinces','districts','municipalities'));
            }
        return redirect('/admin');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WardRequest $request)
    {
        if(User::checkAuthForCreateUpdateDelWard()===false){
            return redirect('/admin');
        }

         $ward = Ward::create([
            'ward_no'               => $request->get('ward_no'),
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
            'token'               => $ward->token,
            'username'               => $request->get('username'),
            'email'               => $request->get('email'),
            'password'               => md5($request->get('password')),
            'role'               => "ward",
        ]);

        $request->session()->flash('message', 'Data Inserted successfully');

        return redirect()->route('ward.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        if(\App\User::getFirstLoggedInRole(\Request::session()->get('user_token')) != 'Main' && Auth::user()->role !="municipality"){
            return redirect('/admin');
       }


        $data = $this->findModel($id);
        return view('backend.ward.show',compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(\App\User::getFirstLoggedInRole(\Request::session()->get('user_token')) != 'Main' && Auth::user()->role !="municipality"){
            return redirect('/admin');
        }

        if(Ward::checkValidId($id)===false){
            return redirect('/admin');
        }

        $data = $this->findModel($id);
        
        $token = Auth::user()->token;
        $municipality = MunicipalityInfo::where('token', $token)->get()->first();
        $provinces = Province::where('id', $municipality->province_id)->get();
        $districts = District::where('id', $municipality->district_id)->get();
        $municipalities = Municipality::where('id', $municipality->municipality_id)->get();
        $user = $this->findModelUser($data->token);
        return view('backend.ward.edit', compact('data','provinces','districts','municipalities','user'));
	}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(WardRequest $request, $id)
    {
        if(\App\User::getFirstLoggedInRole(\Request::session()->get('user_token')) != 'Main' && Auth::user()->role !="municipality"){
            return redirect('/admin');
        }

        if(Ward::checkValidId($id)===false){
            return redirect('/admin');
        }

        $ward = $this->findModel($id);        
        
    	$ward->update([
            'ward_no'               => $request->get('ward_no'),
            'phone'               => $request->get('phone'),
            'province_id'               => $request->get('province_id'),
            'district_id'               => $request->get('district_id'),
            'municipality_id'               => $request->get('municipality_id'),
            'office_address'               => $request->get('office_address'),
            'office_longitude'               => $request->get('office_longitude'),
            'office_lattitude'               => $request->get('office_lattitude'),
            'status'               => $request->get('status'),
		]);

        $user = $this->findModelUser($ward->token);

        $user->update([
            'email'               => $request->get('email'),

        ]);

		$request->session()->flash('message', 'Data Updated successfully');

        return redirect()->route('ward.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if(\App\User::getFirstLoggedInRole(\Request::session()->get('user_token')) != 'Main'){
            return redirect('/admin');
        }

        if(Ward::checkValidId($id)===false){
            return redirect('/admin');
        }
        
        $ward = $this->findModel($id);
        
        $ward->delete();

        $user = $this->findModelUser($ward->token);

        $user->delete();

        $request->session()->flash('message', 'Data Deleted successfully');

        return redirect()->route('ward.index');
    }



    protected function findModel($id){
        if(Ward::find($id)===null)
        {
            abort(404,'Page not found');
        }else{
            return $model = Ward::find($id);
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
