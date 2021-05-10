<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Municipality;

class MunicipalityController extends Controller
{
    public function index(Request $request)
    {
      if($request->has('district')) {
        $district_id = $request->get('district');
          $municipality = Municipality::where('district_id', $district_id)->get();
          return response()->json($municipality);
      } else {
        $municipality = Municipality::all();
        return response()->json($municipality);
      }
    }
}
