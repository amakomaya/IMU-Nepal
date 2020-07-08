<?php

namespace App\Http\Controllers\Backend\Woman;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LabTest;
use App\Models\Woman;

class LabTestController extends Controller
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
        $labTests = LabTest::where('woman_token', $woman->token)->latest()->get();
        return view('backend.woman.lab-test.index',compact('labTests','woman'));
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
        return view('backend.woman.lab-test.create',compact('woman'));
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

         LabTest::create([
            'token'               => uniqid().time(),
            'date'               => $request->get('date'),
            'woman_token'               => $request->get('woman_token'),
            'urine_protin'               => $request->get('urine_protin'),
            'urine_sugar'               => $request->get('urine_sugar'),
            'blood_sugar'               => $request->get('blood_sugar'),
            'hbsag'               => $request->get('hbsag'),
            'vdrl'               => $request->get('vdrl'),
            'retro_virus'               => $request->get('retro_virus'),
            'other'               => $request->get('other'),
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
    	$labTestId = $request->get('lab_test_id');
        $data = $this->findModel($labTestId);
        return view('backend.woman.lab-test.show',compact('data'));
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
        //
    }



    protected function findModel($id){

        if(LabTest::find($id)===null)
        {
            abort(404,'Page not found');
        }else{
            return $model = LabTest::find($id);
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
            'date' => 'date',
            'urine_protin' => 'urine protin',
            'urine_sugar' => 'urine sugar',
            'blood_sugar' => 'blood sugar',
            'hbsag' => 'hbsag',
            'vdrl' => 'vdrl',
            'retro_virus' => 'retro virus',
            'other' => 'other',
        );
        
        $this->validate($request, [
            'date' => 'required|string',
            'urine_protin' => 'required|string',
            'urine_sugar' => 'required|string',
            'blood_sugar' => 'required|string',
            'hbsag' => 'required|string',
            'vdrl' => 'required|string',
            'retro_virus' => 'required|string',
            'other' => 'required|string',

        ],array(), $attributes);
	}


}



	
