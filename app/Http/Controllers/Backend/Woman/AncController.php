<?php

namespace App\Http\Controllers\Backend\Woman;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Anc;
use App\Models\Woman;
use App\Models\Healthpost;

class AncController extends Controller
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
        $ancs = Anc::where('woman_token', $woman->token)->latest()->get();
        return view('backend.woman.anc.index',compact('ancs','woman'));
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
        $healthposts = Healthpost::all();
        return view('backend.woman.anc.create',compact('healthposts','woman'));
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
        $token = uniqid().time();
        $modelWoman = $this->findModelWoman($request->get('woman_id'));
         Anc::create([
            'token'               => $token,
            'woman_token'               => $modelWoman->token,
            'visit_date'               => $request->get('visit_date'),
            'weight'               => $request->get('weight'),
            'anemia'               => $request->get('anemia'),
            'swell'               => $request->get('swell'),
            'blood_pressure'               => $request->get('blood_pressure'),
            'uterus_height'               => $request->get('uterus_height'),
            'baby_presentation'               => $request->get('baby_presentation'),
            'baby_heart_beat'               => $request->get('baby_heart_beat'),
            'other'               => $request->get('other'),
            'iron_pills'               => $request->get('iron_pills'),
            'worm_medicine'               => $request->get('worm_medicine'),
            'td_vaccine'               => $request->get('td_vaccine'),
            'checked_by'               => "doctor_token",
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
        $ancId = $request->get('anc_id');
        $data = $this->findModel($ancId);
        return view('backend.woman.anc.show',compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $womanId = $request->get('woman_id');
        $woman = $this->findModelWoman($womanId);
        $healthposts = Healthpost::all();
        return view('backend.woman.anc.edit',compact('healthposts','woman'));    
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

        if(Anc::find($id)===null)
        {
            abort(404,'Page not found');
        }else{
            return $model = Anc::find($id);
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
            'visit_date' => 'visit date',
            'weight' => 'weight',
            'anemia' => 'anemia',
            'swell' => 'swell',
            'blood_pressure' => 'blood pressure',
            'uterus_height' => 'uterus height',
            'baby_presentation' => 'baby presentation',
            'baby_heart_beat' => 'baby heart beat',
            'other' => 'other',
            'iron_pills' => 'iron pills',
            'worm_medicine' => 'worm medicine',
            'td_vaccine' => 'td vaccine',
            'status' => 'status',
        );
        
        $this->validate($request, [
            'visit_date' => 'required|string',
            'weight' => 'required|string',
            'anemia' => 'required|string',
            'swell' => 'required|string',
            'blood_pressure' => 'required|string',
            'uterus_height' => 'required|string',
            'baby_presentation' => 'required|string',
            'baby_heart_beat' => 'required|string',
            'other' => 'required|string',
            'iron_pills' => 'required|string',
            'worm_medicine' => 'required|string',
            'td_vaccine' => 'required|string',
            'status' => 'required|numeric',

        ],array(), $attributes);
    }
}
