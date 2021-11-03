<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Http\Requests\HealthWorkerRequest;
use App\Models\OrganizationMember;
use App\Models\Province;
use App\Models\District;
use App\Models\Municipality;
use App\Models\Organization;
use App\User;
use Auth;
use App\Helpers\BackendHelper;
use Illuminate\Routing\UrlGenerator;
use Spatie\Permission\Models\Permission;

class HealthWorkerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public $url;

    public function __construct(UrlGenerator $url)
    {
        $this->middleware('auth');
        $this->url = $url;
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
            $healthWorkers = OrganizationMember::where([['province_id', $province_id],['role', 'healthworker']])->latest()->get();
       }elseif(Auth::user()->role=="dho"){
            $district_id = District::modelDistrictInfo(Auth::user()->token)->district_id;
            $healthWorkers = OrganizationMember::where([['district_id', $district_id],['role','healthworker']])->latest()->get();
       }elseif(Auth::user()->role=="healthpost"){
            $hp_code = Organization::where('token', Auth::user()->token)->get()->first()->hp_code;
            $healthWorkers = OrganizationMember::where([['hp_code', $hp_code],['role','healthworker']])->latest()->get();
       }else{
            $healthWorkers = OrganizationMember::where('role', 'healthworker')->latest()->get();
       }
        
        $role = "healthworker";
        return view('backend.health-worker.index',compact('healthWorkers','role'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->url->to('/');
        if(User::checkAuthForCreateUpdateDelHealthworker()===false){
            return redirect('/index');
        }
        $token = Auth::user()->token;
        $healthpost = Organization::where('token', $token)->get()->first();
        $provinces = Province::where('id', $healthpost->province_id)->get();
        $districts = District::where('id', $healthpost->district_id)->get();
        $municipalities = Municipality::where('id', $healthpost->municipality_id)->get();
        // $wards = Ward::where([['ward_no', $healthpost->ward_no],['municipality_id', $healthpost->municipality_id]])->get();
        $wards = [];
        $organizations = Organization::where('id', $healthpost->id)->get();
        $role = "healthworker";
        $permissions = Permission::whereNotIn('id', [1, 2, 3, 4, 5, 15, 17])->get();

        return view('backend.health-worker.create',compact('provinces','districts','municipalities','organizations','role', 'permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HealthWorkerRequest $request)
    {
        if(User::checkAuthForCreateUpdateDelHealthworker()===false){
            return redirect('/index');
        }

        if ($request->hasFile('image')) {
            $filename = Str::replaceFirst(" ","-", $request->name)."-".time().".".$request->image->extension();
            $request->image->storeAs('public/health-worker', $filename);//
            $path = './storage/health-worker/'.$filename;
            BackendHelper::resizeAndCropImage(120, 35, $path, $path );

        }else{
            $filename="";
        }

        $healthWorker = OrganizationMember::create([
            'token'               => uniqid().time(),
            'name'               => $request->get('name'),
            'province_id'               => $request->get('province_id'),
            'district_id'               => $request->get('district_id'),
            'municipality_id'               => $request->get('municipality_id'),
            'hp_code'               => $request->get('hp_code'),
            'image'               => $filename,
            'phone'               => $request->get('phone'),
            'post'               => $request->get('post'),
            'tole'               => $request->get('tole'),
            'registered_device'               => "web",
            'role'               => "healthworker",
            'longitude'               => $request->get('longitude'),
            'latitude'               => $request->get('latitude'),
            'status'               => $request->get('status'),
            'ward'               => $request->get('ward'),
        ]);


         $user = User::create([
            'token'               => $healthWorker->token,
            'username'               => $request->get('username'),
            'email'               => $request->get('email'),
            'imei'               => $request->get('imei'),
            'password'               => md5($request->get('password')),
            'role'               => "healthworker",
        ]);
        $permissionBundle = $request->get('permission_bundle')?json_decode($request->get('permission_bundle')):[];
        $permissions = $request->get('permissions')??[];
        $allPermissions = array_merge($permissionBundle, $permissions);

        $user->givePermissionTo($allPermissions);

        $request->session()->flash('message', 'Data Inserted successfully');

        return redirect()->route('health-worker.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(OrganizationMember::checkValidId($id)===false){
            return redirect('/index');
        }

        $data = $this->findModel($id);
        $user = $this->findModelUser($data->token);
        $role="healthworker";
        return view('backend.health-worker.show',compact('data','role','user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(User::checkAuthForCreateUpdateDelHealthworker()===false){
            return redirect('/index');
        }

        $data = $this->findModel($id);
        if(OrganizationMember::checkValidId($id)===false){
            return redirect('/index');
        }
        $token = Auth::user()->token;
        $healthpost = Organization::where('token', $token)->get()->first();
        $provinces = Province::where('id', $healthpost->province_id)->get();
        $districts = District::where('id', $healthpost->district_id)->get();
        $municipalities = Municipality::where('id', $healthpost->municipality_id)->get();
//        $wards = Ward::where([['ward_no', $healthpost->ward_no],['municipality_id', $healthpost->municipality_id]])->get();
        $organizations = Organization::where('id', $healthpost->id)->get();
        $user = $this->findModelUser($data->token);
        $role = "healthworker";
        $permissions = Permission::whereNotIn('id', [1, 2, 3, 4, 5, 15, 17])->get();
        return view('backend.health-worker.edit', compact('data','provinces','districts','municipalities','organizations','user','role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(HealthWorkerRequest $request, $id)
    {
        if(User::checkAuthForCreateUpdateDelHealthworker()===false){
            return redirect('/index');
        }

        $healthWorker = $this->findModel($id); 

        if(OrganizationMember::checkValidId($id)===false){
            return redirect('/index');
        }  

        if ($request->hasFile('image')) {
            $filename = Str::replaceFirst(" ","-", $request->name)."-".time().".".$request->image->extension();
            $request->image->storeAs('public/health-worker', $filename);
            $path = './storage/health-worker/'.$filename;
            BackendHelper::resizeAndCropImage(120, 35, $path, $path );
            $previous_image_path = $_SERVER['DOCUMENT_ROOT'].'/storage/health-worker/'.$healthWorker->image;
            if($healthWorker->image!=""){
                if(file_exists($previous_image_path)){
                    unlink($previous_image_path); 
                }
            }
            
        }else{
            $filename=$healthWorker->image;
        }     
        
        $healthWorker->update([
            'name'               => $request->get('name'),
            'province_id'               => $request->get('province_id'),
            'district_id'               => $request->get('district_id'),
            'municipality_id'               => $request->get('municipality_id'),
            'hp_code'               => $request->get('hp_code'),
            'phone'               => $request->get('phone'),
            'tole'               => $request->get('tole'),
            'post'               => $request->get('post'),
            'image'             => $filename,
            'longitude'               => $request->get('longitude'),
            'latitude'               => $request->get('latitude'),
            'status'               => $request->get('status'),
            'ward'               => $request->get('ward'),
        ]);
        $user = $this->findModelUser($healthWorker->token);

        $user->update([
            'email'               => $request->get('email'),
            'imei'               => $request->get('imei'),
        ]);
        $permissionBundle = $request->get('permission_bundle')?json_decode($request->get('permission_bundle')):[];
        $permissions = $request->get('permissions')??[];
        $allPermissions = array_merge($permissionBundle, $permissions);
        $user->syncPermissions($allPermissions);

        $request->session()->flash('message', 'Data Updated successfully');

        return redirect()->route('health-worker.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if(User::checkAuthForCreateUpdateDelHealthworker()===false){
            return redirect('/index');
        }
        
        $healthWorker = $this->findModel($id);

        
        if(OrganizationMember::checkValidId($id)===false){
            return redirect('/index');
        }

        if($healthWorker->image!=""){
            $previous_image_path = $_SERVER['DOCUMENT_ROOT'].'/storage/health-worker/'.$healthWorker->image;
            if(file_exists($previous_image_path)){
                unlink($previous_image_path); 
            }
        }
        
        $healthWorker->delete();

        $user = $this->findModelUser($healthWorker->token);

        $user->delete();

        $request->session()->flash('message', 'Data Deleted successfully');

        return redirect()->route('health-worker.index');
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