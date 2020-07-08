<?php

namespace App\Http\Controllers\Backend\Baby;

use App\Models\BabyDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BabyController extends Controller
{
    public function edit()
    {
        return view('backend.baby.edit');
    }
}