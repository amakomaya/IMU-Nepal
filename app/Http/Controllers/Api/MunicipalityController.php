<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Municipality;

class MunicipalityController extends Controller
{
    public function index()
    {
        $municipality = Municipality::all();
        return response()->json($municipality);
    }
}
