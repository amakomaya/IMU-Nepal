<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\Models\OrganizationMember;
use App\Models\Symptoms;
use App\User;
use Illuminate\Http\Request;

class ExtSymptomsController extends Controller
{
    public function index()
    {
        $key = request()->getUser();
        $secret = request()->getPassword();

        $user = User::where([['username', $key], ['password', md5($secret)], ['role', 'healthworker']])->get()->first();

        if (!empty($user)) {
            $healthworker = OrganizationMember::where('token', $user->token)->get()->first();
            $data = Symptoms::where('org_code', $healthworker->org_code)->get();
            return response()->json($data);
        }
        return ['message' => 'Authentication Failed'];
    }

    public function store(Request $request)
    {
        $userCheck = new UserCheck();
        $user = $userCheck->checkUserExists(request()->getUser(), request()->getPassword());

        if ($user) {
            $data = $request->json()->all();
            try {
                Symptoms::insert($data);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Something went wrong, Please try again.']);
            }
            return response()->json(['message' => 'Data Successfully Sync']);
        }
        return ['message' => 'Authentication Failed'];
    }
}
