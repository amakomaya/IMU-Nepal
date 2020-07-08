<?php

namespace App\Listeners;

use App\Events\WomanWasCreated;
use App\Models\Woman;
use App\Notifications\WomanWasCreatedNotify;

class SendWomanWelcomeNotification
{
    public function handle(WomanWasCreated $event)
    {
        if ($user = Woman::where('token', $event->woman->token)->first()) {
            $user->notify(new WomanWasCreatedNotify($event->woman->toArray()));
        }
    }
}