<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\HealthWorkerRequest;
use App\Models\OrganizationMember;
use App\Models\Province;
use App\Models\District;
use App\Models\Municipality;
use App\Models\Organization;
use App\Models\Ward;
use App\User;
use Auth;
use Spatie\Permission\Models\Permission;

class FchvController extends Controller
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
      if(Auth::user()->role=="province"){
            $province_id = Province::modelProvinceInfo(Auth::user()->token)->province_id;
            $healthWorkers = OrganizationMember::where([['province_id', $province_id],['role', 'fchv']])->with('municipality')->latest()->get();
       }elseif(Auth::user()->role=="dho"){
            $district_id = District::modelDistrictInfo(Auth::user()->token)->district_id;
            $healthWorkers = OrganizationMember::where([['district_id', $district_id],['role','fchv']])->with('municipality')->latest()->get();
       }elseif(Auth::user()->role=="healthpost"){
            $hp_code = Organization::where('token', Auth::user()->token)->get()->first()->hp_code;
            $healthWorkers = OrganizationMember::where([['hp_code', $hp_code],['role','fchv']])->with('municipality')->latest()->get();
       }else{
            $healthWorkers = OrganizationMember::where('role', 'fchv')->with('municipality')->latest()->get();
       }
        $role = 'fchv';
        return view('backend.fchv.index',compact('healthWorkers','role'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        if(Auth::user()->role=="province"){
            $provinceInfo = ProvinceInfo::where('token', Auth::user()->token)->first();
            $provinces = Province::where('id', $provinceInfo->province_id ?? '')->get();
            $districts = District::where('province_id', $provinceInfo->province_id ?? '')->get();
            $municipalities = Municipality::where('province_id', $provinceInfo->province_id ?? '')->get();
       }elseif(Auth::user()->role=="dho"){
            $district_id = District::modelDistrictInfo(Auth::user()->token)->district_id;
            $healthWorkers = OrganizationMember::where([['district_id', $district_id],['role','fchv']])->latest()->get();
       }elseif(Auth::user()->role=="healthpost"){
            $hp_code = Organization::where('token', Auth::user()->token)->get()->first()->hp_code;
            $healthWorkers = OrganizationMember::where([['hp_code', $hp_code],['role','fchv']])->latest()->get();
       }else{
            $healthWorkers = OrganizationMember::where('role', 'fchv')->latest()->get();
       }        

        $provinces = Province::get();
        $districts = District::get();
        $municipalities = Municipality::get();
        $permissions = Permission::all();
        $role = 'fchv';
        return view('backend.fchv.create',compact('provinces','districts','municipalities','role', 'permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // if(User::checkAuthForCreateUpdateDelHealthworker()===false){
        //     return redirect('/admin');
        // }

        $request->validate([
            'username' => 'required|unique:users',
            'name' => 'required',
            'password' => 'required|min:4'
        ]);

        $healthWorker = OrganizationMember::create([
            'token'               => uniqid().time(),
            'name'               => $request->get('name'),
            'province_id'               => $request->get('province_id'),
            'district_id'               => $request->get('district_id'),
            'municipality_id'               => $request->get('municipality_id'),
            'hp_code'               => $request->get('hp_code'),
            'phone'               => $request->get('phone'),
            'tole'               => $request->get('tole'),
            'post'               => $request->get('post'),
            'registered_device'               => "web",
            'role'               => "fchv",
            'longitude'               => $request->get('longitude'),
            'latitude'               => $request->get('latitude'),
            'status'               => $request->get('status'),
            'ward'               => $request->get('ward'),
        ]);


         $user = User::create([
            'token'               => $healthWorker->token,
            'username'               => $request->get('username'),
            'email'               => $request->get('email'),
            'imei'              => $request->get('imei'),
            'password'               => md5($request->get('password')),
            'role'               => "healthworker",
        ]);

         $user->givePermissionTo($request->get('permissions'));

        $request->session()->flash('message', 'Data Inserted successfully');

        return redirect()->route('fchv.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = $this->findModel($id);

        if(OrganizationMember::checkValidId($id)===false){
            return redirect('/admin');
        }
        $user = $this->findModelUser($data->token);
        $role="fchv";
        return view('backend.fchv.show',compact('data','role','user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // if(User::checkAuthForCreateUpdateDelHealthworker()===false){
        //     return redirect('/admin');
        // }

        $data = $this->findModel($id);

        // if(OrganizationMember::checkValidId($id)===false){
        //     return redirect('/admin');
        // }
        
        if(Auth::user()->role=="province"){
            $provinceInfo = ProvinceInfo::where('token', Auth::user()->token)->first();
            $provinces = Province::where('id', $provinceInfo->province_id ?? '')->get();
            $districts = District::where('province_id', $provinceInfo->province_id ?? '')->get();
            $municipalities = Municipality::where('province_id', $provinceInfo->province_id ?? '')->get();
       }elseif(Auth::user()->role=="dho"){
            $district_id = District::modelDistrictInfo(Auth::user()->token)->district_id;
            $healthWorkers = OrganizationMember::where([['district_id', $district_id],['role','fchv']])->latest()->get();
       }elseif(Auth::user()->role=="healthpost"){
            $hp_code = Organization::where('token', Auth::user()->token)->get()->first()->hp_code;
            $healthWorkers = OrganizationMember::where([['hp_code', $hp_code],['role','fchv']])->latest()->get();
       }else{
        $provinces = Province::get();
        $districts = District::get();
        $municipalities = Municipality::get();
        }
        $permissions = Permission::all();
        $user = $this->findModelUser($data->token);
        $role = 'fchv';
        return view('backend.fchv.edit', compact('permissions','data','provinces','districts','municipalities','user','role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // if(User::checkAuthForCreateUpdateDelHealthworker()===false){
        //     return redirect('/admin');
        // }

        $request->validate([
            'name' => 'required',
        ]);

        if(OrganizationMember::checkValidId($id)===false){
            return redirect('/admin');
        }

        $healthWorker = $this->findModel($id);        
        
        $healthWorker->update([
            'name'               => $request->get('name'),
            'province_id'               => $request->get('province_id'),
            'district_id'               => $request->get('district_id'),
            'municipality_id'               => $request->get('municipality_id'),
            'hp_code'               => $request->get('hp_code'),
            'phone'               => $request->get('phone'),
            'tole'               => $request->get('tole'),
            'post'               => $request->get('post'),
            'longitude'               => $request->get('longitude'),
            'latitude'               => $request->get('latitude'),
            'status'               => $request->get('status'),
            'ward'               => $request->get('ward'),
        ]);
        $user = $this->findModelUser($healthWorker->token);

        $user->update([
            'email'               => $request->get('email'),
            'imei'               => $request->get('imei')
        ]);

        $user->syncPermissions($request->get('permissions'));
        $request->session()->flash('message', 'Data Updated successfully');

        return redirect()->route('fchv.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        // if(User::checkAuthForCreateUpdateDelHealthworker()===false){
        //     return redirect('/admin');
        // }


        if(OrganizationMember::checkValidId($id)===false){
            return redirect('/admin');
        }
        
        $healthWorker = $this->findModel($id);
        
        $healthWorker->delete();

        $user = $this->findModelUser($healthWorker->token);

        $user->delete();

        $request->session()->flash('message', 'Data Deleted successfully');

        return redirect()->route('fchv.index');
    }

    protected function findModel($id)
    {
        if(OrganizationMember::find($id)===null)
        {
            abort(404,'Page not found');
        }else{
            return $model = OrganizationMember::find($id);
        }
    }

    protected function findModelUser($token){
        if(User::where('token', $token)->get()->first()===null){
            abort(404,'Page not found');
        }else{
            return $model = User::where('token', $token)->get()->first();
        }
    }
}