<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\District;
use Illuminate\Support\Facades\Cache;

class DistrictController extends Controller
{
    public function index(Request $request)
    {
        if($request->has('province')) {
          $province_id = $request->get('province');
          $district = District::where('province_id', $province_id)->get();
          return response()->json($district);
        } else {
          $district = Cache::remember('district-list', 48*60*60, function () {
            return District::select(['id', 'district_name'])->get();
          });
          return response()->json($district);
        }
    }
}
