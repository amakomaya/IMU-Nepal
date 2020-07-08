<?php


namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Woman;
use App\Notifications\SendMessageFromWebNotify;
use Exception;
use Illuminate\Http\Request;


class SendMessageController extends Controller
{
    public function fromWebAdmin(Request $request)
    {
        $json = json_decode($request->getContent(), true);
        $unSavedWomanToken = [];
        $savedWomanToken = [];
        foreach ($json['woman_token'] as $value) {
            $payload = [
                "woman_token" => $value,
                "message" => $json['message'],
                'notified_by' => $json['notified_by'],
                'notified_at' =>$json['notified_at']
            ];
            $user = Woman::getNotifiableByToken($value);
            try {
                if (sizeof($user) > 0) {
                    $user->notify(new SendMessageFromWebNotify($payload));
                    array_push($savedWomanToken, $value['woman_token']);
                }
            } catch (Exception $exception) {
                array_push($unSavedWomanToken, $value['woman_token']);
            }
        }
        return response()->json(['error_woman_token' => $unSavedWomanToken, 'success_woman_token' => $savedWomanToken]);
    }
}