<?php

namespace App\Http\Controllers\Backend;

use App\Models\HealthProfessional;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProvinceInfo;
use App\Models\Province;
use App\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ProvinceRequest;
use Auth;

class ProvinceController extends Controller
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
        if(User::checkAuthForIndexShowProvince()===false){
            return redirect('/admin');
        }

        $provinces = provinceInfo::latest()->get();
        $health_professional = HealthProfessional::groupBy('checked_by')
            ->select('checked_by', \DB::raw('count(*) as total'))
            ->get();

        $merged = $provinces->map(function ($item) use ($health_professional) {

            $organization = Organization::where('province_id', $item->province_id)->pluck('token');

            $item['total'] = $health_professional->whereIn('checked_by', $organization)->sum('total');

            return $item;
        });

        return view('backend.province.index',[
            'provinces' => $merged
        ]);
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
        return view('backend.province.create',compact('provinces'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(provinceRequest $request)
    {
        // if(User::checkAuthForCreateUpdateDelProvince()===false){
        //     return redirect('/admin');
        // }

         $province =provinceInfo::create([
            'province_id'               => $request->get('province_id'),
            'token'               => uniqid().time(),
            'phone'               => $request->get('phone'),
            'office_address'               => $request->get('office_address'),
            'office_longitude'               => $request->get('office_longitude'),
            'office_lattitude'               => $request->get('office_lattitude'),
            'status'               => $request->get('status'),
        ]);

         User::create([
            'token'               => $province->token,
            'username'               => $request->get('username'),
            'email'               => $request->get('email'),
            'password'               => md5($request->get('password')),
            'role'               => "province",
        ]);



        $request->session()->flash('message', 'Data Inserted successfully');

        return redirect()->route('province.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // if(User::checkAuthForIndexShowProvince()===false){
        //     return redirect('/admin');
        // }

        $data = $this->findModelprovince($id);
        $user = $this->findModelUser($data->token);
        return view('backend.province.show',compact('data','user'));
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

        $data = $this->findModelprovince($id);
        $user = $this->findModelUser($data->token);
        $provinces = Province::all();
        return view('backend.province.edit', compact('data','user','provinces'));
	}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(provinceRequest $request, $id)
    {
        // if(User::checkAuthForCreateUpdateDelProvince()===false){
        //     return redirect('/admin');
        // }

        $province = $this->findModelprovince($id);        
        
    	$province->update([
            'name'               => $request->get('name'),
            'phone'               => $request->get('phone'),
            'office_address'               => $request->get('office_address'),
            'office_longitude'               => $request->get('office_longitude'),
            'office_lattitude'               => $request->get('office_lattitude'),
            'status'               => $request->get('status'),
		]);


        $user = $this->findModelUser($province->token);

        $user->update([
            'email'               => $request->get('email'),
        ]);

		$request->session()->flash('message', 'Data Updated successfully');

        return redirect()->route('province.index');
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

        $province = $this->findModelprovince($id);

        $province->delete();

        $user = $this->findModelUser($province->token);

        $user->delete();

        $request->session()->flash('message', 'Data Deleted successfully');

        return redirect()->route('province.index');
    }

    protected function findModelprovince($id){

        if(ProvinceInfo::find($id)===null){
            abort(404);
        }else{
            return $model = provinceInfo::find($id);
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
