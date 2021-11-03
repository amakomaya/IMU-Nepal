<?php

namespace App\Http\Controllers\Api;

use App\VialDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VialDetailController extends Controller
{

    public function index(Request $request)
    {
        $hpCode = $request->org_code;
        $data = VialDetail::where('org_code',$hpCode)->get();
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
                    VialDetail::create($input);
                }catch (\Exception $e){
                }
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong, Please try again.']);
        }
        return response()->json(['message' => 'Data Successfully Sync']);
    }

    public function show(VialDetail $vialDetail)
    {
        //
    }
    public function edit(VialDetail $vialDetail)
    {
        //
    }
    public function update(Request $request)
    {
        $data = $request->json()->all();
        foreach ($data as $value) {
            try {
                VialDetail::where('vial_image', $value['vial_image'])->update($value);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Something went wrong, Please try again.']);
            }
        }
        return response()->json(['message' => 'Data Successfully Sync and Update']);
    }
    public function destroy(VialDetail $vialDetail)
    {
        //
    }
}
