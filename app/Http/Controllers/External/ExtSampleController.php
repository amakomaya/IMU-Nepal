<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\Models\OrganizationMember;
use App\Models\SampleCollection;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExtSampleController extends Controller
{
    public function index()
    {
        $key = request()->getUser();
        $secret = request()->getPassword();

        $user = User::where([['username', $key], ['password', md5($secret)], ['role', 'healthworker']])->get()->first();

        if (!empty($user)) {
            $healthworker = OrganizationMember::where('token', $user->token)->get()->first();
            $record = DB::table('ancs')->where('hp_code', $healthworker->hp_code)->get();
            $data = collect($record)->map(function ($row) {
                $response = [];
                $response['token'] = $row->token;
                $response['case_token'] = $row->woman_token ?? '';
                $response['sample_type'] = $row->sample_type ?? '';
                $response['sample_type_specific'] = $row->sample_type_specific ?? '';
                $response['service_type'] = $row->service_type ?? '';
                $response['result'] = $row->result ?? '';
                $response['infection_type'] = $row->infection_type ?? '';
                $response['service_for'] = $row->service_for ?? '';
                $response['created_at'] = $row->created_at ?? '';
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
    public function store(Request $request){
        $key = request()->getUser();
        $secret = request()->getPassword();

        $user = User::where([['username', $key], ['password', md5($secret)], ['role', 'healthworker']])->get()->first();

        if (!empty($user)) {
            $healthworker = OrganizationMember::where('token', $user->token)->get()->first();
            $id = OrganizationMember::where('token', $user->token)->first()->id;
            $swab_id = str_pad($id, 4, '0', STR_PAD_LEFT).'-'.Carbon::now()->format('ymd').'-'.$this->convertTimeToSecond(Carbon::now()->format('H:i:s'));
            $swab_id = generate_unique_sid($swab_id);
            $data = $request->json()->all();
            foreach ($data as $value) {
                try {
                    SampleCollection::insert([
                        'token' =>  $swab_id,
                        'woman_token' => $value['case_token'],
                        'sample_type' => $value['sample_type'],
                        'sample_type_specific' => $value['sample_type_specific'],
                        'sample_case' => $value['sample_case'],
                        'sample_case_specific' => $value['sample_case_specific'],
                        'service_type' => $value['service_type'],
                        'result' => $value['result'],
                        'infection_type' => $value['infection_type'],
                        'service_for' => $value['service_for'],
                        'hp_code' => $healthworker->hp_code,
                        'status' =>1
                    ]);
                } catch (\Exception $e) {
                    return response()->json(['message' => 'Something went wrong, Please try again.']);
                }
            }
            return response()->json(['message' => 'Data Sussessfully Sync']);
        }
        return ['message'=>'Authentication Failed'];
    }
}
