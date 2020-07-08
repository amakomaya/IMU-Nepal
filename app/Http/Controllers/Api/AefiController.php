<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Aefi;

class AefiController extends Controller
{ 
    public $msg = array();

    public function index(Request $request)
    {
        $hpCode = $request->hp_code;
        try {
            $heathpost = Aefi::where([['hp_code', $hpCode],['status', '!==', 2]])->get();
            return response()->json($heathpost);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to Get AEFI record, Please contact 01-4428090'], 409);
        }
    }

    public function store(Request $request)
    {

        try {
            $json = json_decode($request->getContent(), true);
            foreach ($json as $data) {
                foreach ($data as $key => $record) {
                    Aefi::updateOrCreate(
                        [
                            'token'                 => $data['token']                       
                        ],
                        [   
                            'baby_token'            => $data['baby_token'],
                            'hp_code'               => $data['hp_code'],
                            'vaccine'               => $data['vaccine'],
                            'vaccinated_date'       => $data['vaccinated_date'],
                            'aefi_date'             => $data['aefi_date'],
                            'problem'               => $data['problem'],
                            'advice'                =>$data['advice'],
                            'status'                => $data['status'],
                            'created_at'            => $data['created_at'],
                            'updated_at'            => $data['updated_at']     
                        ]);
                }
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to Sync AEFI record, Please contact 01-4428090'], 409);
        }
    }
}