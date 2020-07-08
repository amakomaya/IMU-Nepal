<?php

namespace App\Http\Controllers\Backend\Woman;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Healthpost;
use App\Models\Woman;

class DeliveryController extends Controller
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
        $woman = $this->findModelWoman($request->get('woman_id'));
        $deliveries = Delivery::where('woman_token', $woman->token)->latest()->get();
        return view('backend.woman.delivery.index',compact('deliveries', 'woman'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $healthposts = Healthpost::all();
        $woman = $this->findModelWoman($request->get('woman_id'));
        return view('backend.woman.delivery.create',compact('healthposts', 'woman'));
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

         
         Delivery::create([
            'token'               => uniqid().time(),
            'woman_token'               => $request->get('woman_token'),
            'delivery_date'               => $request->get('delivery_date'),
            'delivery_time'               => $request->get('delivery_time'),
            'delivery_place'               => $request->get('delivery_place'),
            'presentation'               => $request->get('presentation'),
            'delivery_type'               => $request->get('delivery_type'),
            'compliexicty'               => $request->get('compliexicty'),
            'other_problem'               => $request->get('other_problem'),
            'advice'               => $request->get('advice'),
            'miscarriage_status'               => $request->get('miscarriage_status'),
            'hp_code'               => Delivery::getLoggedInHealthpost($request->get('woman_token')),
            'delivery_by_token'               => "doctor123",
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
        $data = $this->findModel($request->get('delivery_id'));
        return view('backend.woman.delivery.show',compact('data'));
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
        $delivery = $this->findModel($id);
        
        $delivery->delete();

        $request->session()->flash('message', 'Data Deleted successfully');

        return redirect()->route('delivery.index');
    }



    protected function findModel($id){

        if(Delivery::find($id)===null)
        {
            abort(404,'Page not found');
        }else{
            return $model = Delivery::find($id);
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
            'delivery_place' => 'delivery place',
            'presentation' => 'presentation',
            'delivery_type' => 'delivery type',
            'compliexicty' => 'compliexicty',
            'other_problem' => 'other problem',
            'advice' => 'advice',
            'miscarriage_status' => 'miscarriage status',
            'status' => 'status',
        );
        
        $this->validate($request, [
            'delivery_date' => 'required|string',
            'delivery_time' => 'required|string',
            'delivery_place' => 'required|string',
            'presentation' => 'required|string',
            'delivery_type' => 'required|string',
            'compliexicty' => 'required|string',
            'other_problem' => 'required|string',
            'advice' => 'required|string',
            'miscarriage_status' => 'required|numeric',
            'status' => 'required|numeric',

        ],array(), $attributes);
    }


}
