<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\Models\District;
use Illuminate\Support\Facades\Cache;
class ExtDistrictController extends Controller
{
    public function index()
    {
        $userCheck = new UserCheck();
        $user = $userCheck->checkUserExists(request()->getUser(),request()->getPassword());

        if ($user) {
            $district = Cache::remember('district-list', 48*60*60, function () {
              return District::select(['id', 'district_name', 'province_id' ])->get();
            });
            return response()->json($district);
        }
        return ['message'=>'Authentication Failed'];
    }
}