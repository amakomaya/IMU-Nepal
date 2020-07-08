<?php

namespace App\Http\Controllers\Backend\Woman;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Pnc;
use App\Models\Woman;

class PncController extends Controller
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
    public function index(Request $request)
    {
    	$womanId = $request->get('woman_id');
        $woman = $this->findModelWoman($womanId);
        $pncs = Pnc::where('woman_token',$woman->token)->latest()->get();
        return view('backend.woman.pnc.index',compact('pncs','woman'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
    	$womanId = $request->get('woman_id');
        $woman = $this->findModelWoman($womanId);
        return view('backend.woman.pnc.create',compact('woman'));
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
    	 $modelWoman = $this->findModelWoman($request->get('woman_id'));

         Pnc::create([
            'token'               => uniqid().time(),
            'woman_token'               => $modelWoman->token,
            'delivery_date'               => $request->get('delivery_date'),
            'delivery_time'               => $request->get('delivery_time'),
            'mother_status'               => $request->get('mother_status'),
            'baby_status'               => $request->get('baby_status'),
            'advice'               => $request->get('advice'),
            'checked_by'               => "doctor123",
            'hp_code'               => $modelWoman->hp_code,
            'status'               => $request->get('status'),
        ]);
        $request->session()->flash('message', 'Data Inserted successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $pncId = $request->get('pnc_id');
        $data = $this->findModel($pncId);
        return view('backend.woman.pnc.show',compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    	//
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $pnc = $this->findModel($id);
        
        $pnc->delete();

        $request->session()->flash('message', 'Data Deleted successfully');

        return redirect()->route('pnc.index');
    }


    protected function findModel($id){

        if(Pnc::find($id)===null)
        {
            abort(404,'Page not found');
        }else{
            return $model = Pnc::find($id);
        }
    }


    protected function findModelWoman($id){

        if(Woman::find($id)===null)
        {
            abort(404,'Page not found');
        }else{
            return $model = Woman::find($id);
        }
    }
	
	protected function validateForm(Request $request){

		$attributes = array(
            'delivery_date' => 'delivery date',
            'delivery_time' => 'delivery time',
            'mother_status' => 'mother status',
            'baby_status' => 'baby status',
            'advice' => 'advice',
            'status' => 'status',
        );
        
        $this->validate($request, [
            'delivery_date' => 'required|string',
            'delivery_time' => 'required|string',
            'mother_status' => 'required|string',
            'baby_status' => 'required|string',
            'advice' => 'required|string',
            'status' => 'required|numeric',

        ],array(), $attributes);
	}


}