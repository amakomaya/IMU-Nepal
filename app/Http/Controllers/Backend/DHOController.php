<?php

namespace App\Http\Controllers\Backend;

use App\Models\HealthProfessional;
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
       if(Auth::user()->role=="province"){
            $province_id = Province::modelProvinceInfo(Auth::user()->token)->province_id;
            $districtsList = Province::districtList($province_id);
            $data = DistrictInfo::whereIn('district_id', $districtsList)->latest()->get();
       }else{
            $data = DistrictInfo::latest()->get();
       }

        $health_professional = HealthProfessional::groupBy('checked_by')
            ->select('checked_by', \DB::raw('count(*) as total'))
            ->get();

        $merged = $data->map(function ($item) use ($health_professional) {

            $organization = Organization::where('district_id', $item->district_id)->pluck('token');

            $item['total'] = $health_professional->whereIn('checked_by', $organization)->sum('total');

//            $single = $count_hospital->where('municipality_id',$item->municipality_id)->first();

//            $item['hospital_total'] = ($single) ? $single->municipality_total : 0;

            return $item;
        });

       return view('backend.dho.index', [
           'data' => $merged
       ]);
    }

    public function create()
    {
        // if(User::checkAuthForCreateUpdateDelProvince()===false){
        //     return redirect('/admin');
        // }

        $districts = District::all();

        if(Auth::user()->role=="province"){
            $province_id = Province::modelProvinceInfo(Auth::user()->token)->province_id;
            $districts = $districts->where('province_id', $province_id);
       }

        return view('backend.dho.create',compact('districts'));
    }

    public function store(DHORequest $request)
    {
        // if(User::checkAuthForCreateUpdateDelProvince()===false){
        //     return redirect('/admin');
        // }

        $dho_info = [
            'district_id'         => $request->get('district_id'),
            'token'               => uniqid().time(),
            'phone'               => $request->get('phone'),
            'office_address'      => $request->get('office_address'),
            'office_longitude'    => $request->get('office_longitude'),
            'office_lattitude'    => $request->get('office_lattitude'),
            'status'              => $request->get('status'),
        ];

        $dho = DistrictInfo::create($dho_info);

        $user = [
            'token'               => $dho->token,
            'username'            => $request->get('username'),
            'email'               => $request->get('email'),
            'password'            => md5($request->get('password')),
            'role'                => "dho",
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
        return view('backend.dho.show',compact('data','user'));
    }

    public function edit($id)
    {
        // if(User::checkAuthForCreateUpdateDelProvince()===false){
        //     return redirect('/admin');
        // }

        $data = $this->findModel($id);
        $user = $this->findModelUser($data->token);
        $districts = District::all();
        if(Auth::user()->role=="province"){
            $province_id = Province::modelProvinceInfo(Auth::user()->token)->province_id;
            $districts = $districts->where('province_id', $province_id);
       }
        return view('backend.dho.edit', compact('data','user','districts'));
    }

    public function update(DHORequest $request, $id)
    {
        // if(User::checkAuthForCreateUpdateDelProvince()===false){
        //     return redirect('/admin');
        // }
        
        $dho = $this->findModel($id);
        
        $data = [
            'name'               => $request->get('name'),
            'phone'              => $request->get('phone'),
            'office_address'     => $request->get('office_address'),
            'office_longitude'   => $request->get('office_longitude'),
            'office_lattitude'   => $request->get('office_lattitude'),
            'status'             => $request->get('status'),
		];
        
    	$dho->update($data);

        $user = $this->findModelUser($dho->token);

        $user->update([
            'email'    => $request->get('email'),
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

    protected function findModel($id){

        if(DistrictInfo::find($id)===null)
        {
            abort(404);
        }else{
            return $model = DistrictInfo::find($id);
        }
    }

    protected function findModelUser($token){
        if(User::where('token', $token)->get()->first()===null){
            abort(404);
        }else{
            return $model = User::where('token', $token)->get()->first();
        }
    }
}