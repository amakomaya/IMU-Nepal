<?php


namespace App\Http\Controllers;


use App\Models\HealthProfessional;
use App\Models\MunicipalityInfo;
use App\Models\Organization;
use Illuminate\Http\Request;

class SearchGlobalController extends Controller
{
    public function index(Request $request){
        $data = HealthProfessional::where('status', 1);

        if ($request->has('municipality_id')) {
            $municipality_token = MunicipalityInfo::where('municipality_id', $request->municipality_id)->first()->token;
            $healthpost_token = Organization::where('municipality_id', $request->municipality_id)->pluck('token');
            $token = collect($healthpost_token)->merge($municipality_token);
            $data->whereIn('checked_by', $token);
        }

        if ($request->has('name')) {
            $data->where("name", "like", "%" . $request->name . "%");
        }
        if ($request->has('age')) {
            $data->where("age", "like", "%" . $request->age . "%");
        }
        if ($request->has('phone')) {
            $data->where("phone", "like", "%" . $request->phone . "%");
        }

        $filter = $data->get();


        return view('global-search', compact($filter));
    }
}