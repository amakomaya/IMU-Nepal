<?php

namespace App\Http\Controllers\ContentApp;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ContentApp\WomanSurvey;
use Carbon\Carbon;
use App\Models\Woman\WomanRegisterApp;
use App\Models\Healthpost;
use App\Models\Municipality;
use App\Models\District;
use App\Models\Woman;
use App\User;

class WomanRegistrationController extends Controller
{
   public function __construct(){
      $this->middleware('auth');
  }

  public function index(){
    $rows = WomanRegisterApp::all()->map(function ($woman){
            $woman['mis_data'] = json_decode($woman['mis_data'], true);
              return $woman;
        })->where('register_as', 'mom')->sortByDesc('created_at'); 
    $districts = District::orderBy('district_name', 'asc')->get();;
    $municipalities = Municipality::orderBy('municipality_name', 'asc')->get();
    $healthposts = Healthpost::get(['name', 'hp_code'])->sortBy('name');
    return view('content-app.woman.index', compact('rows', 'healthposts', 'districts', 'municipalities'));
  }

  public function delete(Request $request, $id){
    WomanRegisterApp::findOrFail($id)->delete();
    $request->session()->flash('message', 'Woman deleted');
    return redirect()->back();  
  }

    public function edit(Request $request, $id){
    $row = WomanRegisterApp::findOrFail($id);
    $districts = District::orderBy('district_name', 'asc')->get();;
    $municipalities = Municipality::orderBy('municipality_name', 'asc')->get();
    $healthposts = Healthpost::get(['name', 'hp_code'])->sortBy('name');
    return view('content-app.woman.edit', compact('row', 'healthposts', 'districts', 'municipalities'));
  }

   public function update(Request $request, $id){
    dd($request->all());
    $row = WomanRegisterApp::findOrFail($id);
    $districts = District::orderBy('district_name', 'asc')->get();;
    $municipalities = Municipality::orderBy('municipality_name', 'asc')->get();
    $healthposts = Healthpost::get(['name', 'hp_code'])->sortBy('name');
    return view('content-app.woman.edit', compact('row', 'healthposts', 'districts', 'municipalities'));
  }

  public function sendCare(Request $request){
    $data = $request->all();
    $data['token'] = $data['municipality_id'].'-'.str_random();
    $data['ward'] = $data['ward_no'];
    $data['role'] = 'woman';
    $data['status'] = 1;
    $data['anc_status'] = 0;
    $data['delivery_status'] = 0;
    $data['labtest_status'] = 0;
    $data['pnc_status'] = 0;
    $data['registered_device'] = 'mobile';
    Woman::create($data);
    User::create($data);
    WomanRegisterApp::where('username', $data['username'])->update(array('status' => 0));
    $request->session()->flash('message', 'Records successfully send');
    return redirect('admin/content-app/woman-registered');  
  }

  public function showSendCare(Request $request, $id)
  {
    $row = WomanRegisterApp::findOrFail($id);
    $districts = District::orderBy('district_name', 'asc')->get();;
    $municipalities = Municipality::orderBy('municipality_name', 'asc')->get();
    $healthposts = Healthpost::get(['name', 'hp_code'])->sortBy('name');
    return view('content-app.woman.send-care', compact('row', 'healthposts', 'districts', 'municipalities'));
  }
}