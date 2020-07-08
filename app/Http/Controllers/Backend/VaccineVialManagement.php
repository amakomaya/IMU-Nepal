<?php

namespace App\Http\Controllers\Backend;

use App\Models\Healthpost;
use App\Reports\DateFromToRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class VaccineVialManagement extends Controller
{
    public function showAll(Request $request, $hp_code)
    {
        $date = $this->dataFromAndTo($request);


        $stock_data = DB::table('vaccine_vial_stocks')->where('hp_code', $hp_code)
            ->whereBetween('created_at', [$date['from_date'], $date['to_date']])->get();

        $vial_data = DB::table('vial_details')->where('hp_code', $hp_code)
                        ->whereBetween('created_at', [$date['from_date'], $date['to_date']])->groupBy('vial_image')->get();
        $hp_name = Healthpost::where('hp_code', $hp_code)->first()->name;

        return view('backend.vial-management.show-all', compact('stock_data', 'vial_data', 'hp_name'));
    }

    private function dataFromAndTo(Request $request)
    {
        return DateFromToRequest::dateFromTo($request);
    }
}
