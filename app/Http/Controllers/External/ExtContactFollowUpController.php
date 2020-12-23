<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\Models\ContactFollowUp;
use App\Models\OrganizationMember;
use App\User;
use Illuminate\Http\Request;

class ExtContactFollowUpController extends Controller
{
    public function index()
    {
        $key = request()->getUser();
        $secret = request()->getPassword();

        $user = User::where([['username', $key], ['password', md5($secret)], ['role', 'healthworker']])->get()->first();

        if (!empty($user)) {
            $healthworker = OrganizationMember::where('token', $user->token)->get()->first();
            $data = ContactFollowUp::where('hp_code', $healthworker->hp_code)->get();
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
                ContactFollowUp::insert($data);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Something went wrong, Please try again.']);
            }
            return response()->json(['message' => 'Data Successfully Sync']);
        }
        return ['message' => 'Authentication Failed'];
    }
}