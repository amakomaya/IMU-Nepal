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
            $record = LabTest::where('org_code', $healthworker->org_code)->get();

            $data = collect($record)->map(function ($row) use ($user){
                $response = [];
                $response['token'] = $row->token;
                $response['sample_token'] = $row->sample_token ?? '';
                $response['sample_recv_date'] = $row->sample_recv_date ?? '';
                $response['sample_test_date'] = $row->sample_test_date ?? '';
                $response['sample_test_time'] = $row->sample_test_time ?? '';
                $response['sample_test_result'] = $row->sample_test_result ?? '';
                $response['created_at'] = $row->created_at ?? '';
                return $response;
            })->values();

            return response()->json($data);
        }
        return ['message' => 'Authentication Failed'];
    }

    public function store(Request $request)
    {
        $key = request()->getUser();
        $secret = request()->getPassword();

        $user = User::where([['username', $key], ['password', md5($secret)], ['role', 'healthworker']])->get()->first();

        if (!empty($user)) {
            $healthworker = OrganizationMember::where('token', $user->token)->get()->first();
            $data = $request->json()->all();
            foreach ($data as $value) {
                try {
                    if ($value['sample_test_date'] == '') {
                        $value['sample_test_result'] = '9';
                        if($value['token']) $value['token'] = $user->token.'-'.$value['token'];
                        LabTest::create($value);
                        SampleCollection::where('token', $value['sample_token'])->update(['result' => '9']);
                    } else {
                        SampleCollection::where('token', $value['sample_token'])->update(['result' => strval($value['sample_test_result'])]);
                        $find_test = LabTest::where('token', $user->token.'-'.$value['token']); //check all the token for users
                        if ($find_test) {
                            $find_test->update([
                                'sample_test_date' => $value['sample_test_date'],
                                'sample_test_time' => $value['sample_test_time'],
                                'sample_test_result' => strval($value['sample_test_result']),
                                'checked_by' => $value['checked_by'],
                                'org_code' => $value['org_code'],
                                'status' => $value['status'],
                                'created_at' => $value['created_at'],
                                'checked_by_name' => $value['checked_by_name']
                            ]);
                        } else {
                            try {
                                LabTest::insert([
                                    'token' => $user->token.'-'.md5(microtime(true).mt_Rand()),
                                    'sample_token' => $value['sample_token'],
                                    'sample_recv_date' => $value['sample_recv_date'],
                                    'sample_test_date' => $value['sample_test_date'],
                                    'sample_test_time' => $value['sample_test_time'],
                                    'sample_test_result' => strval($value['sample_test_result']),
                                    'org_code' => $healthworker->org_code,
                                    'checked_by' => $healthworker->token,
                                    'status' => 1
                                ]);
                            } catch (\Exception $e) {
                                return response()->json(['message' => 'Something went wrong, Please try again.']);
                            }
                        }
                    }
                } catch (\Exception $e) {
                    return response()->json(['message' => 'Something went wrong, Please try again.']);
                }
            }
            return response()->json(['message' => 'Data Successfully Sync']);
        }
        return ['message' => 'Authentication Failed'];
    }
}