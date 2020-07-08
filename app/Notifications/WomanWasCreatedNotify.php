<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class WomanWasCreatedNotify extends Notification
{
    use Queueable;
    protected $array;

    public function __construct(array $array)
    {
        $this->array = $array;
    }

    public function via()
    {
        return ['database'];
    }

    public function toDatabase()
    {
        return $this->array;
    }
}