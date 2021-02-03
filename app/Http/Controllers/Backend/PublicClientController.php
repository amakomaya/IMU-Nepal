<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Municipality;
use App\Models\PublicClient;
use Illuminate\Http\Request;

class PublicClientController extends Controller
{
    public function create(){
        $province_id = 1;
        $district_id = 1;
        $municipality_id = 1;
        $districts = District::where('province_id', $province_id)->orderBy('district_name', 'asc')->get();
        $municipalities = Municipality::where('district_id', $district_id)->orderBy('municipality_name', 'asc')->get();

        return view('backend.public-client.add',compact('province_id', 'district_id', 'municipality_id','districts', 'municipalities'));
    }
    public function store(Request $request){
        $row = $request->all();
        $row['name'] = $request->full_name;
        $row['status'] = 1;
        $id = PublicClient::create($row)->id;
        $id = str_pad($id, 6, "0", STR_PAD_LEFT);
        return redirect()->back()->
        with('message', "Your information is successfully submitted. Your serial Number is : $id  And Please carefully note your Serial Numbers and your registered Ph number : " . $row['phone']);
    }
}
