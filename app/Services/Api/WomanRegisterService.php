<?php


namespace App\Services\Api;

use App\Events\WomanWasCreated;
use App\Models\Woman;
use Exception;

class WomanRegisterService
{
    public function store($request){
        $json = json_decode($request->getContent(), true);
        $savedWomanToken = [];
        $errors = [];
        foreach ($json as $value) {
            try{
                $woman = Woman::create($value);
                array_push($savedWomanToken, $woman->token);
                event(new WomanWasCreated($woman));
            } catch (Exception $exception){
                $error = [
                    'unSavedWomanToken' => $value['token'],
                    'error_code' => $exception->errorInfo[1],
                    'message' => $exception->errorInfo[2]
                ];
                array_push($errors, $error );
            }
        }

        $response = [
            'data' =>   [ 'savedWomanTokens' => $savedWomanToken ],
            'error' =>  $errors,
        ];

        return response()->json($response);
    }
}