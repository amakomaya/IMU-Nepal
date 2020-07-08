<?php

namespace App\Services\Api;

use App\Models\HIVSyphillisTest;
use App\Models\SyncLogs;
use Carbon\Carbon;

class HIVSyphillisTestService
{
    public function index($request){
        $hpCode = $request->hp_code;
        $response = HIVSyphillisTest::byHpCode($hpCode)->get();
        return response()->json($response);
    }

    public function store($request){
        $records = json_decode($request->getContent(), true);
        $log = SyncLogs::create(['data'=> json_encode($records)]);
        $savedWomanToken = []; 
        $errors = [];
        foreach ($records as $value) {
            try {
                $data = HIVSyphillisTest::create($value);
                array_push($savedWomanToken, $data->token);
                SyncLogs::where('id', $log->id)->update([
                    'completed_at' => Carbon::now()
                    ]);
            } catch (\Exception $exception) {
                $error = [
                    'unSavedWomanToken' => $value['token'],
                    'error_code' => $exception->errorInfo[1],
                    'message' => $exception->errorInfo[2]
                ];
                array_push($errors, $error);
            }
        }
        $response = [
            'data' => ['savedWomanTokens' => $savedWomanToken],
            'error' => $errors,
        ];
        return response()->json($response);
    }

    public function update($request){
        $records = json_decode($request->getContent(), true);
        $log = SyncLogs::create(['data'=> json_encode($records)]);
        foreach ($records as $value) {
            try {
                HIVSyphillisTest::where('token', $value['token'])->update($value);
                SyncLogs::where('id', $log->id)->update([
                    'completed_at' => Carbon::now()
                    ]);
            } catch (\Exception $exception) {
                
            }
        }
        $response = 1;
        return response()->json($response);
    }
}