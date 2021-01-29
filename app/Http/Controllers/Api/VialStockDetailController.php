<?php

namespace App\Http\Controllers\Api;

use App\VialStockDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VialStockDetailController extends Controller
{
    public function index(Request $request)
    {
        $hpCode = $request->hp_code;
        $data = VialStockDetail::where('hp_code',$hpCode)->get();
        return response()->json($data);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $data = $request->json()->all();
        try {
            foreach ($data as $input){
                try {
                    VialStockDetail::create($input);
                }catch (\Exception $e){
                }
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong, Please try again.']);
        }
        return response()->json(['message' => 'Data Successfully Sync']);
    }
    public function show(VialStockDetail $vialStockDetail)
    {
        //
    }
    public function edit(VialStockDetail $vialStockDetail)
    {
        //
    }
    public function update(Request $request)
    {
        $data = $request->json()->all();
        foreach ($data as $value) {
            try {
                VialStockDetail::where('token', $value['token'])->update($value);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Something went wrong, Please try again.']);
            }
        }
        return response()->json(['message' => 'Data Successfully Sync and Update']);
    }
    public function destroy(VialStockDetail $vialStockDetail)
    {
        //
    }
}
