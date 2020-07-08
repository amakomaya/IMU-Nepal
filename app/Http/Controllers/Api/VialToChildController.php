<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BabyDetail;
use App\Models\Healthpost;
use App\User;
use Illuminate\Http\Request;
use App\Models\SyncLogs;
use Carbon\Carbon;

class VialToChildController extends Controller
{
    public function login(Request $request)
    {
        $username = $request->username;
        $password = $request->password;
        $user = User::where([
            ['username', $username],
            ['password', md5($password)]
        ])
            ->get()
            ->first();
        if (!empty($user)){
            if (!empty($healthpost = Healthpost::where('token', $user->token)->get()->first())) {
                $userInfo = [
                    'success' => 1,
                    'message' => "Successfully Logged in !!!",
                    'name' => $healthpost->name,
                    'phone' => $healthpost->phone,
                    'email' => $healthpost->email,
                    'district' => $healthpost->district_id,
                    'address' => $healthpost->address,
                    'hp_code' => $healthpost->hp_code
                ];
                $position = \Location::get();
                $detect = \Browser::detect();
                $details_log = ['location'=> $position, 'browser' => $detect];
                activity('login')
                ->causedBy($healthpost)
                ->performedOn(new User())
                ->withProperties($request)
                ->log(json_encode($details_log));
                return response()->json($userInfo);
            }
        }
        $response = ['success' => 0, 'message' => 'The requested usename or password doesn\'t exist'];
        return response()->json($response);
    }

    public function baby(Request $request)
    {
        $hpCode = $request->hp_code;
        $heathpost = BabyDetail::where('hp_code', $hpCode)->isAlive()->withInDobThreeYears()->active()->get();
        return response()->json($heathpost);
    }

    public function batchStore(Request $request) 
    {
        $json = json_decode($request->getContent(), true);
        $log = SyncLogs::create(['data'=> json_encode($json)]);
        try{
            BabyDetail::insert($json);

        }catch(Exception $e){
            SyncLogs::where('id', $log->id)->update([
                'completed_at' => Carbon::now()
                ]);
        }
        return response()->json("1");
    }

    public function batchUpdate(Request $request)
    {
        $json = json_decode($request->getContent(), true);
        $log = SyncLogs::create(['data'=> json_encode($json)]);
        try{
            foreach ($json as $data) {
                $token = $data['token'];
                BabyDetail::where('token', $token)->update($data);
            }
            SyncLogs::where('id', $log->id)->update([
                'completed_at' => Carbon::now()
                ]);
        }catch(Exception $e){
        }
        return response()->json("1");
    }

    public function storeBaby(Request $request)
    {
        $json = json_decode($request->getContent(), true);
        foreach ($json as $data) {
            BabyDetail::updateOrCreate(
                ['token' => $data['token']],

                [
                    'baby_name' => isset($data['baby_name']) ? $data['baby_name'] : "",
                    'gender' => isset($data['gender']) ? $data['gender'] : "Male",
                    'caste' => isset($data['caste']) ? intval($data['caste']) : 0,
                    'dob_en' => isset($data['dob_en']) ? date('Y-m-d', strtotime($data['dob_en'])) : date("Y-m-d"),
                    'dob_np' => isset($data['dob_en']) ? date('Y-m-d', strtotime($data['dob_en'])) : date("Y-m-d"),
                    'weight' => isset($data['weight']) ? $data['weight'] : "0",
                    'contact_no' => isset($data['contact_no']) ? $data['contact_no'] : "",
                    'birth_place' => isset($data['birth_place']) ? $data['birth_place'] : "",
                    'mother_name' => isset($data['mother_name']) ? $data['mother_name'] : "",
                    'father_name' => isset($data['father_name']) ? $data['father_name'] : "",
                    'baby_alive' => isset($data['baby_alive']) ? $data['baby_alive'] : "Alive",
                    'hp_code' => isset($data['hp_code']) ? $data['hp_code'] : "amh",
                    'ward_no' => isset($data['ward_no']) ? intval($data['ward_no']) : 0,
                    'card_no' => isset($data['card_no']) ? $data['card_no'] : "",
                    'status' => isset($data['status']) ? intval($data['status']) : 0,
                    'created_at' => $data['created_at'],
                    'updated_at' => $data['updated_at']
                ]
            );
        }
        //return response()->json("1");
        return $json;
    }

    public function storeBabyNew(Request $request)
    {
        $json = json_decode($request->getContent(), true);
        $token = array();
        foreach ($json as $data) {
            $BabyDetail = new BabyDetail;
            foreach ($data as $key => $record) {
                $BabyDetail->token = $data['baby_token'];
                $BabyDetail->baby_name = $data['name'];
                $BabyDetail->dob_en = $data['dob_en'];
                $BabyDetail->dob_np = $data['dob_np'];
                $BabyDetail->gender = $data['gender'];
                $BabyDetail->contact_no = $data['contact_no'];
                $BabyDetail->caste = $data['caste'];
                $BabyDetail->weight = $data['weight'];
                $BabyDetail->birth_place = $data['birth_place'];
                $BabyDetail->mother_name = $data['mother_name'];
                $BabyDetail->father_name = $data['father_name'];
                $BabyDetail->baby_alive = 'Alive';
                $BabyDetail->hp_code = $data['hp_code'];
                $BabyDetail->ward_no = $data['ward_no'];
                $BabyDetail->card_no = $data['card_no'];
                $BabyDetail->status = $data['status'];
                $BabyDetail->created_at = $data['created_at'];
                $BabyDetail->updated_at = $data['updated_at'];
                $BabyDetail->save();
            }
        }
        return response()->json("1");
    }
}