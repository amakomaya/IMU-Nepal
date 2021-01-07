<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\Models\District;

class ExtDistrictController extends Controller
{
    public function index()
    {
        $userCheck = new UserCheck();
        $user = $userCheck->checkUserExists(request()->getUser(),request()->getPassword());

        if ($user) {
            $district = District::all();
            return response()->json($district);
        }
        return ['message'=>'Authentication Failed'];
    }
}