<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\Models\Municipality;
use App\User;
use Illuminate\Support\Facades\Cache;

class ExtMunicipalityController extends Controller
{
    public function index()
    {
        $userCheck = new UserCheck();
        $user = $userCheck->checkUserExists(request()->getUser(),request()->getPassword());

        if ($user) {
            $municipalities = Cache::remember('municipality-list', 48*60*60, function () {
              return Municipality::select(['id', 'municipality_name', 'province_id', 'district_id', 'municipality_name_np', 'type', 'total_no_of_wards'])->get();
            });
            $data = collect($municipalities)->map(function ($row) {
                $response = [];
                $response['id'] = $row->id;
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