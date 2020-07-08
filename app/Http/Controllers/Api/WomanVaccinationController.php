<?php

namespace App\Http\Controllers\Api;

use App\Models\Woman\Vaccination;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class WomanVaccinationController extends Controller
{

    public function index(Request $request)
    {
        $hpCode = $request->hp_code;
        $records = Vaccination::where([['hp_code', $hpCode],['status', 1]])->get();
        return response()->json($records);
    }

    public function store(Request $request)
    {
        $json = json_decode($request->getContent(), true);
        try{
            Vaccination::insert($json);
            return response()->json(1);
        }catch(\Exception $e){
            return response()->json(0);
        }
    }

    public function update(Request $request)
    {
        $json = json_decode($request->getContent(), true);
        try{
            foreach($json as $data){
                $id = $data['id'];
                Vaccination::where('id', $id)->update($data);
            }
            return response()->json(1);
        }catch(\Exception $e){
            return response()->json(0);
        }
    }
}
