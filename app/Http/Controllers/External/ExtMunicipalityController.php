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
            $data = collect($municipalities)->map(function ($row) {
                $response = [];
                $response['province_id'] = $row->province_id;
                $response['district_id'] = $row->district_id ?? '';
                $response['district_name'] = $row->district_name ?? '';
                $response['municipality_name'] = $row->municipality_name ?? '';
                return $response;
            })->values();
            return response()->json($data);
        }
        return ['message' => 'Authentication Failed'];
    }
}