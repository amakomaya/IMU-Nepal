<?php

namespace App\Services\Api;

use App\Helpers\ViewHelper;
use App\Models\HealthWorker;
use App\Models\Woman;
use App\Notifications\WomanNotify;
use Exception;
use Illuminate\attributesbase\Eloquent\Model;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class ContentAppNotificationService
{
    public function getNotification($request)
    {
        $woman_token = $request->woman_token;
        $notifiable = Woman::getNotifiableByToken($woman_token);
        try {
            return response()->json($notifiable->notifications->map(function ($notifications, $keys) {
                $data = [];
                $data['token'] = $notifications->id;
                $data['type'] = $this->notificationType($notifications->type);
                $data['message'] = $this->message($data['type'], $notifications);
                $data['notified_by'] = $this->getNotifiedBy($data['type'], $notifications);
                $data['notified_at'] = $notifications->created_at;
                $data['read_at'] = $notifications->read_at;
                return $data;
            }));
        } catch (Exception $e) {
            return [];
        }
    }

    // From AMC application

    private function notificationType($type)
    {
        switch ($type) {
            case "App\Notifications\WomanNotify":
                return 11;
                break;
            case "App\Notifications\WomanWasCreatedNotify":
                return 1;
                break;
            case "App\Notifications\WomanAncNotify":
                return 2;
            default:
                return 0;
        }
    }

    private function message(int $type, $notification)
    {
        switch ($type) {
            case 11:
                return $notification->data['message'];
                break;
            case 1:
                return "आमाकोमाया एप्स मा हजुरलाई स्वागत छ । आमा र बच्चा को स्वस्थ जीवन नै हाम्रो उद्देश्य";
                break;

            case 2:
                return convertToNepali(ViewHelper::convertEnglishToNepali($notification->data['visit_date'])) . " गते को गर्भवती जांच रिपोर्ट हेर्नुहोस्";
                break;

            default:
                return "आमाकोमाया एप्स मा हजुरलाई स्वागत छ । आमा र बच्चा को स्वस्थ जीवन नै हाम्रो उद्देश्य";
        }
    }

    private function getNotifiedBy(int $type, $notification)
    {
        switch ($type) {
            case 11:
                return HealthWorker::findHealthWorkerByToken($notification->data['notified_by']);
                break;

            case 1:
                return HealthWorker::findHealthWorkerByToken($notification->data['created_by']);
                break;

            case 2:
                return HealthWorker::findHealthWorkerByToken($notification->data['checked_by']);
                break;

            default:
                return "आमाकोमाया एप्स";
        }
    }

    public function postNotification($request)
    {
        $json = json_decode($request->getContent(), true);
        $unSavedWomanToken = [];
        foreach ($json as $value) {
            $user = Woman::getNotifiableByToken($value['woman_token']);
            try {
                if (sizeof($user) > 0) {
                    $user->notify(new WomanNotify($value));
                }
            } catch (Exception $exception) {
                array_push($unSavedWomanToken, $value['woman_token']);
            }
        }
        return response()->json(['unsaved_woman_token' => $unSavedWomanToken]);
    }

    public function updateReadAt($request){
        $json = json_decode($request->getContent(), true);
        $unSavedToken = [];

        foreach ($json as $value) {
            try {
                DB::table('notifications')->where(['id' => $value['token']])->update(['read_at' => $value['read_at']]);
            } catch (Exception $exception) {
                array_push($unSavedToken, $value['token']);
            }
        }
        return response()->json(['unsaved_token' => $unSavedToken]);
    }
}