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
                $response['sex'] = $row->sex ?? '';
                $response['caste'] = $row->caste ?? '';
                $response['province_id'] = $row->province_id ?? '';
                $response['district_id'] = $row->district_id ?? '';
                $response['municipality_id'] = $row->municipality_id ?? '';
                $response['ward'] = $row->ward ?? '';
                $response['tole'] = $row->tole ?? '';
                $response['chronic_illness'] = $row->chronic_illness ?? '';
                $response['symptoms'] = $row->symptoms ?? '';
                $response['travelled'] = $row->travelled ?? '';
                $response['travelled_date'] = $row->travelled_date ?? '';
                $response['travel_medium'] = $row->travel_medium ?? '';
                $response['travel_detail'] = $row->travel_detail ?? '';
                $response['travelled_where'] = $row->travelled_where ?? '';
                $response['hp_code'] = $row->hp_code ?? '';
                $response['registered_device'] = $row->registered_device ?? '';
                $response['created_by'] = $row->created_by ?? '';
                $response['longitude'] = $row->longitude ?? '';
                $response['latitude'] = $row->latitude ?? '';
                $response['status'] = $row->status ?? '';
                $response['created_at'] = $row->created_at ?? '';
                $response['updated_at'] = $row->updated_at ?? '';
                $response['age_unit'] = $row->age_unit ?? 0;
                $response['occupation'] = $row->occupation ?? '';
                $response['symptoms_specific'] = $row->symptoms_specific ?? '';
                $response['symptoms_comorbidity'] = $row->symptoms_comorbidity ?? '';
                $response['symptoms_comorbidity_specific'] = $row->symptoms_comorbidity_specific ?? '';
                $response['screening'] = $row->screening ?? '';
                $response['screening_specific'] = $row->screening_specific ?? '';
                $response['emergency_contact_one'] = $row->emergency_contact_one ?? '';
                $response['emergency_contact_two'] = $row->emergency_contact_two ?? '';
                $response['cases'] = $row->cases ?? '';
                $response['case_where'] = $row->case_where ?? '';
                $response['end_case'] = $row->end_case ?? '';
                $response['payment'] = $row->payment ?? '';
                $response['result'] = $row->sample_result ?? '';
                if ($response['result'] == '4') {
                    $response['case_id'] = $row->case_id ?? '';
                } else {
                    $response['case_id'] = '';
                }

                $response['parent_case_id'] = $row->parent_case_id ?? '';
                $response['symptoms_recent'] = $row->symptoms_recent ?? '';
                $response['symptoms_within_four_week'] = $row->symptoms_within_four_week ?? '';
                $response['symptoms_date'] = $row->symptoms_date ?? '';
                $response['case_reason'] = $row->case_reason ?? '';
                $response['temperature'] = $row->temperature ?? '';
                $response['date_of_onset_of_first_symptom'] = $row->date_of_onset_of_first_symptom ?? '';
                $response['reson_for_testing'] = $row->reson_for_testing ?? '';

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
        $userCheck = new UserCheck();
        $user = $userCheck->checkUserExists(request()->getUser(),request()->getPassword());

        if ($user) {
            $data = $request->json()->all();
            try {
                SuspectedCase::insert($data);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Something went wrong, Please try again.']);
            }
            return response()->json(['message' => 'Data Successfully Sync']);
        }
        return ['message' => 'Authentication Failed'];
    }
}