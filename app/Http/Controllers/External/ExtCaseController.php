<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\Models\LabTest;
use App\Models\OrganizationMember;
use App\Models\SampleCollection;
use App\Models\SuspectedCase;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ExtCaseController extends Controller
{
    public function index()
    {
        $key = request()->getUser();
        $secret = request()->getPassword();

        $user = User::where([['username', $key], ['password', md5($secret)], ['role', 'healthworker']])->get()->first();

        if (!empty($user)) {
            $healthworker = OrganizationMember::where('token', $user->token)->get()->first();
            $record = DB::table('women')
                ->join('ancs', 'ancs.woman_token', '=', 'women.token')
                ->join('lab_tests', 'lab_tests.sample_token', '=', 'ancs.token')
                ->where('women.hp_code', $healthworker->hp_code)
                ->where('women.end_case', 0)
                ->select('women.token','women.name','women.age','women.age_unit','women.sex','women.caste','women.province_id','women.district_id','women.municipality_id','women.ward',
                    'women.tole','women.emergency_contact_one','women.travelled','women.occupation','women.created_at'
                    ,'ancs.token as sample_token', 'ancs.service_for', 'ancs.service_type','ancs.sample_type',
                    'ancs.infection_type','ancs.created_at as sample_created_at','lab_tests.sample_recv_date', 'lab_tests.sample_test_date','lab_tests.sample_test_time',
                    'lab_tests.sample_test_result')
                ->get();
            $data = collect($record)->map(function ($row) {
                $response['token'] = $row->token;
                $response['name'] = $row->name ?? '';
                $response['age'] = $row->age ?? '';
                $response['age_unit'] = $row->age_unit ?? 0;
                $response['sex'] = $row->sex ?? '';
                $response['caste'] = $row->caste ?? '';
                $response['province_id'] = $row->province_id ?? '';
                $response['district_id'] = $row->district_id ?? '';
                $response['municipality_id'] = $row->municipality_id ?? '';
                $response['ward'] = $row->ward ?? '';
                $response['tole'] = $row->tole ?? '';
                $response['emergency_contact_one'] = $row->emergency_contact_one ?? '';
                $response['travelled'] = $row->travelled ?? '';
                $response['occupation'] = $row->occupation ?? '';
                $response['registered_at'] = $row->created_at ?? '';
                $response['sample_token'] = $row->sample_token ?? '';
                $response['service_for'] = $row->service_for ?? '';
                $response['service_type'] = $row->service_type ?? '';
                $response['sample_type'] = $row->sample_type ?? '';
                $response['sample_collected_date'] = $row->sample_created_at ?? '';
                $response['infection_type'] = $row->infection_type ?? '';
                $response['lab_received_date'] = $row->sample_recv_date ?? '';
                $response['lab_test_date'] = $row->sample_test_date ?? '';
                $response['lab_test_time'] = $row->sample_test_time ?? '';
                $response['lab_result'] = $row->sample_test_result ?? '';

                return $response;
            })->values();

            return response()->json($data);
        }
        return ['message' => 'Authentication Failed'];
    }

    private function convertTimeToSecond(string $time): int
    {
        $d = explode(':', $time);
        return ($d[0] * 3600) + ($d[1] * 60) + $d[2];
    }

    public function store(Request $request)
    {
        $key = request()->getUser();
        $secret = request()->getPassword();

        $user = User::where([['username', $key], ['password', md5($secret)], ['role', 'healthworker']])->get()->first();

        if (!empty($user)) {
            $healthworker = OrganizationMember::where('token', $user->token)->get()->first();
            $data = json_decode($request->getContent(), true);
            foreach ($data as $value) {
                $case_token = 'a-' . md5(microtime(true) . mt_Rand());
                $case = [
                    'token' => $case_token,
                    'name' => $value['name'],
                    'age' => $value['age'],
                    'age_unit' => $value['age_unit'],
                    'caste' => $value['caste'],
                    'sex' => $value['sex'],
                    'province_id' => $value['province_id'],
                    'district_id' => $value['district_id'],
                    'municipality_id' => $value['municipality_id'],
                    'ward' => $value['ward'],
                    'tole' => $value['tole'],
                    'emergency_contact_one' => $value['emergency_contact_one'],
                    'travelled' => $value['travelled'],
                    'occupation' => $value['occupation'],
                    'created_at' => $value['registered_at'],
                    'status' => 1,
                    'hp_code' => $healthworker->hp_code,
                    'created_by' => $healthworker->token
                ];
                $id = OrganizationMember::where('token', $user->token)->first()->id;
                $swab_id = str_pad($id, 4, '0', STR_PAD_LEFT) . '-' . Carbon::now()->format('ymd') . '-' . $this->convertTimeToSecond(Carbon::now()->format('H:i:s'));
                $sample = [
                    'token' => $swab_id,
                    'woman_token' => $case_token,
                    'service_for' => $value['service_for'],
                    'service_type' => $value['service_type'],
                    'sample_type' => $value['sample_type'],
                    'created_at' => $value['sample_collected_date'],
                    'infection_type' => $value['infection_type'],
                    'result' => $value['lab_result'],
                    'hp_code' => $healthworker->hp_code,
                    'status' => 1
                ];
                $lab_test = [
                    'token' => md5(microtime(true) . mt_Rand()),
                    'sample_token' => $swab_id,
                    'sample_recv_date' => $value['lab_received_date'],
                    'sample_test_date' => $value['lab_test_date'],
                    'sample_test_time' => $value['lab_test_time'],
                    'sample_test_result' => $value['lab_result'],
                    'hp_code' => $healthworker->hp_code,
                    'checked_by' => $healthworker->token,
                    'status' => 1
                ];
                try {
                    SuspectedCase::insert($case);
                    SampleCollection::insert($sample);
                    LabTest::insert($lab_test);
                } catch (\Exception $e) {
                    return response()->json(['message' => 'Something went wrong, Please try again.']);
                }
            }
            return response()->json(['message' => 'Data Successfully Sync']);
        }
        return ['message' => 'Authentication Failed'];
    }

    public function getCaseDetailBySample(Request $request)
    {
        $key = request()->getUser();
        $secret = request()->getPassword();
        $sample_token = $request->sample_token;

        $user = User::where([['username', $key], ['password', md5($secret)], ['role', 'healthworker']])->get()->first();

        if (!empty($user)) {
            $healthworker = OrganizationMember::where('token', $user->token)->get()->first();
            $record = DB::table('ancs')
                ->join('women', 'women.token', '=', 'ancs.woman_token')
                ->join('lab_tests', 'lab_tests.sample_token', '=', 'ancs.token')
                ->where('ancs.token', $sample_token)
                ->where('women.hp_code', $healthworker->hp_code)
                ->where('women.end_case', 0)
                ->select('women.token','women.name','women.age','women.age_unit','women.sex','women.caste','women.province_id','women.district_id','women.municipality_id','women.ward',
                    'women.tole','women.emergency_contact_one','women.travelled','women.occupation','women.created_at'
                    ,'ancs.token as sample_token', 'ancs.service_for', 'ancs.service_type','ancs.sample_type',
                    'ancs.infection_type','ancs.created_at as sample_created_at','lab_tests.sample_recv_date', 'lab_tests.sample_test_date','lab_tests.sample_test_time',
                    'lab_tests.sample_test_result')
                ->get();
            $data = collect($record)->map(function ($row) {
                $response['token'] = $row->token;
                $response['name'] = $row->name ?? '';
                $response['age'] = $row->age ?? '';
                $response['age_unit'] = $row->age_unit ?? 0;
                $response['sex'] = $row->sex ?? '';
                $response['caste'] = $row->caste ?? '';
                $response['province_id'] = $row->province_id ?? '';
                $response['district_id'] = $row->district_id ?? '';
                $response['municipality_id'] = $row->municipality_id ?? '';
                $response['ward'] = $row->ward ?? '';
                $response['tole'] = $row->tole ?? '';
                $response['emergency_contact_one'] = $row->emergency_contact_one ?? '';
                $response['travelled'] = $row->travelled ?? '';
                $response['occupation'] = $row->occupation ?? '';
                $response['registered_at'] = $row->created_at ?? '';
                $response['sample_token'] = $row->sample_token ?? '';
                $response['service_for'] = $row->service_for ?? '';
                $response['service_type'] = $row->service_type ?? '';
                $response['sample_type'] = $row->sample_type ?? '';
                $response['sample_collected_date'] = $row->sample_created_at ?? '';
                $response['infection_type'] = $row->infection_type ?? '';
                $response['lab_received_date'] = $row->sample_recv_date ?? '';
                $response['lab_test_date'] = $row->sample_test_date ?? '';
                $response['lab_test_time'] = $row->sample_test_time ?? '';
                $response['lab_result'] = $row->sample_test_result ?? '';

                return $response;
            })->values();

            return response()->json($data);
        }
        return ['message' => 'Authentication Failed'];
    }
}