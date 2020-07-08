<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\District;

class DistrictController extends Controller
{
    public function index()
    {
        $district = District::all();
        return response()->json($district);
    }
}
