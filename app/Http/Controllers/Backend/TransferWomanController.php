<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SuspectedCase;
use App\Models\Province;
use App\Models\District;
use App\Models\Municipality; 
use App\Models\Organization;
use App\Http\Requests\TransferWomanRequest;
use App\Models\TransferWoman;
use Auth;

class TransferWomanController extends Controller
{
    public function transfer($from_hp_code, $woman_token){
    	$woman = SuspectedCase::where('token', $woman_token)->get()->first();
    	$provinces = Province::all();
        $districts = District::all();
        $municipalities = Municipality::all();
        $healthposts = Organization::all();
    	return view('backend.transfer-woman.transfer',compact('from_hp_code','woman','provinces','districts','municipalities','healthposts'));
    }

    public function transferStore(TransferWomanRequest $request)
    {
    	$transferWoman = new TransferWoman;
        $transferWoman->woman_token = $request->get('woman_token');
        $transferWoman->from_hp_code = $request->get('from_hp_code');
        $transferWoman->to_hp_code = $request->get('hp_code');
        $transferWoman->message = $request->get('message');
        $transferWoman->status = '1';
        $transferWoman->save();

        $request->session()->flash('message', 'Request sent to selected healthpost successfully');

        return redirect()->route('woman.index');
    }

    public function transferConfirm($from_hp_code, $woman_token){
        $woman = SuspectedCase::where('token', $woman_token)->get()->first();
        $healthpost = Organization::where('hp_code', $from_hp_code)->get()->first();
        $transfer = TransferWoman::where([['from_hp_code', $healthpost->hp_code], ['woman_token', $woman->token]])->get()->first();
        return view('backend.transfer-woman.transfer-confrim',compact('healthpost','woman','transfer'));
    }

    public function transferComplete(Request $request, $id){
        if($request->get('confirm')!==null){
            $transferWoman = $this->findModel($id);
            $transferWoman->status = '0';
            $transferWoman->save();
            $woman = $this->findModelWoman($transferWoman->woman_token);
            $loggedInToken = Auth::user()->token;
            $healthpost = Organization::where('token', $loggedInToken)->get()->first();
            $woman->hp_code = $healthpost->hp_code;
            $woman->save();
            $request->session()->flash('message', 'SuspectedCase admitted succesffully');
        }else{

            $transferWoman = $this->findModel($id);
            $transferWoman->delete();

            $request->session()->flash('cancel', 'Request is cancelled');
        }

        return redirect()->route('woman.index');
        
    }

    protected function findModel($id){

        if(TransferWoman::where('status', '1')->get()->first()===null)
        {
            abort(404);
        }else{
            return $model = TransferWoman::where('status', '1')->get()->first();
        }
    }



    protected function findModelWoman($token){

        if(SuspectedCase::where('token', $token)->get()->first()===null)
        {
            abort(404);
        }else{
            return $model = SuspectedCase::where('token', $token)->get()->first();
        }
    }
}
