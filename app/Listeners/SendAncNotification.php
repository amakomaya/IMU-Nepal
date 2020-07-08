<?php

namespace App\Listeners;

use App\Events\AncWasCreated;
use App\Models\Woman;
use App\Notifications\WomanAncNotify;

class SendAncNotification
{
    public function handle(AncWasCreated $event)
    {
        if ($user = Woman::where('token', $event->anc->woman_token)->first()) {
            $user->notify(new WomanAncNotify($event->anc->toArray()));
        }
    }
}