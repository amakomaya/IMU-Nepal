<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\Models\OrganizationMember;
use App\Models\Province;
use App\User;
use Illuminate\Support\Facades\Cache;

class ExtProvinceController extends Controller
{
    public function index()
    {
        $userCheck = new UserCheck();
        $user = $userCheck->checkUserExists(request()->getUser(),request()->getPassword());

        if ($user) {
            $provinces = Cache::remember('province-list', 48*60*60, function () {
              return Province::select(['id', 'province_name'])->get();
            });
            return response()->json($provinces);
        }
        return ['message' => 'Authentication Failed'];
    }
}