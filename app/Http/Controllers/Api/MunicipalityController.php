<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Municipality;
use Illuminate\Support\Facades\Cache;

class MunicipalityController extends Controller
{
    public function index(Request $request)
    {
      if($request->has('district')) {
        $district_id = $request->get('district');
          $municipality = Municipality::where('district_id', $district_id)->get();
          return response()->json($municipality);
      } else {
        $municipality = Cache::remember('municipality-list', 48*60*60, function () {
          return Municipality::select(['id', 'municipality_name', 'province_id', 'district_id', 'municipality_name_np', 'type', 'total_no_of_wards'])->get();
        });
        return response()->json($municipality);
      }
    }
}
