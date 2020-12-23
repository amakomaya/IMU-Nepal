<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\Models\LabTest;
use App\Models\OrganizationMember;
use App\Models\SampleCollection;
use App\User;
use Illuminate\Http\Request;

class ExtLabTestController extends Controller
{
    public function index()
    {
        $key = request()->getUser();
        $secret = request()->getPassword();

        $user = User::where([['username', $key], ['password', md5($secret)], ['role', 'healthworker']])->get()->first();

        if (!empty($user)) {
            $healthworker = OrganizationMember::where('token', $user->token)->get()->first();
            $data = LabTest::where('hp_code', $healthworker->hp_code)->get();
            return response()->json($data);
        }
        return ['message' => 'Authentication Failed'];
    }
    public function store(Request $request){
        $userCheck = new UserCheck();
        $user = $userCheck->checkUserExists(request()->getUser(),request()->getPassword());

        if ($user) {
            $data = $request->json()->all();
            foreach ($data as $value) {
                try {
                    if ($value['sample_test_date'] == '') {
                        $value['sample_test_result'] = 9;
                        LabTest::create($value);
                        SampleCollection::where('token', $value['sample_token'])->update(['result' => '9']);
                    } else {
                        SampleCollection::where('token', $value['sample_token'])->update(['result' => $value['sample_test_result']]);
                        $find_test = LabTest::where('token', $value['token']);
                        if ($find_test) {
                            $find_test->update([
                                'sample_test_date' => $value['sample_test_date'],
                                'sample_test_time' => $value['sample_test_time'],
                                'sample_test_result' => $value['sample_test_result'],
                                'checked_by' => $value['checked_by'],
                                'hp_code' => $value['hp_code'],
                                'status' => $value['status'],
                                'created_at' => $value['created_at'],
                                'checked_by_name' => $value['checked_by_name']
                            ]);
                        } else {
                            LabTest::create($value);
                        }
                    }
                } catch (\Exception $e) {
                    return response()->json(['message' => 'Something went wrong, Please try again.']);
                }
            }
            return response()->json(['message' => 'Data Successfully Sync']);
        }
        return ['message'=>'Authentication Failed'];
    }
}