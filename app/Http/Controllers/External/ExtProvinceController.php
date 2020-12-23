<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\Models\OrganizationMember;
use App\Models\province;
use App\User;

class ExtProvinceController extends Controller
{
    public function index()
    {
        $userCheck = new UserCheck();
        $user = $userCheck->checkUserExists(request()->getUser(),request()->getPassword());

        if ($user) {
            $provinces = province::all();
            return response()->json($provinces);
        }
        return ['message' => 'Authentication Failed'];
    }
}