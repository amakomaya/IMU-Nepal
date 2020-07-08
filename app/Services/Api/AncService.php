<?php

namespace App\Services\Api;

use App\Events\AncWasCreated;
use App\Models\Anc;

class AncService
{
    public function store($request){
        $json = json_decode($request->getContent(), true);
        $savedWomanAncToken = [];
        $errors = [];
        foreach ($json as $value) {
            try {
                $anc = Anc::create($value);
                array_push($savedWomanAncToken, $anc->token);
                event(new AncWasCreated($anc));
            } catch (\Exception $exception) {
                $error = [
                    'unSavedWomanAncToken' => $value['token'],
                    'error_code' => $exception->errorInfo[1],
                    'message' => $exception->errorInfo[2]
                ];
                array_push($errors, $error);
            }
        }
        $response = [
            'data' => ['savedWomanAncTokens' => $savedWomanAncToken],
            'error' => $errors,
        ];
        return response()->json($response);
    }
}