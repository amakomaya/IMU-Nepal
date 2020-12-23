<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\Models\Municipality;
use App\User;

class ExtMunicipalityController extends Controller
{
    public function index()
    {
        $userCheck = new UserCheck();
        $user = $userCheck->checkUserExists(request()->getUser(),request()->getPassword());

        if ($user) {
            $municipalities = Municipality::all();
            return response()->json($municipalities);
        }
        return ['message' => 'Authentication Failed'];
    }
}