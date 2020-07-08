<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\OutReachClinic;
use App\Models\Province;
use App\Models\District;
use App\Models\Municipality;
use App\Models\Healthpost;
use Auth;
use App\User;

class OutReachClinicController extends Controller
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
        $outReachClinics = OutReachClinic::all();
        if(Auth::user()->role=="province"){
            $province_id = Province::modelProvinceInfo(Auth::user()->token)->province_id;
            $outReachClinics = OutReachClinic::where([['province_id', $province_id]])->latest()->get();
       }elseif(Auth::user()->role=="dho"){
            $district_id = District::modelDistrictInfo(Auth::user()->token)->district_id;
            $outReachClinics = OutReachClinic::where([['district_id', $district_id]])->latest()->get();
       }elseif(Auth::user()->role=="healthpost"){
            $hp_code = Healthpost::where('token', Auth::user()->token)->get()->first()->hp_code;
            $outReachClinics = OutReachClinic::where([['hp_code', $hp_code]])->latest()->get();
       }else{
            $outReachClinics = OutReachClinic::all();
       }
        return view('backend.out-reach-clinic.index',compact('outReachClinics'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function create()
    {
        if(User::checkAuthForCreateUpdateDelHealthworker()===false){
            return redirect('/admin');
        }

        $token = Auth::user()->token;
        $healthpost = Healthpost::where('token', $token)->get()->first();
        $ward_no = $healthpost->ward_no;
        $provinces = Province::where('id', $healthpost->province_id)->get();
        $districts = District::where('id', $healthpost->district_id)->get();
        $municipalities = Municipality::where('id', $healthpost->municipality_id)->get();
        $hp_code = $healthpost->hp_code;
        return view('backend.out-reach-clinic.create',compact('provinces','districts','municipalities','ward_no','hp_code'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	 $this->validateForm($request);

         OutReachClinic::create([
            'name'               => $request->get('name'),
            'province_id'               => $request->get('province_id'),
            'district_id'               => $request->get('district_id'),
            'municipality_id'               => $request->get('municipality_id'),
            'ward_no'               => $request->get('ward_no'),
            'hp_code'               => $request->get('hp_code'),
            'address'               => $request->get('address'),
            'phone'               => $request->get('phone'),
            'longitude'               => $request->get('longitude'),
            'lattitude'               => $request->get('lattitude'),
            'status'               => $request->get('status'),
        ]);
        $request->session()->flash('message', 'Data Inserted successfully');

        return redirect()->route('out-reach-clinic.index');
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
        return view('backend.out-reach-clinic.show',compact('data'));
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
            return redirect('/admin');
        }

    	$data = $this->findModel($id);
        $token = Auth::user()->token;
        $healthpost = Healthpost::where('token', $token)->get()->first();
        $provinces = Province::where('id', $healthpost->province_id)->get();
        $districts = District::where('id', $healthpost->district_id)->get();
        $municipalities = Municipality::where('id', $healthpost->municipality_id)->get();
        return view('backend.out-reach-clinic.edit', compact('data','provinces','districts','municipalities'));
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
        $this->validateForm($request); 

        $outReachClinic = $this->findModel($id);        
        
    	$outReachClinic->update([
            'name'               => $request->get('name'),
            'province_id'               => $request->get('province_id'),
            'district_id'               => $request->get('district_id'),
            'municipality_id'               => $request->get('municipality_id'),
            'ward_no'               => $request->get('ward_no'),
            'hp_code'               => $request->get('hp_code'),
            'address'               => $request->get('address'),
            'phone'               => $request->get('phone'),
            'longitude'               => $request->get('longitude'),
            'lattitude'               => $request->get('lattitude'),
            'status'               => $request->get('status'),
		]);
		$request->session()->flash('message', 'Data Updated successfully');

        return redirect()->route('out-reach-clinic.index');
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
            return redirect('/admin');
        }

        $outReachClinic = $this->findModel($id);
        
        $outReachClinic->delete();

        $request->session()->flash('message', 'Data Deleted successfully');

        return redirect()->route('out-reach-clinic.index');
    }



    protected function findModel($id){

        if(OutReachClinic::find($id)===null)
        {
            abort(404,'Page not found');
        }else{
            return $model = OutReachClinic::find($id);
        }
    }
	
	protected function validateForm(Request $request){

		$attributes = array(
            'name' => 'name',
            'province_id' => 'province id',
            'district_id' => 'district id',
            'municipality_id' => 'municipality id',
            'ward_no' => 'ward no',
            'hp_code' => 'hp code',
            'address' => 'address',
            'status' => 'status',
        );
        
        $this->validate($request, [
            'name' => 'required|string',
            'province_id' => 'required|integer',
            'district_id' => 'required|integer',
            'municipality_id' => 'required|integer',
            'ward_no' => 'required|integer',
            'hp_code' => 'required|string',
            'address' => 'required|string',
            'status' => 'required|numeric',
            'phone' => 'nullable|regex:/(^([+0-9]+)(\d+)?$)/u|max:15',

        ],array(), $attributes);
	}
}
