<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BabyDetail;
use App\Models\Delivery;
use Illuminate\Support\Facades\DB;
use App\Helpers\ViewHelper;

class BabyDetailController extends Controller
{
    public $msg = array();

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $hpCode = $request->hp_code;
        $heathpost = BabyDetail::where([['hp_code', $hpCode],['status', 1]])->get();
        return response()->json($heathpost);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //card_no
        $json = json_decode($request->getContent(), true);
        $token=array();
        $data = array();
        $response = array();

        foreach ($json as $data) {

            $BabyDetail = new BabyDetail;
            foreach ($data as $key => $record) {
                if($key=="token"){
                    $token[]['token'] = $record;
                }
                
                if($key=='dob_en'){
                    $BabyDetail->dob_en = null;
                }else{
                    $BabyDetail->$key = $record;
                }
            }
            
            
            $BabyDetail->save();
            $babies = DB::table('baby_details')
                      ->select('baby_details.token as baby_token','women.name as mother_name', 'women.husband_name as father_name','women.tole as tole', 'women.caste as caste', 'deliveries.delivery_date as dob_en', 'deliveries.delivery_place as birth_place','baby_details.gender as gender', 'baby_details.status as status', 'baby_details.created_at as created_at','women.hp_code as hp_code','baby_details.weight','healthposts.ward_no','women.phone as contact_no')
                      ->join('deliveries','baby_details.delivery_token','=','deliveries.token')
                      ->join('women','deliveries.woman_token','=','women.token')
                      ->join('healthposts','baby_details.hp_code','=','women.hp_code')
                      ->where('baby_details.token', $BabyDetail->token)
                      ->first();
            $collectData = array();
            foreach ($babies as $key => $value) {
               if($key=="gender"){
                    if($value=="Male"){
                        $collectData[$key]="M";
                    }elseif($value=="Female"){
                        $collectData[$key]="F";
                    }elseif($value=="Other"){
                        $collectData[$key]="O";
                    }

               }elseif($key=="mother_name"){
                    $collectData[$key] = $value;
                    $collectData['name'] = "child of ".$value;
               }elseif($key=="dob_en"){
                    $collectData[$key] = $value;
                    $collectData['dob_np'] = ViewHelper::convertEnglishToNepali($value);
                    $collectData['updated_at'] = date('Y-m-d h:m:s');
                    $collectData['created_at'] = date('Y-m-d h:m:s');
               }elseif($key=="tole"){
                    //
               }elseif($key=="birth_place"){
                    $collectData[$key] = $value;
                    if($value != "Home"){
                        $collectData[$key] = "HealthFacility";
                    }
               }else{
                    $collectData[$key] = $value;
               }
            }

           $response[] = $collectData;

            //Baby Update Self table from women and deliveries               
           $BabyDetail2 = $this->findModelBabyDetail($babies->baby_token);
           $BabyDetail2->dob_en = $babies->dob_en;
           $BabyDetail2->mother_name = $babies->mother_name;
           $BabyDetail2->father_name = $babies->father_name;
           $BabyDetail2->tole = $babies->tole;
           $BabyDetail2->caste = $babies->caste;
           $BabyDetail2->save();

            

        }
        //echo json_encode($response); exit;

        $url = 'http://www.amakomaya.com/api/web/en/v1/babies/123456';
        //$url = 'http://localhost/amakomaya/api/web/en/v1/babies/123456';

        //create a new cURL resource
        $ch = curl_init($url);

        $payload = json_encode($response);

        //attach encoded JSON string to the POST fields
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        //set the content type to application/json
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

        //return response instead of outputting
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //execute the POST request
        $result = curl_exec($ch);

        //close cURL resource
        curl_close($ch);

        return  $result;

        //return response()->json($token);
    }

    public function storeIndividual(Request $request)
    {
        $json = json_decode($request->getContent(), true);
        $token=array();
        foreach ($json as $data) {
            $BabyDetail = new BabyDetail;
            foreach ($data as $key => $record) {
                if($key=="token"){
                    $token[]['token'] = $record;
                }
                $BabyDetail->$key = $record;
                $BabyDetail->save();
            }
        }
        return response()->json($token);
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $hpCode = $request->token;
        $model = $this->findModelBabyDetail($hpCode);
        if(count($this->msg)>0){
            return response()->json($this->msg);
        }
        return response()->json($model);
    }



    public function showBabyDetailByWomanToken(Request $request){
        $womanToken = $request->woman_token;
        $model = $this->findModelBabyDetailByWomanToken($womanToken);
        if(count($this->msg)>0){
            return response()->json($this->msg);        
        }
        return response()->json($model);
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
        $json = json_decode($request->getContent(), true);
        try{
            foreach($json as $data){
                $token = $data['token'];
                BabyDetail::where('token', $token)->update($data);
            }
            return response()->json(1);
        }catch(\Exception $e){
            return response()->json(0);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function findModelBabyDetail($token){
        if (($model = BabyDetail::where('token', $token)->get()->first()) !== null) {
            return $model;
        } else {
            $this->msg = ['name'=>'Not Found', 'message'=>'The requested page doesn\'t exist', 'status'=>'404'];
            return $this;
        }
    }



    public function findModelBabyDetailByWomanToken($woman_token){
        if (($deliveries = Delivery::where('woman_token', $woman_token)->get()) !== null) {
            foreach ($deliveries as $delivery) {
                $deliveryToken[] = $delivery->token;
            }
            if (($model = BabyDetail::whereIn('delivery_token', $deliveryToken)->get()) !== null) {
                return $model;
            } else {
                $this->msg = ['name'=>'Not Found', 'message'=>'The requested page doesn\'t exist', 'status'=>'404'];
                return $this;
            }
        } else {
            $this->msg = ['name'=>'Not Found', 'message'=>'The requested page doesn\'t exist', 'status'=>'404'];
            return $this;
        }
    }

    public function newStore(Request $request)
    {
        $json = json_decode($request->getContent(), true);
        foreach ($json as $data) {

            $BabyDetail = new BabyDetail;
            foreach ($data as $record) {
                $BabyDetail->token = $data['token'];
                $BabyDetail->delivery_token = $data['delivery_token'];
                $BabyDetail->baby_name = 'child of '.$BabyDetail->getMotherName($BabyDetail->delivery_token);
                $BabyDetail->dob_en = $BabyDetail->getDob($BabyDetail->delivery_token);
                $BabyDetail->dob_np = ViewHelper::convertEnglishToNepali($BabyDetail->dob_en);
                $BabyDetail->gender = $data['gender'];
                $BabyDetail->contact_no = $BabyDetail->getContactNumber($BabyDetail->delivery_token);
                $BabyDetail->caste = $BabyDetail->getCaste($BabyDetail->delivery_token);
                $BabyDetail->weight = $data['weight'];
                $BabyDetail->birth_place = $BabyDetail->getChildBirthPlace($BabyDetail->delivery_token);
                $BabyDetail->premature_birth = $data['premature_birth'];
                $BabyDetail->baby_alive = $data['baby_alive'];
                $BabyDetail->mother_name = $BabyDetail->getMotherName($BabyDetail->delivery_token);
                $BabyDetail->father_name = $BabyDetail->getFatherName($BabyDetail->delivery_token);
                $BabyDetail->baby_status = $data['baby_status'];
                $BabyDetail->hp_code = $data['hp_code'];
                $BabyDetail->advice = $data['advice'];
                $BabyDetail->tole = $BabyDetail->getTole($BabyDetail->delivery_token);
                $BabyDetail->ward_no = $BabyDetail->getMotherWardNo($BabyDetail->delivery_token);
                $BabyDetail->status = $data['status'];
                $BabyDetail->created_at = $data['created_at'];
                $BabyDetail->updated_at = $data['updated_at'];                
            }
            $BabyDetail->save();
        }
        return response()->json('1');
    }
}
