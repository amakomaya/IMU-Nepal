<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\District;

class DistrictController extends Controller
{
    public function index(Request $request)
    {
        if($request->has('province')) {
          $province_id = $request->get('province');
          $district = District::where('province_id', $province_id)->get();
          return response()->json($district);
        } else {
          $district = District::all();
          return response()->json($district);
        }
    }
}
