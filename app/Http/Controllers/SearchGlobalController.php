<?php


namespace App\Http\Controllers;


use App\Models\HealthProfessional;
use App\Models\MunicipalityInfo;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class SearchGlobalController extends Controller
{
    public function index(Request $request){
        $data = collect();
        if (collect($request->all())->values()->unique()->count() > 1) {
            $data = HealthProfessional::where('status', 1);
        }
        try {
            if ($request->has('municipality_id')) {
                $municipality_token = MunicipalityInfo::where('municipality_id', $request->municipality_id)->first()->token;
                $healthpost_token = Organization::where('municipality_id', $request->municipality_id)->pluck('token');
                $token = collect($healthpost_token)->merge($municipality_token);
                $data->whereIn('checked_by', $token);
            }
        }catch (\Exception $e){}

        try {
            if ($request->has('name')) {
                $data->where("name", "like", "%" . $request->name . "%");
            }
        }catch (\Exception $e){}

        try {
            if ($request->has('age')) {
                $data->where("age", "like", "%" . $request->age . "%");
            }
        }catch (\Exception $e){}

        try{
            if ($request->has('phone')) {
                $data->where("phone", "like", "%" . $request->phone . "%");
            }
        }catch (\Exception $e){}

        $filter = (count($data) > 0) ? $data->take(10)->get() : [];

        return view('global-search', compact('filter'));
    }

    public function card(Request $request, $id){
        $cryptId = Crypt::decryptString($id);

        $data = HealthProfessional::where('id', $cryptId)->first();
        return view('health-professional.global-card', compact('data'));
    }

}