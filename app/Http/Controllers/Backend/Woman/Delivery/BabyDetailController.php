<?php

namespace App\Http\Controllers\Backend\Woman\Delivery;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BabyDetail;
use App\Models\Delivery;
use App\Models\Woman;

class BabyDetailController extends Controller
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
        $deliveryId = $request->get('delivery_id');
        $delivery = $this->findModelDelivery($deliveryId);
        $womanName= Woman::getWomanName($delivery->woman_token);
        $babyDetails = BabyDetail::where('delivery_token',$delivery->token)->get();
        return view('backend.woman.delivery.baby-detail.index',compact('babyDetails','delivery', 'womanName'));
    }

    public function baby(Request $request){
        $data = (new Woman)->getWomanInfo();
        if($request->get('list')=="baby"){
            $babyDetails = BabyDetail::whereIn('token', $data['baby'])->get();
        }else{
            $babyDetails = BabyDetail::all()->latest();
        }
        
        return view('backend.woman.delivery.baby-detail.baby',compact('babyDetails'));
    }

    public function babyShow($id){
        $data = $this->findModel($id);
        $delivery = $this->findModelDeliveryByToken($data->delivery_token);
        $womanName= Woman::getWomanName($delivery->woman_token);
        return view('backend.woman.delivery.baby-detail.baby-show',compact('data','womanName'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $deliveryId = $request->get('delivery_id');
        $delivery = $this->findModelDelivery($deliveryId);
        return view('backend.woman.delivery.baby-detail.create',compact('delivery'));
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

         BabyDetail::create([
            'token'               => uniqid().time(),
            'delivery_token'               => $request->get('delivery_token'),
            'gender'               => $request->get('gender'),
            'weight'               => $request->get('weight'),
            'premature_birth'               => $request->get('premature_birth'),
            'baby_alive'               => $request->get('baby_alive'),
            'baby_status'               => $request->get('baby_status'),
            'advice'               => $request->get('advice'),
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
        $childId = $request->get('child_id');
        $data = $this->findModel($childId);
        $delivery = $this->findModelDeliveryByToken($data->delivery_token);
        $womanName= Woman::getWomanName($delivery->woman_token);
        return view('backend.woman.delivery.baby-detail.show',compact('data','womanName'));
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

        if(BabyDetail::find($id)===null)
        {
            abort(404,'Page not found');
        }else{
            return $model = BabyDetail::find($id);
        }
    }


    protected function findModelDelivery($id){

        if(Delivery::find($id)===null)
        {
            abort(404,'Page not found');
        }else{
            return $model = Delivery::find($id);
        }
    }




    protected function findModelDeliveryByToken($token){

        if(Delivery::where('token',$token)->get()->first()===null)
        {
            abort(404,'Page not found');
        }else{
            return $model = Delivery::where('token',$token)->get()->first();
        }
    }
    
    protected function validateForm(Request $request){

        $attributes = array(
            'gender' => 'gender',
            'weight' => 'weight',
            'premature_birth' => 'premature birth',
            'baby_alive' => 'baby alive',
            'baby_status' => 'baby status',
            'advice' => 'advice',
        );
        
        $this->validate($request, [
            'gender' => 'required|string',
            'weight' => 'required|string',
            'premature_birth' => 'required|string',
            'baby_alive' => 'required|string',
            'baby_status' => 'required|string',
            'advice' => 'required|string',

        ],array(), $attributes);
    }
}
