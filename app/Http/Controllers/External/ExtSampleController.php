<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\Models\OrganizationMember;
use App\Models\SampleCollection;
use App\User;
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
                $response['woman_token'] = $row->woman_token ?? '';
                $response['checked_by'] = $row->checked_by ?? '';
                $response['hp_code'] = $row->hp_code ?? '';
                $response['status'] = $row->status ?? '';
                $response['created_at'] = $row->created_at ?? '';
                $response['updated_at'] = $row->updated_at ?? '';
                $response['checked_by_name'] = $row->checked_by_name ?? '';
                $response['sample_type'] = $row->sample_type ?? '';
                $response['sample_type_specific'] = $row->sample_type_specific ?? '';
                $response['sample_case_specific'] = $row->sample_case_specific ?? '';
                $response['sample_case'] = $row->sample_case ?? '';
                $response['sample_identification_type'] = $row->sample_identification_type ?? '';
                $response['service_type'] = $row->service_type ?? '';
                $response['result'] = $row->result ?? '';
                $response['infection_type'] = $row->infection_type ?? '';
                $response['service_for'] = $row->service_for ?? '';
                return $response;
            })->values();
            return response()->json($data);
        }
        return ['message' => 'Authentication Failed'];
    }

    public function store(Request $request){
        $checkUser = new UserCheck();
        $user = $checkUser->checkUserExists(request()->getUser(), request()->getPassword());

        if ($user){
            $data = $request->json()->all();
            try {
                SampleCollection::insert($data);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Something went wrong, Please try again.']);
            }
            return response()->json(['message' => 'Data Sussessfully Sync']);
        }
        return ['message'=>'Authentication Failed'];
    }
}
