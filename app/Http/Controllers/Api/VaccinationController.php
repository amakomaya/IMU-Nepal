<?php

namespace App\Http\Controllers\Api;

use App\Models\HealthProfessional;
use App\Models\VaccinationRecord;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\District;

class VaccinationController extends Controller
{
    public function index()
    {
        $responses = HealthProfessional::latest()->take(20)->get();
        return response()->json($responses);
    }

    public function qrSearch(Request $request)
    {
        $token = $request->token;
        $responses = HealthProfessional::where('id', $token)->first();
        if (!empty($responses)) {
            $responses['vaccination_record'] = VaccinationRecord::where('vaccinated_id', $responses->id)->get();
            return response()->json($responses);
        } else {
            return response()->json(['message' => 'Data Not Found']);
        }
    }

    public function record(Request $request)
    {
        $data = $request->json()->all();
        try {
            foreach ($data as $input) {
                try {
                    VaccinationRecord::create($input);
                } catch (\Exception $e) {
                }
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong, Please try again.']);
        }
        return response()->json(['message' => 'Data Successfully Sync']);
    }

    public function store(Request $request){
        dd($request->getContent());
        $data['token'] = md5(microtime(true) . mt_Rand());
        $data['vaccinated_id'] = '';
        $data['hp_code'] = '';
        $data['vaccine_name'] = 'Covi Shield';
        $data['vaccine_period'] = '1M';
        $data['vaccinated_date_en'] = '';
        $data['vaccinated_date_np'] = '';
        $data['status'] = 1;
    }
}
