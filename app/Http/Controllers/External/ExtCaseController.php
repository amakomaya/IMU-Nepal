<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\Models\OrganizationMember;
use App\Models\SuspectedCase;
use App\User;
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
                ->leftJoin('ancs', 'ancs.woman_token', '=', 'women.token')
                ->where('women.hp_code', $healthworker->hp_code)
                ->where('women.end_case', 0)
                ->select('women.*', 'ancs.result as sample_result')
                ->get();
            $data = collect($record)->map(function ($row) {

                $response = [];

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
                $response['emergency_contact_two'] = $row->emergency_contact_two ?? '';
                $response['created_at'] = $row->created_at ?? '';

                $response['result'] = $row->sample_result ?? '';
//                if ($response['result'] == '4') {
//                    $response['case_id'] = $row->case_id ?? '';
//                } else {
//                    $response['case_id'] = '';
//                }
                return $response;
            })->values();

            $filtered = $data->filter(function ($value, $key) {
                return $value['result'] !== '4';
            })->values();

            return response()->json($filtered);
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
                    SuspectedCase::insert([
                        'token' =>  md5(microtime(true).mt_Rand()),
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
                        'emergency_contact_two' => $value['emergency_contact_two'],
                        'status' => 1,
                        'hp_code' => $healthworker->hp_code,
                        'created_by' => $healthworker->token
                    ]);
                } catch (\Exception $e) {
                    return response()->json(['message' => 'Something went wrong, Please try again.']);
                }
            }
            return response()->json(['message' => 'Data Successfully Sync']);
        }
        return ['message' => 'Authentication Failed'];
    }
}