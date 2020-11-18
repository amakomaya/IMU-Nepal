<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Woman;


class CaseDetailController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }
    function getCaseDetail(Request $request){
        $token = $request->token;
        $data = Woman::withAll()->where('token', $token)->first();

		$phpArray = json_decode($data,true);

        return view('backend.patient.detail', compact('phpArray'));
    }
}
