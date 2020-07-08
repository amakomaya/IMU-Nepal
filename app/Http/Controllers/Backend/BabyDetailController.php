<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BabyDetail;
use App\Models\Healthpost;
use App\Models\MunicipalityInfo;
use Auth;
use App\User;

class BabyDetailController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('backend.baby-detail.index');
    }

    public function create()
    {
        $healthposts = Healthpost::all();
        return view('backend.baby-detail.create',compact('healthposts'));
    }

    public function store(Request $request)
    {
    	 $this->validateForm($request);
         BabyDetail::create([
            'token'                     => $request->get('token'),
            'delivery_token'            => $request->get('delivery_token'),
            'gender'                    => $request->get('gender'),
            'weight'                    => $request->get('weight'),
            'premature_birth'           => $request->get('premature_birth'),
            'baby_alive'                => $request->get('baby_alive'),
            'baby_status'               => $request->get('baby_status'),
            'advice'                    => $request->get('advice'),
            'hp_code'                   => $request->get('hp_code'),
            'date_of_birth_reg'         => $request->get('date_of_birth_reg'),
            'birth_certificate_reg_no'  => $request->get('birth_certificate_reg_no'),
            'family_record_form_no'     => $request->get('family_record_form_no'),
            'baby_name'                 => $request->get('baby_name'),
            'child_information_by'      => $request->get('child_information_by'),
            'grand_father_name'         => $request->get('grand_father_name'),
            'grand_mother_name'         => $request->get('grand_mother_name'),
            'father_citizenship_no'     => $request->get('father_citizenship_no'),
            'mother_citizenship_no'     => $request->get('mother_citizenship_no'),
            'local_registrar_fullname'  => $request->get('local_registrar_fullname'),
            'status'                    => $request->get('status'),
        ]);
        $request->session()->flash('message', 'Data Inserted successfully');
        return redirect()->route('child.index');
    }

    public function show($id)
    {
        if(BabyDetail::checkValidId($id)===false){
            return redirect('/admin');
        }
        $data = $this->findModel($id);
        $token = Auth::user()->token;
        $municipality = MunicipalityInfo::select('municipalities.municipality_name as name','districts.district_name','provinces.province_name')
                            ->join('municipalities','municipalities.id','=','municipality_infos.municipality_id')
                            ->join('districts','districts.id','=','municipality_infos.district_id')
                            ->join('provinces','provinces.id','=','municipality_infos.province_id')
                            ->where('municipality_infos.token', $token)
                            ->get()
                            ->first();
        $municipality_name = $municipality->name;
        $district_name = $municipality->district_name;
        $province_name = $municipality->province_name;
        return view('backend.baby-detail.show',compact('data','municipality_name', 'district_name','province_name'));
    }

    public function edit($id)
    {
        if(BabyDetail::checkValidId($id)===false){
            return redirect('/admin');
        }
    	$data = $this->findModel($id);
        $healthposts = Healthpost::all();
        return view('backend.baby-detail.edit', compact('data','healthposts'));
	}

    public function update(Request $request, $id)
    {
        $this->validateForm($request); 
        $babyDetail = $this->findModel($id); 
    	$babyDetail->update([
            'date_of_birth_reg'               => $request->get('date_of_birth_reg'),
            'birth_certificate_reg_no'        => $request->get('birth_certificate_reg_no'),
            'family_record_form_no'           => $request->get('family_record_form_no'),
            'baby_name'                       => $request->get('baby_name'),
            'child_information_by'            => $request->get('child_information_by'),
            'grand_father_name'               => $request->get('grand_father_name'),
            'grand_mother_name'               => $request->get('grand_mother_name'),
            'father_citizenship_no'           => $request->get('father_citizenship_no'),
            'mother_citizenship_no'           => $request->get('mother_citizenship_no'),
            'local_registrar_fullname'        => $request->get('local_registrar_fullname'),
		]);
        $babyDetail->save();
		$request->session()->flash('message', 'Data Updated successfully');
        return redirect()->route('child.index');
    }

    public function destroy($id, Request $request)
    {
        $babyDetail = $this->findModel($id);
        $babyDetail->delete();
        $request->session()->flash('message', 'Data Deleted successfully');
        return redirect()->route('child.index');
    }

    protected function findModel($id){
        if(BabyDetail::find($id)===null)
        {
            abort(404,'Page not found');
        }else{
            return $model = BabyDetail::find($id);
        }
    }
	
	protected function validateForm(Request $request){}

}	